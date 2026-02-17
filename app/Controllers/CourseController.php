<?php

namespace App\Controllers;

use App\Models\LessonModel;
use App\Models\QuizModel;
use App\Models\AttemptModel;

class CourseController extends BaseController
{
    // แสดงรายชื่อบทเรียนทั้งหมด
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new LessonModel();
        // ดึงบทเรียนทั้งหมดเรียงตามลำดับ
        $lessons = $model->orderBy('les_order', 'ASC')->findAll();

        $user_id = $session->get('use_id');
        $data['lessons'] = [];

        // วนลูปเช็คสถานะล็อกของแต่ละบทเรียน
        foreach ($lessons as $l) {
            // เรียกใช้ฟังก์ชันที่เราเพิ่งสร้าง
            $l['is_unlocked'] = $this->isLessonUnlocked($user_id, $l['les_order']);
            $data['lessons'][] = $l;
        }

        return view('course/index', $data);
    }

    // หน้าเข้าเรียน (ดูวิดีโอ + อ่านเนื้อหา)
    public function learn($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $user_id = $session->get('use_id');

        $model = new LessonModel();
        $data['lesson'] = $model->find($id);

        if (empty($data['lesson'])) {
            return redirect()->to('/course')->with('error', 'ไม่พบบทเรียนนี้');
        }

        // --- เพิ่มส่วนเช็คสิทธิ์ตรงนี้ ---
        $isUnlocked = $this->isLessonUnlocked($user_id, $data['lesson']['les_order']);

        if (!$isUnlocked) {
            // ถ้ายังไม่ปลดล็อก ให้ดีดกลับไปหน้ารวม พร้อมแจ้งเตือน
            return redirect()->to('/course')->with('error', '🔒 คุณต้องเรียนและทำแบบทดสอบบทก่อนหน้าให้ผ่านก่อน!');
        }
        // -----------------------------

        return view('course/player', $data);
    }

    private function isLessonUnlocked($user_id, $lesson_order)
    {
        // ถ้าเป็นบทเรียนแรก (ลำดับที่ 1) ให้ผ่านเสมอ
        if ($lesson_order <= 1) {
            return true;
        }

        // หาบทเรียนก่อนหน้า (Previous Lesson)
        $lessonModel = new LessonModel();
        $prevLesson = $lessonModel->where('les_order', $lesson_order - 1)->first();

        // ถ้าไม่มีบทเรียนก่อนหน้า (อาจจะระบบผิดพลาด) ให้ผ่านไปเลย
        if (!$prevLesson) return true;

        // หาข้อสอบของบทเรียนก่อนหน้า
        $quizModel = new QuizModel();
        $prevQuiz = $quizModel->where('les_id', $prevLesson['les_id'])->first();

        // ถ้าบทเรียนก่อนหน้า "ไม่มีข้อสอบ" ถือว่าผ่าน (ให้ไปต่อได้เลย)
        if (!$prevQuiz) return true;

        // เช็คว่า User เคยสอบผ่านวิชานั้นหรือยัง (เกณฑ์: คะแนน >= 50%)
        $attemptModel = new AttemptModel();
        $passed = $attemptModel->where('use_id', $user_id)
            ->where('qui_id', $prevQuiz['qui_id'])
            ->where('att_score >=', 'att_full_score / 2', false) // เช็คว่าคะแนนเกินครึ่ง
            ->countAllResults();

        return ($passed > 0);
    }
}
