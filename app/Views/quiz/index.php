<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>คลังข้อสอบ - Cyber Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* สไตล์สำหรับการ์ดที่ถูกล็อก */
        .locked-quiz {
            filter: grayscale(100%); /* ปรับเป็นขาวดำ */
            opacity: 0.7;
            pointer-events: none; /* ห้ามกด */
            background-color: #e9ecef;
        }
        .lock-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4rem;
            color: #6c757d;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/student/dashboard"><i class="bi bi-shield-check"></i> CyberSec Testing</a>
            <a href="/student/dashboard" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">📝 แบบทดสอบทั้งหมด</h3>
        
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger shadow-sm">
                <i class="bi bi-exclamation-octagon-fill"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif;?>
        
        <div class="row">
            <?php foreach($quizzes as $q): ?>
            <div class="col-md-6 mb-4">
                
                <div class="card shadow-sm border-start border-4 border-primary h-100 position-relative <?= $q['is_unlocked'] ? '' : 'locked-quiz' ?>">
                    
                    <?php if(!$q['is_unlocked']): ?>
                        <div class="lock-overlay">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><?= $q['qui_title'] ?></h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="bi bi-journal-bookmark"></i> บทเรียน: <?= $q['les_title'] ?>
                                </p>
                            </div>
                            <span class="badge bg-secondary">บทที่ <?= $q['les_order'] ?></span>
                        </div>
                        
                        <hr>

                        <?php if($q['is_unlocked']): ?>
                            <a href="/quiz/start/<?= $q['les_id'] ?>" class="btn btn-primary w-100">
                                <i class="bi bi-play-circle"></i> เริ่มทำข้อสอบ
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-lock"></i> ล็อก (ต้องผ่านบทก่อนหน้า)
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if(empty($quizzes)): ?>
                <div class="col-12 text-center mt-5">
                    <p class="text-muted">ยังไม่มีแบบทดสอบในขณะนี้</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>