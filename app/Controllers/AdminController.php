<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\QuizModel;
use App\Models\AttemptModel;
use App\Models\TicketModel;

class AdminController extends BaseController
{
    private function checkAdmin()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('use_role') != 'admin') {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $userModel = new UserModel();
        $quizModel = new QuizModel();
        $attemptModel = new AttemptModel();
        $ticketModel = new TicketModel();
        $db = \Config\Database::connect(); 

        $count_users = $userModel->where('use_role', 'user')->countAllResults();
        $count_quizzes = $quizModel->countAllResults();
        $count_attempts = $attemptModel->countAllResults();
        $pending_tickets = $ticketModel->where('tic_status', 'pending')->countAllResults();

        // --- แก้ไขจุดที่ 1: คำนวณคะแนนเฉลี่ย (Best Attempt) เฉพาะ role = 'user' ---
        // JOIN ตาราง users เพื่อเช็ค use_role
        $sqlAvg = "SELECT AVG(user_max) as avg_val 
                   FROM (
                       SELECT MAX(qa.att_score) as user_max 
                       FROM quiz_attempts qa
                       JOIN users u ON qa.use_id = u.use_id
                       WHERE u.use_role = 'user'
                       GROUP BY qa.use_id, qa.qui_id
                   ) as user_best_scores";
                   
        $queryAvg = $db->query($sqlAvg);
        $avg_raw = $queryAvg->getRow()->avg_val ?? 0;
        // --------------------------------------------------------------------------------

        // --- แก้ไขจุดที่ 2: ปรับอัตราสำเร็จ (Success Rate) เฉพาะ role = 'user' ---
        // นับจำนวน (User + Quiz) ที่ทำคะแนนได้เกินครึ่ง อย่างน้อย 1 ครั้ง
        $sqlPass = "SELECT COUNT(*) as passed_count 
                    FROM (
                        SELECT MAX(qa.att_score) as max_score 
                        FROM quiz_attempts qa
                        JOIN users u ON qa.use_id = u.use_id
                        WHERE u.use_role = 'user'
                        GROUP BY qa.use_id, qa.qui_id
                    ) as best_attempts 
                    WHERE max_score >= 12.5"; // 12.5 คือครึ่งหนึ่งของ 25

        $queryPass = $db->query($sqlPass);
        $passed_count = $queryPass->getRow()->passed_count ?? 0;

        // ตัวหารคือ จำนวน (User + Quiz) ทั้งหมดที่มีการสอบ (Unique Attempts) เฉพาะ User
        $sqlTotalUnique = "SELECT COUNT(*) as total 
                           FROM (
                                SELECT qa.use_id 
                                FROM quiz_attempts qa
                                JOIN users u ON qa.use_id = u.use_id
                                WHERE u.use_role = 'user'
                                GROUP BY qa.use_id, qa.qui_id
                           ) as t";
        $total_unique = $db->query($sqlTotalUnique)->getRow()->total ?? 0;
        
        $success_rate = ($total_unique > 0) ? ($passed_count / $total_unique) * 100 : 0;

        $data = [
            'count_users' => $count_users,
            'count_quizzes' => $count_quizzes,
            'count_attempts' => $count_attempts,
            'pending_tickets' => $pending_tickets,
            'recent_users' => $userModel->orderBy('use_created_at', 'DESC')->findAll(5),
            
            // ส่งค่าคะแนนดิบไป
            'avg_score' => number_format($avg_raw, 2),    
            'success_rate' => number_format($success_rate, 2) 
        ];

        return view('admin/dashboard', $data);
    }

    // ... (ฟังก์ชันอื่นๆ เหมือนเดิม) ...
    public function users()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');
        
        $userModel = new UserModel();
        $search = $this->request->getVar('search'); 

        if ($search) {
            $data['users'] = $userModel->groupStart()
                                        ->like('use_name', $search)
                                        ->orLike('use_email', $search)
                                        ->orLike('use_username', $search)
                                       ->groupEnd()
                                       ->findAll();
            $data['search_keyword'] = $search;
        } else {
            $data['users'] = $userModel->findAll();
            $data['search_keyword'] = null;
        }

        return view('admin/manage_users', $data);
    }

    public function deleteUser($id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function tickets()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');
        $ticketModel = new TicketModel();
        $data['tickets'] = $ticketModel->select('tickets.*, users.use_username, users.use_email')
                                       ->join('users', 'users.use_id = tickets.use_id')
                                       ->orderBy('tic_status', 'ASC')
                                       ->orderBy('tic_created_at', 'DESC')
                                       ->findAll();
        return view('admin/tickets', $data);
    }

    public function resolveTicket($id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');
        $ticketModel = new TicketModel();
        $ticketModel->update($id, ['tic_status' => 'resolved']);
        return redirect()->to('/admin/tickets')->with('success', 'ดำเนินการแก้ไขปัญหาเรียบร้อย');
    }
}