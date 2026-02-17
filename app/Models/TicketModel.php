<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'tic_id';
    protected $allowedFields = ['use_id', 'tic_subject', 'tic_message', 'tic_status'];
    protected $useTimestamps = false; // เราใช้ Default Current Timestamp ของ MySQL แล้ว
}