<?php

namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\LessonModel;

class AdminQuizController extends BaseController
{
    // เช็ค Admin เหมือนเดิม
    private function checkAdmin() {
        if (!session()->get('isLoggedIn') || session()->get('use_role') != 'admin') {
            return false;
        }
        return true;
    }

    // 1. หน้าแสดงรายชื่อข้อสอบทั้งหมด
    public function index()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $quizModel = new QuizModel();
        $lessonModel = new LessonModel();

        // ดึงข้อสอบพร้อมชื่อบทเรียน (Join)
        $data['quizzes'] = $quizModel->select('quizzes.*, lessons.les_title')
                                     ->join('lessons', 'lessons.les_id = quizzes.les_id')
                                     ->findAll();
        
        // ดึงบทเรียนมาใส่ใน Dropdown สำหรับสร้างข้อสอบใหม่
        $data['lessons'] = $lessonModel->findAll();

        return view('admin/quiz/index', $data);
    }

    // 2. บันทึกหัวข้อสอบใหม่
    public function createQuiz()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $quizModel = new QuizModel();
        
        $data = [
            'les_id' => $this->request->getVar('les_id'),
            'qui_title' => $this->request->getVar('qui_title')
        ];

        $quizModel->save($data);
        return redirect()->to('/admin/quizzes')->with('success', 'สร้างชุดข้อสอบเรียบร้อย');
    }

    // 3. หน้าจัดการคำถาม (เพิ่ม/ลบ ข้อสอบในชุดนั้นๆ)
    public function manage($quiz_id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $quizModel = new QuizModel();
        $questionModel = new QuestionModel();

        $data['quiz'] = $quizModel->find($quiz_id);
        $data['questions'] = $questionModel->where('qui_id', $quiz_id)->findAll();

        return view('admin/quiz/manage_questions', $data);
    }

    // 4. บันทึกคำถามใหม่
    public function addQuestion()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $questionModel = new QuestionModel();
        $quiz_id = $this->request->getVar('quiz_id');

        $data = [
            'qui_id' => $quiz_id,
            'que_text' => $this->request->getVar('question'),
            'que_opt_a' => $this->request->getVar('opt_a'),
            'que_opt_b' => $this->request->getVar('opt_b'),
            'que_opt_c' => $this->request->getVar('opt_c'),
            'que_opt_d' => $this->request->getVar('opt_d'),
            'que_correct' => $this->request->getVar('correct'),
            'que_score' => $this->request->getVar('score')
        ];

        $questionModel->save($data);
        return redirect()->to('/admin/quiz/manage/'.$quiz_id)->with('success', 'เพิ่มคำถามสำเร็จ');
    }

    // 5. ลบคำถาม
    public function deleteQuestion($id, $quiz_id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');
        
        $questionModel = new QuestionModel();
        $questionModel->delete($id);

        return redirect()->to('/admin/quiz/manage/'.$quiz_id)->with('success', 'ลบคำถามเรียบร้อย');
    }

     public function editQuestion($id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $questionModel = new QuestionModel();
        $quizModel = new QuizModel();

        // ดึงข้อมูลคำถาม
        $data['question'] = $questionModel->find($id);

        if (!$data['question']) {
            return redirect()->back()->with('error', 'ไม่พบคำถามที่ต้องการแก้ไข');
        }

        // ดึงข้อมูล Quiz เพื่อเอาไว้แสดงชื่อหัวข้อหรือทำปุ่มย้อนกลับ
        $data['quiz'] = $quizModel->find($data['question']['qui_id']);

        return view('admin/quiz/edit_question', $data);
    }

    // --- ฟังก์ชันใหม่ 2: บันทึกการแก้ไข ---
    public function updateQuestion()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $questionModel = new QuestionModel();
        
        $que_id = $this->request->getVar('que_id'); // ID คำถาม
        $quiz_id = $this->request->getVar('quiz_id'); // ID Quiz (เอาไว้ redirect กลับ)

        $data = [
            'que_text'    => $this->request->getVar('question'),
            'que_opt_a'   => $this->request->getVar('opt_a'),
            'que_opt_b'   => $this->request->getVar('opt_b'),
            'que_opt_c'   => $this->request->getVar('opt_c'),
            'que_opt_d'   => $this->request->getVar('opt_d'),
            'que_correct' => $this->request->getVar('correct'),
            'que_score'   => $this->request->getVar('score')
        ];

        $questionModel->update($que_id, $data);

        return redirect()->to('/admin/quiz/manage/'.$quiz_id)->with('success', 'แก้ไขคำถามเรียบร้อยแล้ว');
    }
}