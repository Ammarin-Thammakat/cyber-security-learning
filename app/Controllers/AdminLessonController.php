<?php

namespace App\Controllers;

use App\Models\LessonModel;

class AdminLessonController extends BaseController
{
    // เช็คสิทธิ์ Admin
    private function checkAdmin() {
        if (!session()->get('isLoggedIn') || session()->get('use_role') != 'admin') {
            return false;
        }
        return true;
    }

    // 1. หน้ารายการบทเรียน
    public function index()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $model = new LessonModel();
        // เรียงตามลำดับที่กำหนดไว้
        $data['lessons'] = $model->orderBy('les_order', 'ASC')->findAll();

        return view('admin/lesson/index', $data);
    }

    // 2. หน้าฟอร์มเพิ่ม/แก้ไข บทเรียน
    public function form($id = null)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $model = new LessonModel();
        
        $data['lesson'] = null;
        if($id){
            // ถ้ามี ID ส่งมา แปลว่าเป็นการแก้ไข
            $data['lesson'] = $model->find($id);
        }

        return view('admin/lesson/form', $data);
    }

    // 3. บันทึกข้อมูล (ใช้ได้ทั้งเพิ่มใหม่และแก้ไข)
 public function save()
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $model = new LessonModel();
        $id = $this->request->getVar('les_id');

        $data = [
            'les_title'     => $this->request->getVar('title'),
            'les_desc'      => $this->request->getVar('desc'),
            'les_content'   => $this->request->getVar('content'),
            'les_video_url' => $this->request->getVar('video_url'),
            'les_order'     => $this->request->getVar('order'),
            'les_status'    => $this->request->getVar('status')
        ];

        // --- ส่วนที่เพิ่มใหม่: จัดการไฟล์อัปโหลด ---
        $file = $this->request->getFile('lesson_file');
        
        // เช็คว่ามีการอัปโหลดไฟล์มาไหม และไฟล์สมบูรณ์ไหม
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // ตั้งชื่อไฟล์ใหม่แบบสุ่ม (ป้องกันชื่อซ้ำและชื่อภาษาไทยที่มีปัญหา)
            $newName = $file->getRandomName();
            
            // ย้ายไฟล์ไปที่ public/uploads/lessons
            $file->move('uploads/lessons', $newName);
            
            // เพิ่มชื่อไฟล์ลงใน array ที่จะบันทึก
            $data['les_file'] = $newName;
        }
        // ----------------------------------------

        if($id){
            $model->update($id, $data);
            $msg = 'แก้ไขบทเรียนเรียบร้อย';
        } else {
            $model->save($data);
            $msg = 'เพิ่มบทเรียนใหม่เรียบร้อย';
        }

        return redirect()->to('/admin/lessons')->with('success', $msg);
    }

    // 4. ลบบทเรียน
    public function delete($id)
    {
        if (!$this->checkAdmin()) return redirect()->to('/login');

        $model = new LessonModel();
        $model->delete($id);

        return redirect()->to('/admin/lessons')->with('success', 'ลบบทเรียนเรียบร้อยแล้ว');
    }
}