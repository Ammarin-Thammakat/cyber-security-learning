<?php namespace App\Models;
use CodeIgniter\Model;

class AttemptModel extends Model {
    protected $table = 'quiz_attempts';
    protected $primaryKey = 'att_id';
    protected $allowedFields = ['use_id', 'qui_id', 'att_score', 'att_full_score'];
    protected $useTimestamps = false; // เราตั้ง Default Current Timestamp ใน DB ไว้แล้ว
}