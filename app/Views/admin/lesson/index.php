<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการบทเรียน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/dashboard">⬅️ กลับ Dashboard</a>
            <a href="/admin/lesson/form" class="btn btn-success">➕ เพิ่มบทเรียนใหม่</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📚 รายการบทเรียนทั้งหมด</h5>
            </div>
            <div class="card-body">
                
                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif;?>

                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%">ลำดับ</th>
                            <th>ชื่อบทเรียน</th>
                            <th>คำอธิบายสั้นๆ</th>
                            <th style="width: 10%">สถานะ</th>
                            <th style="width: 20%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lessons as $l): ?>
                        <tr>
                            <td class="text-center"><?= $l['les_order'] ?></td>
                            <td><?= $l['les_title'] ?></td>
                            <td><?= $l['les_desc'] ?></td>
                            <td class="text-center">
                                <?php if($l['les_status'] == 'active'): ?>
                                    <span class="badge bg-success">ใช้งาน</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">ซ่อน</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/admin/lesson/form/<?= $l['les_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <a href="/admin/lesson/delete/<?= $l['les_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('ยืนยันที่จะลบบทเรียนนี้?');">ลบ</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>