<?php namespace App\Models;
use CodeIgniter\Model;

class QuizModel extends Model {
    protected $table = 'quizzes';
    protected $primaryKey = 'qui_id';
    protected $allowedFields = ['les_id', 'qui_title'];
}