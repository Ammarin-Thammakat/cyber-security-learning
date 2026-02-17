<?php

namespace App\Controllers;

use App\Models\TicketModel;

class SupportController extends BaseController
{
    // หน้าแสดงรายการแจ้งปัญหา + ฟอร์มส่งเรื่อง
    public function index()
    {
        if(!session()->get('isLoggedIn')) return redirect()->to('/login');
        
        $model = new TicketModel();
        $user_id = session()->get('use_id');

        // ดึงประวัติการแจ้งปัญหาของตัวเอง เรียงจากล่าสุด
        $data['tickets'] = $model->where('use_id', $user_id)
                                 ->orderBy('tic_created_at', 'DESC')
                                 ->findAll();

        return view('support/index', $data);
    }

    // บันทึกการแจ้งปัญหา
    public function create()
    {
        if(!session()->get('isLoggedIn')) return redirect()->to('/login');

        $model = new TicketModel();
        
        $data = [
            'use_id' => session()->get('use_id'),
            'tic_subject' => $this->request->getVar('subject'),
            'tic_message' => $this->request->getVar('message'),
            'tic_status' => 'pending' // สถานะเริ่มต้นคือ รอดำเนินการ
        ];

        $model->save($data);

        return redirect()->to('/support')->with('success', 'ส่งเรื่องแจ้งปัญหาเรียบร้อยแล้ว ผู้ดูแลระบบจะตรวจสอบโดยเร็วที่สุด');
    }
}