<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผลการสอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .score-circle {
            width: 150px; height: 150px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem; font-weight: bold;
            border: 8px solid;
        }
        .pass { border-color: #198754; color: #198754; background: #d1e7dd; }
        .fail { border-color: #dc3545; color: #dc3545; background: #f8d7da; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <div class="card shadow mx-auto" style="max-width: 600px;">
            <div class="card-body p-5">
                
                <h4 class="text-muted mb-3"><?= $quiz['qui_title'] ?></h4>

                <!-- วงกลมคะแนน -->
                <div class="score-circle <?= $isPassed ? 'pass' : 'fail' ?>">
                    <?= $attempt['att_score'] ?>
                </div>
                
                <h5 class="mb-4">
                    คะแนนเต็ม: <?= $attempt['att_full_score'] ?>
                </h5>

                <!-- ข้อความแสดงผล -->
                <?php if($isPassed): ?>
                    <div class="alert alert-success">
                        <h4><i class="bi bi-check-circle-fill"></i> ยินดีด้วย! คุณสอบผ่าน</h4>
                        <p class="mb-0">คุณมีความเข้าใจในบทเรียนนี้เป็นอย่างดี</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <h4><i class="bi bi-x-circle-fill"></i> เสียใจด้วย คุณสอบไม่ผ่าน</h4>
                        <p class="mb-0">ลองทบทวนบทเรียนและทำแบบทดสอบใหม่อีกครั้งนะครับ</p>
                    </div>
                <?php endif; ?>
                
                <hr>

                <!-- ปุ่มควบคุมทิศทาง -->
                <div class="d-grid gap-2">
                    
                    <?php if($isPassed): ?>
                        <!-- กรณีสอบผ่าน -->
                        <?php if($nextLesson): ?>
                            <!-- มีบทเรียนถัดไป -->
                            <a href="/course/learn/<?= $nextLesson['les_id'] ?>" class="btn btn-primary btn-lg">
                                บทเรียนถัดไป: <?= $nextLesson['les_title'] ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <!-- บทเรียนสุดท้ายแล้ว -->
                            <a href="/course" class="btn btn-success btn-lg">
                                <i class="bi bi-trophy-fill"></i> จบหลักสูตรแล้ว! กลับหน้ารวม
                            </a>
                        <?php endif; ?>
                        
                        <a href="/student/dashboard" class="btn btn-outline-secondary">กลับหน้าหลัก</a>

                    <?php else: ?>
                        <!-- กรณีสอบตก -->
                        <a href="/quiz/start/<?= $currentLesson['les_id'] ?>" class="btn btn-warning btn-lg">
                            <i class="bi bi-arrow-repeat"></i> ทำแบบทดสอบอีกครั้ง
                        </a>
                        <div class="row">
                            <div class="col-6">
                                <a href="/course/learn/<?= $currentLesson['les_id'] ?>" class="btn btn-outline-info w-100">
                                    <i class="bi bi-book"></i> ทบทวนบทเรียน
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/student/dashboard" class="btn btn-outline-secondary w-100">กลับหน้าหลัก</a>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>