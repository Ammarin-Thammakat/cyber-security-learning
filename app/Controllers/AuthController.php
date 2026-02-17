<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    // แสดงหน้าฟอร์มสมัครสมาชิก
    public function register()
    {
        helper(['form']); // โหลดตัวช่วยสร้างฟอร์ม
        return view('auth/register');
    }

   public function saveRegister()
    {
        helper(['form']); 
        // กำหนดกฎการตรวจสอบ (Rules) และข้อความแจ้งเตือน (Errors) แบบภาษาไทย
        $rules = [
            'username' => [
                'rules'  => 'required|min_length[3]|max_length[50]|is_unique[users.use_username]',
                'errors' => [
                    'required'   => 'กรุณากรอก Username',
                    'min_length' => 'Username ต้องมีความยาวอย่างน้อย 3 ตัวอักษร',
                    'is_unique'  => 'ชื่อผู้ใช้นี้ (Username) ถูกใช้งานไปแล้ว กรุณาเปลี่ยนชื่อใหม่' // <--- ข้อความแจ้งเตือนที่ต้องการ
                ]
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.use_email]',
                'errors' => [
                    'required'    => 'กรุณากรอก Email',
                    'valid_email' => 'รูปแบบ Email ไม่ถูกต้อง',
                    'is_unique'   => 'Email นี้ถูกใช้งานไปแล้ว กรุณาใช้ Email อื่น' // <--- ข้อความแจ้งเตือนที่ต้องการ
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'กรุณากรอกรหัสผ่าน',
                    'min_length' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร'
                ]
            ],
            'confpassword' => [
                'rules'  => 'matches[password]',
                'errors' => [
                    'matches' => 'รหัสผ่านยืนยันไม่ตรงกับรหัสผ่านแรก'
                ]
            ],
            'name' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'กรุณากรอกชื่อ-นามสกุล'
                ]
            ]
        ];

        // เริ่มตรวจสอบข้อมูล
        if ($this->validate($rules)) {
            $model = new UserModel();
            $data = [
                'use_username' => $this->request->getVar('username'),
                'use_email'    => $this->request->getVar('email'),
                'use_password' => $this->request->getVar('password'), // จะถูก Hash อัตโนมัติใน Model
                'use_name'     => $this->request->getVar('name'),
                'use_role'     => 'user'
            ];
            
            $model->save($data);
            
            // สมัครเสร็จ ส่งไปหน้า Login พร้อมข้อความสีเขียว
            return redirect()->to('/login')->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว กรุณาเข้าสู่ระบบ'); 
        } else {
            // ถ้าไม่ผ่าน (เช่น ชื่อซ้ำ) ให้กลับไปหน้าเดิมพร้อมส่งรายการ Error ไปแสดงผล
            $data['validation'] = $this->validator;
            return view('auth/register', $data);
        }
    }
    
    public function login() {
        return view('auth/login');
    }
    
    // ฟังก์ชันประมวลผลการเข้าสู่ระบบ
    public function attemptLogin()
    {
        $session = session();
        $model = new UserModel();
        
        // รับค่าจากฟอร์ม
        $login_id = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');
        
        // ค้นหา User จาก Username หรือ Email
        $data = $model->where('use_username', $login_id)
                      ->orWhere('use_email', $login_id)
                      ->first();
        
        if ($data) {
            $pass = $data['use_password'];
            // ตรวจสอบรหัสผ่าน (Hash Verify)
            if (password_verify($password, $pass)) {
                
                // ถ้ารหัสถูก ให้จำค่าลง Session
                $ses_data = [
                    'use_id'       => $data['use_id'],
                    'use_username' => $data['use_username'],
                    'use_name'     => $data['use_name'],
                    'use_role'     => $data['use_role'],
                    'isLoggedIn'   => TRUE
                ];
                $session->set($ses_data);
                
                // ตรวจสอบว่าเป็น Admin หรือ User แล้วเด้งไปหน้านั้นๆ
                if($data['use_role'] == 'admin'){
                     return redirect()->to('/admin/dashboard');
                }else{
                     return redirect()->to('/student/dashboard');
                }

            } else {
                $session->setFlashdata('msg', 'รหัสผ่านไม่ถูกต้อง');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'ไม่พบชื่อผู้ใช้งานนี้');
            return redirect()->to('/login');
        }
    }

    
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}