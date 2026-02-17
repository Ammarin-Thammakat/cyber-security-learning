<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการแจ้งปัญหา</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/dashboard">⬅️ กลับ Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">รายการแจ้งปัญหาจากผู้ใช้</h4>
            </div>
            <div class="card-body">
                
                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif;?>

                <table class="table table-bordered table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>วันที่</th>
                            <th>ผู้แจ้ง</th>
                            <th>หัวข้อ</th>
                            <th>รายละเอียด</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tickets as $t): ?>
                        <tr>
                            <td style="width: 15%"><?= $t['tic_created_at'] ?></td>
                            <td style="width: 15%">
                                <!-- ใช้ esc() กับข้อมูลผู้ใช้ด้วยเพื่อความชัวร์ -->
                                <strong><?= esc($t['use_username']) ?></strong><br>
                                <small class="text-muted"><?= esc($t['use_email']) ?></small>
                            </td>
                            
                            <!-- 🔴 จุดสำคัญ: ต้องใส่ esc() ตรงนี้ด้วยครับ -->
                            <td style="width: 20%"><?= esc($t['tic_subject']) ?></td>
                            <td><?= esc($t['tic_message']) ?></td>
                            
                            <td style="width: 10%" class="text-center">
                                <?php if($t['tic_status'] == 'pending'): ?>
                                    <span class="badge bg-secondary">รอดำเนินการ</span>
                                <?php else: ?>
                                    <span class="badge bg-success">เสร็จสิ้น</span>
                                <?php endif; ?>
                            </td>
                            <td style="width: 10%" class="text-center">
                                <?php if($t['tic_status'] == 'pending'): ?>
                                    <a href="/admin/ticket/resolve/<?= $t['tic_id'] ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('ยืนยันว่าปัญหานี้ได้รับการแก้ไขแล้ว?');">
                                       ✅ แก้ไขแล้ว
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($tickets)): ?>
                            <tr><td colspan="6" class="text-center">ไม่มีการแจ้งปัญหา</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>