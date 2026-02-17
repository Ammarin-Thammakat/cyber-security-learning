<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// --- Auth System (ระบบล็อกอิน/สมัครสมาชิก) ---
$routes->get('/register', 'AuthController::register');
$routes->post('/register/save', 'AuthController::saveRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login/auth', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

// --- User Routes (Student) ---
$routes->get('/student/dashboard', 'UserController::index');
$routes->get('/student/history', 'UserController::history');
$routes->get('/student/profile', 'UserController::profile');
$routes->post('/student/profile/update', 'UserController::updateProfile');
$routes->post('/student/avatar/update', 'UserController::updateAvatar');

// --- Learning System (ระบบการเรียน) ---
$routes->get('/course', 'CourseController::index');
$routes->get('/course/learn/(:num)', 'CourseController::learn/$1');

// --- Quiz System (ระบบแบบทดสอบ) ---
$routes->get('/quiz', 'QuizController::index');
$routes->get('/quiz/start/(:num)', 'QuizController::start/$1');
$routes->post('/quiz/submit', 'QuizController::submit');
$routes->get('/quiz/result/(:num)', 'QuizController::result/$1');

// --- Lab Simulations (ห้องปฏิบัติการจำลอง) ---
$routes->get('/lab', 'LabController::index');
$routes->get('/lab/play/(:segment)', 'LabController::play/$1');

// --- Support System (ระบบแจ้งปัญหา) ---
$routes->get('/support', 'SupportController::index');
$routes->post('/support/create', 'SupportController::create');

// --- Admin Group (สำหรับผู้ดูแลระบบ) ---
$routes->group('admin', function($routes) {
    // Dashboard & Users
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('users', 'AdminController::users');
    $routes->get('user/delete/(:num)', 'AdminController::deleteUser/$1');
    
    // Lessons Management (จัดการบทเรียน)
    $routes->get('lessons', 'AdminLessonController::index');
    $routes->get('lesson/form', 'AdminLessonController::form');
    $routes->get('lesson/form/(:num)', 'AdminLessonController::form/$1');
    $routes->post('lesson/save', 'AdminLessonController::save');
    $routes->get('lesson/delete/(:num)', 'AdminLessonController::delete/$1');

    // Quiz Management (จัดการข้อสอบ)
    $routes->get('quizzes', 'AdminQuizController::index');
    $routes->post('quiz/create', 'AdminQuizController::createQuiz');
    $routes->get('quiz/manage/(:num)', 'AdminQuizController::manage/$1');
    
    // Question Management (จัดการคำถาม)
    $routes->post('question/add', 'AdminQuizController::addQuestion');
    $routes->get('question/delete/(:num)/(:num)', 'AdminQuizController::deleteQuestion/$1/$2');
    $routes->get('question/edit/(:num)', 'AdminQuizController::editQuestion/$1');
    $routes->post('question/update', 'AdminQuizController::updateQuestion');

    // Ticket Management (จัดการเรื่องร้องเรียน)
    $routes->get('tickets', 'AdminController::tickets');
    $routes->get('ticket/resolve/(:num)', 'AdminController::resolveTicket/$1');
});