<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cyber Learning</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #0f172a; /* พื้นหลังสีน้ำเงินเข้ม */
            color: #e2e8f0;
        }

        /* Navbar & Profile Styles */
        .navbar {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .navbar-brand {
            font-weight: 600; letter-spacing: 1px; color: #38bdf8 !important; font-size: 1.5rem;
        }

        /* CSS สำหรับรูปโปรไฟล์คลิกได้ */
        .profile-container {
            position: relative;
            width: 55px;
            height: 55px;
            cursor: pointer;
        }
        .profile-img-custom {
            width: 100%; height: 100%;
            object-fit: cover;
            border: 2px solid #38bdf8;
            padding: 2px;
            transition: all 0.3s;
        }
        /* Overlay กล้องถ่ายรูป (ซ่อนไว้ก่อน) */
        .profile-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0; /* ซ่อน */
            transition: opacity 0.3s;
            color: #fff;
            font-size: 1.2rem;
        }
        /* เมื่อเอาเมาส์ชี้ ให้โชว์ Overlay */
        .profile-container:hover .profile-overlay {
            opacity: 1;
        }
        
        .user-name-text { font-size: 1.1rem; font-weight: 600; }

        /* Content Styles */
        .hero-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 15px; padding: 2rem; margin-bottom: 2rem; border: 1px solid #334155;
            position: relative; overflow: hidden;
        }
        .menu-card {
            background-color: #1e293b; border: 1px solid #334155; border-radius: 12px; transition: all 0.3s ease; height: 100%;
        }
        .menu-card:hover { transform: translateY(-5px); border-color: #38bdf8; }
        .card-icon { font-size: 3rem; margin-bottom: 1rem; }
        .btn-cyber { background-color: #38bdf8; color: #0f172a; font-weight: 600; border: none; }
        .btn-cyber:hover { background-color: #0ea5e9; color: #fff; }
        .icon-course { color: #38bdf8; }
        .icon-quiz { color: #facc15; }
        .icon-lab { color: #4ade80; }
        .icon-profile { color: #f472b6; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-lock-fill"></i> CyberSec Learning</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-3">
                    
                    <!-- ส่วนรูปโปรไฟล์และชื่อ -->
                    <div class="d-flex align-items-center">
                        <?php 
                            // ใช้ข้อมูลจาก $user_info ที่ส่งมาจาก Controller เพื่อความสดใหม่
                            $avatarName = $user_info['use_avatar']; 
                            
                            // เช็คไฟล์รูปว่ามีจริงไหม ถ้าไม่มีใช้รูป Default
                            $avatarUrl = (!empty($avatarName) && file_exists(FCPATH . 'uploads/' . $avatarName)) 
                                ? base_url('uploads/' . $avatarName) 
                                : "https://ui-avatars.com/api/?name=".urlencode($user_info['use_name'])."&background=random&color=fff&size=128";
                        ?>
                        
                        <!-- ฟอร์มอัปโหลดรูป (ซ่อนไว้) -->
                        <form id="quickAvatarForm" action="/student/avatar/update" method="post" enctype="multipart/form-data" style="display: none;">
                            <input type="file" name="avatar_quick" id="quickAvatarInput" accept="image/*" onchange="document.getElementById('quickAvatarForm').submit();">
                        </form>

                        <!-- รูปภาพที่คลิกได้ (เมื่อคลิกจะไปเรียก input file ให้ทำงาน) -->
                        <div class="profile-container me-3 shadow-sm" onclick="document.getElementById('quickAvatarInput').click();" title="คลิกเพื่อเปลี่ยนรูป">
                            <img src="<?= $avatarUrl ?>" alt="Profile" class="rounded-circle profile-img-custom">
                            <!-- Overlay ไอคอนกล้อง -->
                            <div class="profile-overlay rounded-circle">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                        </div>
                        
                        <span class="text-white user-name-text">
                            <?= $user_info['use_name'] ?>
                        </span>
                    </div>

                    <div class="vr text-secondary mx-2" style="height: 30px;"></div>

                    <div class="d-flex gap-2">
                        <a href="/support" class="btn btn-outline-warning btn-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="แจ้งปัญหา">
                            <i class="bi bi-exclamation-circle fs-6"></i>
                        </a>
                        <a href="/logout" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="ออกจากระบบ">
                            <i class="bi bi-power fs-6"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        
        <!-- แจ้งเตือนสถานะ -->
        <?php if(session()->getFlashdata('success')):?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

        <!-- Hero Banner -->
        <div class="hero-banner shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white fw-bold">ยินดีต้อนรับ, <?= $user_info['use_name'] ?> 👋</h2>
                    <p class="text-secondary mb-0">พร้อมที่จะยกระดับทักษะด้านความปลอดภัยไซเบอร์ของคุณหรือยัง?</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-cpu text-secondary opacity-25" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="card mb-4 border-0 shadow-sm" style="background: #1e293b;">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div style="flex: 1;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white fw-bold"><i class="bi bi-trophy-fill text-warning"></i> ความคืบหน้า</span>
                        <span class="text-info fw-bold"><?= $progress_percent ?>%</span>
                    </div>
                    <div class="progress" style="height: 10px; background-color: #334155; border-radius: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: <?= $progress_percent ?>%; border-radius: 20px;"></div>
                    </div>
                    <small class="text-secondary mt-2 d-block">ผ่านแล้ว <?= $progress_text ?> บทเรียน</small>
                </div>
                <div class="ms-4 d-none d-sm-block">
                    <?php if($progress_percent >= 100): ?>
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <?php else: ?>
                        <i class="bi bi-graph-up-arrow text-info" style="font-size: 3rem;"></i>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="menu-card text-center p-4">
                    <div class="card-icon icon-course"><i class="bi bi-journal-code"></i></div>
                    <h5 class="text-white">บทเรียน</h5>
                    <p class="text-secondary small">เรียนรู้ทฤษฎีและวิดีโอ</p>
                    <a href="/course" class="btn btn-cyber w-100 rounded-pill">เข้าสู่บทเรียน</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="menu-card text-center p-4">
                    <div class="card-icon icon-quiz"><i class="bi bi-pencil-square"></i></div>
                    <h5 class="text-white">แบบทดสอบ</h5>
                    <p class="text-secondary small">วัดระดับความรู้</p>
                    <a href="/quiz" class="btn btn-outline-light w-100 rounded-pill">เริ่มทำข้อสอบ</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="menu-card text-center p-4 position-relative" style="border-color: #4ade80;">
                    <span class="position-absolute top-0 end-0 badge bg-success m-2">Hot</span>
                    <div class="card-icon icon-lab"><i class="bi bi-laptop"></i></div>
                    <h5 class="text-white">ห้อง Lab จำลอง</h5>
                    <p class="text-secondary small">ฝึกปฏิบัติจริง 10 ฐาน</p>
                    <a href="/lab" class="btn btn-success w-100 rounded-pill">เข้าห้อง Lab</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="menu-card text-center p-4">
                    <div class="card-icon icon-profile"><i class="bi bi-person-badge"></i></div>
                    <h5 class="text-white">โปรไฟล์ของฉัน</h5>
                    <p class="text-secondary small">แก้ไขข้อมูลส่วนตัว</p>
                    <div class="d-grid gap-2">
                        <a href="/student/profile" class="btn btn-outline-secondary btn-sm rounded-pill">แก้ไขโปรไฟล์</a>
                        <a href="/student/history" class="btn btn-outline-info btn-sm rounded-pill">ดูประวัติสอบ</a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-5 text-center text-secondary small pb-4">
            &copy; <?= date('Y') ?> Cyber Security Learning Platform. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>