<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonModel extends Model
{
    protected $table = 'lessons';
    protected $primaryKey = 'les_id';
    protected $allowedFields = ['les_title', 'les_desc', 'les_content', 'les_video_url', 'les_order', 'les_status','les_file'];
}