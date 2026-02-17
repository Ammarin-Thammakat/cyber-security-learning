<?php namespace App\Models;
use CodeIgniter\Model;

class QuestionModel extends Model {
    protected $table = 'questions';
    protected $primaryKey = 'que_id';
    protected $allowedFields = ['qui_id', 'que_text', 'que_opt_a', 'que_opt_b', 'que_opt_c', 'que_opt_d', 'que_correct', 'que_score'];
}