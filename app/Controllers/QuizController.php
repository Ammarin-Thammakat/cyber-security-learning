<?php

namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\AttemptModel;
use App\Models\LessonModel;
class QuizController extends BaseController
{
        // หน้าแสดงรายการแบบทดสอบทั้งหมด
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $user_id = $session->get('use_id');
        $quizModel = new QuizModel();

        // ดึงข้อมูลข้อสอบทั้งหมด
        $quizzes = $quizModel->select('quizzes.*, lessons.les_title, lessons.les_id, lessons.les_order')
            ->join('lessons', 'lessons.les_id = quizzes.les_id')
            ->orderBy('lessons.les_order', 'ASC') // เรียงตามลำดับบทเรียน
            ->findAll();

        // วนลูปเช็คสถานะล็อกของแต่ละวิชา
        $data['quizzes'] = [];
        foreach ($quizzes as $q) {
            $q['is_unlocked'] = $this->isQuizUnlocked($user_id, $q['les_id']);
            $data['quizzes'][] = $q;
        }

        return view('quiz/index', $data);
    }

    public function start($lesson_id)
    {
        // ... (โค้ดส่วนต้นเหมือนเดิม) ...
        $quizModel = new QuizModel();
        $questionModel = new QuestionModel();

        // หา Quiz
        $quiz = $quizModel->where('les_id', $lesson_id)->first();
        if (!$quiz) {
            return redirect()->back()->with('error', 'ยังไม่มีแบบทดสอบสำหรับบทเรียนนี้');
        }

        // --- เพิ่มส่วนตรวจสอบสิทธิ์ ตรงนี้ ---
        $session = session();
        $user_id = $session->get('use_id');
        
        if (!$this->isQuizUnlocked($user_id, $lesson_id)) {
            return redirect()->to('/quiz')->with('error', '🔒 คุณต้องผ่านบททดสอบก่อนหน้าให้ได้ก่อน!');
        }
        // ---------------------------------

        $data['quiz'] = $quiz;
        $data['questions'] = $questionModel->where('qui_id', $quiz['qui_id'])->findAll();

        return view('quiz/take', $data);
    }

    // ส่งคำตอบและคำนวณคะแนน
    public function submit()
    {
        $session = session();
        $questionModel = new QuestionModel();
        $attemptModel = new AttemptModel();

        $quiz_id = $this->request->getVar('quiz_id');
        $answers = $this->request->getVar('answers'); // รับค่า array คำตอบ [question_id => user_choice]

        if (empty($answers)) {
            return redirect()->back()->with('error', 'กรุณาตอบคำถามอย่างน้อย 1 ข้อ');
        }

        $total_score = 0;
        $full_score = 0;

        // ดึงเฉลยจาก DB มาเทียบ (เพื่อความปลอดภัย ห้ามตรวจที่หน้าบ้าน)
        foreach ($answers as $que_id => $user_choice) {
            $question = $questionModel->find($que_id);
            if ($question) {
                $full_score += $question['que_score'];
                // ถ้าตอบถูก
                if ($user_choice == $question['que_correct']) {
                    $total_score += $question['que_score'];
                }
            }
        }

        // บันทึกลง Database
        $saveData = [
            'use_id' => $session->get('use_id'),
            'qui_id' => $quiz_id,
            'att_score' => $total_score,
            'att_full_score' => $full_score
        ];

        $attemptModel->save($saveData);
        $attempt_id = $attemptModel->getInsertID(); // เอา ID ล่าสุดที่เพิ่งบันทึก

        // ส่งไปหน้าแสดงผล
        return redirect()->to('/quiz/result/' . $attempt_id);
    }

    // หน้าแสดงผลคะแนน
     public function result($attempt_id)
    {
        $attemptModel = new AttemptModel();
        $quizModel = new QuizModel();
        $lessonModel = new LessonModel(); // เรียกใช้ Lesson Model

        // 1. ดึงข้อมูลผลการสอบ
        $attempt = $attemptModel->find($attempt_id);
        if (!$attempt) return redirect()->to('/quiz');
        
        $quiz = $quizModel->find($attempt['qui_id']);
        
        // 2. ดึงข้อมูลบทเรียนปัจจุบัน
        $currentLesson = $lessonModel->find($quiz['les_id']);

        // 3. เช็คว่าสอบผ่านไหม (เกณฑ์ 50%)
        $isPassed = ($attempt['att_score'] >= ($attempt['att_full_score'] / 2));

        // 4. หาบทเรียนถัดไป (Next Lesson)
        $nextLesson = null;
        if ($isPassed) {
            // หาบทเรียนที่มีลำดับ (order) มากกว่าบทปัจจุบัน และเรียงจากน้อยไปมาก แล้วเอาตัวแรก
            $nextLesson = $lessonModel->where('les_order >', $currentLesson['les_order'])
                                      ->orderBy('les_order', 'ASC')
                                      ->first();
        }

        $data = [
            'attempt' => $attempt,
            'quiz' => $quiz,
            'currentLesson' => $currentLesson,
            'isPassed' => $isPassed,
            'nextLesson' => $nextLesson
        ];

        return view('quiz/result', $data);
    }
    // --- เพิ่มฟังก์ชันนี้ไว้ล่างสุดของ Class ---
    private function isQuizUnlocked($user_id, $les_id)
    {
        // 1. หาข้อมูลบทเรียนของข้อสอบนี้ เพื่อเอาลำดับ (les_order)
        $lessonModel = new LessonModel();
        $currentLesson = $lessonModel->find($les_id);

        if (!$currentLesson) return false; // ถ้าหาบทเรียนไม่เจอ ล็อกไว้ก่อน

        // 2. ถ้าเป็นบทเรียนแรก (ลำดับ 1) ให้เปิดเสมอ
        if ($currentLesson['les_order'] <= 1) {
            return true;
        }

        // 3. หาบทเรียนก่อนหน้า (Previous Lesson)
        $prevLesson = $lessonModel->where('les_order', $currentLesson['les_order'] - 1)->first();
        if (!$prevLesson) return true; // ถ้าไม่มีบทเรียนก่อนหน้า ก็ปล่อยผ่าน

        // 4. หาข้อสอบของบทเรียนก่อนหน้า
        $quizModel = new QuizModel();
        $prevQuiz = $quizModel->where('les_id', $prevLesson['les_id'])->first();
        if (!$prevQuiz) return true; // ถ้าบทเรียนก่อนหน้าไม่มีข้อสอบ ก็ปล่อยผ่าน

        // 5. เช็คว่า User สอบผ่านวิชาก่อนหน้าหรือยัง (คะแนน >= 50%)
        $attemptModel = new AttemptModel();
        $passed = $attemptModel->where('use_id', $user_id)
            ->where('qui_id', $prevQuiz['qui_id'])
            ->where('att_score >=', 'att_full_score / 2', false)
            ->countAllResults();

        return ($passed > 0);
    }
}
