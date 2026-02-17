<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $lesson['les_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- เพิ่ม Bootstrap Icons เพื่อให้โชว์รูปไอคอนไฟล์ -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* CSS ปรับแต่งเพิ่มเติมสำหรับกล่องดาวน์โหลด */
        .download-box {
            background-color: #f8f9fa;
            border-left: 5px solid #0dcaf0; /* สีฟ้า */
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .download-box:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-0">
        <div class="container">
            <span class="navbar-brand mb-0 h1">กำลังเรียน: <?= $lesson['les_title'] ?></span>
            <a href="/course" class="btn btn-outline-secondary btn-sm">กลับหน้ารวม</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- ฝั่งซ้าย: วิดีโอ -->
            <div class="col-md-8 p-0 bg-black text-center" style="height: 80vh;">
                <?php if(!empty($lesson['les_video_url'])): ?>
                    <iframe width="100%" height="100%" src="<?= $lesson['les_video_url'] ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-white">
                        <h3>บทเรียนนี้ไม่มีวิดีโอ</h3>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ฝั่งขวา: เนื้อหาและไฟล์ดาวน์โหลด -->
            <div class="col-md-4 p-4" style="height: 80vh; overflow-y: auto;">
                <h4>เนื้อหาบทเรียน</h4>
                <hr>
                
                <!-- === ส่วนแสดงไฟล์ดาวน์โหลด (เพิ่มใหม่ตรงนี้) === -->
                <?php if(!empty($lesson['les_file'])): ?>
                    <div class="download-box d-flex align-items-center">
                        <i class="bi bi-file-earmark-arrow-down-fill text-info fs-1 me-3"></i>
                        <div>
                            <h6 class="mb-1 text-dark">เอกสารประกอบการเรียน</h6>
                            <a href="<?= base_url('uploads/lessons/'.$lesson['les_file']) ?>" class="btn btn-sm btn-info text-white" target="_blank">
                                <i class="bi bi-download"></i> ดาวน์โหลดไฟล์
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- ============================================== -->

                <div class="content mt-3">
                    <?= $lesson['les_content'] ?>
                </div>
                
                <div class="mt-5 d-grid">
                    <a href="/quiz/start/<?= $lesson['les_id'] ?>" class="btn btn-success btn-lg">ทำแบบทดสอบบทนี้</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>