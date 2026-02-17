<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แจ้งปัญหาการใช้งาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/student/dashboard">Cyber Support</a>
            <a href="/student/dashboard" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- ฝั่งซ้าย: ฟอร์มส่งเรื่อง -->
            <div class="col-md-5">
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">📩 ส่งข้อความหาผู้ดูแลระบบ</h5>
                    </div>
                    <div class="card-body">
                        
                        <?php if(session()->getFlashdata('success')):?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif;?>

                        <form action="/support/create" method="post">
                            <div class="mb-3">
                                <label>หัวข้อปัญหา</label>
                                <input type="text" name="subject" class="form-control" placeholder="เช่น เข้าเรียนไม่ได้, วิดีโอไม่เล่น" required>
                            </div>
                            <div class="mb-3">
                                <label>รายละเอียด</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="อธิบายปัญหาที่พบ..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">ส่งเรื่อง</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: ประวัติการแจ้ง -->
            <div class="col-md-7">
                <h4 class="mb-3">ประวัติการแจ้งปัญหา</h4>
                <?php if(empty($tickets)): ?>
                    <div class="alert alert-secondary text-center">คุณยังไม่เคยแจ้งปัญหาใดๆ</div>
                <?php else: ?>
                    <?php foreach($tickets as $t): ?>
                        <div class="card mb-3 border-start border-4 <?= $t['tic_status']=='resolved' ? 'border-success' : 'border-secondary' ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <!-- ตรวจสอบจุดนี้: ต้องมี esc() -->
                                    <h5 class="card-title"><?= esc($t['tic_subject'], 'html') ?></h5>
                                    
                                    <?php if($t['tic_status'] == 'pending'): ?>
                                        <span class="badge bg-secondary">รอดำเนินการ</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">แก้ไขแล้ว</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text text-muted small"><?= $t['tic_created_at'] ?></p>
                                
                                <!-- ตรวจสอบจุดนี้: ต้องมี esc() -->
                                <p class="card-text"><?= esc($t['tic_message'], 'html') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>