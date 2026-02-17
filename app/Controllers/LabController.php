<?php

namespace App\Controllers;

use App\Models\SimulationModel; 
use CodeIgniter\Controller;

class LabController extends BaseController
{
    public function index()
    {
        if(!session()->get('isLoggedIn')) return redirect()->to('/login');

        // เราจะใช้ Query Builder เรียกตรงๆ เลยก็ได้เพื่อความรวดเร็ว (หรือจะสร้าง Model ก็ได้)
        $db = \Config\Database::connect();
        $data['labs'] = $db->table('simulations')->get()->getResultArray();

        return view('lab/index', $data);
    }

    public function play($type)
    {
        if(!session()->get('isLoggedIn')) return redirect()->to('/login');

        // เช็คว่า Lab ชื่อนี้มีไฟล์จริงไหม
        if(file_exists(APPPATH . 'Views/lab/scenarios/' . $type . '.php')){
            return view('lab/scenarios/' . $type);
        } else {
            return redirect()->to('/lab')->with('error', 'ไม่พบ Lab ดังกล่าว');
        }
    }
}