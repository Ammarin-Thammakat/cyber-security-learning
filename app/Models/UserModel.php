<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'use_id';
    protected $allowedFields = ['use_username', 'use_password', 'use_name', 'use_email', 'use_role','use_avatar'];

    // ระบบความปลอดภัย: ให้ทำการเข้ารหัสรหัสผ่านอัตโนมัติก่อนบันทึกลง DB
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['use_password'])) {
            // ใช้ PASSWORD_DEFAULT ซึ่งปัจจุบันคือ Algorithm ที่ปลอดภัยที่สุด
            $data['data']['use_password'] = password_hash($data['data']['use_password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}