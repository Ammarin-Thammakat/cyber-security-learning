<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บทเรียนทั้งหมด - Cyber Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- เรียกใช้ Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* สไตล์สำหรับการ์ดที่ถูกล็อก */
        .locked-card {
            opacity: 0.6; /* ทำให้ดูจางๆ */
            background-color: #e9ecef;
            pointer-events: none; /* ห้ามคลิก */
        }
        .locked-icon {
            font-size: 3rem;
            color: #6c757d;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        /* ปรับแต่งส่วนหัวให้ปุ่มอยู่ระดับเดียวกับชื่อ */
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">
    
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/student/dashboard"><i class="bi bi-shield-lock-fill"></i> CyberSec Learning</a>
            <a href="/student/dashboard" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container">
        
        <!-- ส่วนหัวข้อและปุ่มย้อนกลับ (เพิ่มใหม่ตรงนี้) -->
        <div class="page-header">
            <h2 class="mb-0"><i class="bi bi-book"></i> บทเรียนความปลอดภัยไซเบอร์</h2>
        </div>
        
        <!-- ส่วนแจ้งเตือน Error (กรณีคนแอบเข้าข้ามบท) -->
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger shadow-sm">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif;?>
        
        <div class="row">
            <?php foreach($lessons as $lesson): ?>
                
            <div class="col-md-4 mb-4">
                <!-- เช็คเงื่อนไข: ถ้าไม่ unlock ให้ใส่คลาส locked-card -->
                <div class="card h-100 shadow-sm position-relative <?= $lesson['is_unlocked'] ? '' : 'locked-card' ?>">
                    
                    <!-- ถ้าล็อกอยู่ ให้โชว์แม่กุญแจ -->
                    <?php if(!$lesson['is_unlocked']): ?>
                        <div class="locked-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title">
                            บทที่ <?= $lesson['les_order'] ?>: <?= $lesson['les_title'] ?>
                        </h5>
                        <p class="card-text text-muted"><?= $lesson['les_desc'] ?></p>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        <?php if($lesson['is_unlocked']): ?>
                            <a href="/course/learn/<?= $lesson['les_id'] ?>" class="btn btn-primary w-100">
                                เข้าเรียน <i class="bi bi-play-fill"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-lock"></i> ล็อก (ต้องสอบบทก่อนหน้า)
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>