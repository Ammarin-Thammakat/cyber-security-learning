<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        // เช็คว่าล็อกอินจริงไหม (แบบบ้านๆ ก่อน เดี๋ยวสอนทำ Filter ทีหลัง)
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $model = new \App\Models\UserModel();
        $user_id = $session->get('use_id');
        $data['user_info'] = $model->find($user_id);

        $quizModel = new \App\Models\QuizModel();
        $attemptModel = new \App\Models\AttemptModel();

        // A. นับจำนวนข้อสอบทั้งหมดที่มีในระบบ
        $totalQuizzes = $quizModel->countAllResults();

        // B. นับว่า User คนนี้ทำข้อสอบไปกี่วิชาแล้ว (นับแบบไม่ซ้ำวิชา)
        // ใช้ distinct() เพื่อว่าถ้าสอบวิชาเดิมซ้ำ 2 รอบ ก็นับแค่ 1
        $doneQuizzes = $attemptModel->select('qui_id')
                                    ->where('use_id', $user_id)
                                    ->distinct()
                                    ->countAllResults();

        // C. คำนวณเป็นเปอร์เซ็นต์ (ป้องกันการหารด้วย 0)
        $percent = ($totalQuizzes > 0) ? ($doneQuizzes / $totalQuizzes) * 100 : 0;
        
        // ส่งค่าไปที่หน้า View
        $data['progress_percent'] = number_format($percent, 0); // ทศนิยม 0 ตำแหน่ง
        $data['progress_text'] = "$doneQuizzes / $totalQuizzes";

        return view('user/dashboard',$data);
    }
    // ดูประวัติการสอบ
    public function history()
    {
        $session = session();
        $user_id = $session->get('use_id');

        $attemptModel = new \App\Models\AttemptModel();

        // ดึงข้อมูลการสอบ Join กับชื่อวิชา
        $data['attempts'] = $attemptModel->select('quiz_attempts.*, quizzes.qui_title')
            ->join('quizzes', 'quizzes.qui_id = quiz_attempts.qui_id')
            ->where('quiz_attempts.use_id', $user_id)
            ->orderBy('att_date', 'DESC')
            ->findAll();

        return view('user/history', $data);
    }

    // หน้าแก้ไขข้อมูลส่วนตัว
    public function profile()
    {
        $session = session();
        $model = new \App\Models\UserModel();
        $data['user'] = $model->find($session->get('use_id'));

        return view('user/profile', $data);
    }

    // บันทึกการแก้ไข
    public function updateProfile()
    {
        $session = session();
        $model = new \App\Models\UserModel();
        $id = $session->get('use_id');

        // รับค่าเดิมมาก่อน
        $user = $model->find($id);

        $data = [
            'use_name' => $this->request->getVar('name'),
            'use_email' => $this->request->getVar('email'),
        ];

        // 1. ถ้ามีการเปลี่ยนรหัสผ่าน (กรอกลงมา)
        $newPass = $this->request->getVar('password');
        if (!empty($newPass)) {
            // Hash ใหม่แล้วใส่ใน data
            $data['use_password'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        // 2. ถ้ามีการอัปโหลดรูปภาพ
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // ตั้งชื่อไฟล์ใหม่แบบสุ่ม
            $newName = $file->getRandomName();
            // ย้ายไฟล์ไปที่ public/uploads
            $file->move('uploads', $newName);
            // บันทึกชื่อไฟล์ลง DB
            $data['use_avatar'] = $newName;
        }

        // อัปเดตข้อมูล (update() จะไม่ไปยุ่งกับ beforeInsert/Update ที่เราเขียนไว้ถ้าไม่จำเป็น)
        $model->update($id, $data);

        // อัปเดต Session ชื่อให้เป็นปัจจุบัน
        $session->set('use_name', $data['use_name']);

        return redirect()->to('/student/profile')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    public function updateAvatar()
    {
        $session = session();
        $user_id = $session->get('use_id');
        $model = new \App\Models\UserModel();

        $file = $this->request->getFile('avatar_quick');
        
        if($file && $file->isValid() && !$file->hasMoved()){
            // 1. อัปโหลด
            $newName = $file->getRandomName();
            $file->move('uploads', $newName);
            
            // 2. อัปเดต DB
            $model->update($user_id, ['use_avatar' => $newName]);
            
            // 3. อัปเดต Session (เพื่อให้หน้าเว็บเปลี่ยนทันที)
            $session->set('use_avatar', $newName);

            return redirect()->to('/student/dashboard')->with('success', 'เปลี่ยนรูปโปรไฟล์เรียบร้อย');
        }

        return redirect()->to('/student/dashboard')->with('error', 'อัปโหลดรูปไม่สำเร็จ');
    }
}
