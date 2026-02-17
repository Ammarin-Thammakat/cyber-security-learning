<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการผู้ใช้งาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/dashboard">⬅️ กลับ Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">รายชื่อผู้ใช้งานทั้งหมด</h4>
                <span class="badge bg-light text-dark"><?= count($users) ?> คน</span>
            </div>
            <div class="card-body">
                
                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif;?>

                <!-- --- ส่วนฟอร์มค้นหา (เพิ่มใหม่) --- -->
                <form action="/admin/users" method="get" class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="ค้นหาด้วยชื่อ, Username หรือ Email..." 
                               value="<?= isset($search_keyword) ? esc($search_keyword) : '' ?>">
                        <button class="btn btn-primary" type="submit">ค้นหา</button>
                        
                        <?php if(isset($search_keyword) && $search_keyword): ?>
                            <a href="/admin/users" class="btn btn-outline-secondary">ล้างค่า</a>
                        <?php endif; ?>
                    </div>
                </form>
                <!-- --------------------------------- -->

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>รูปโปรไฟล์</th>
                                <th>Username</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        ไม่พบข้อมูลผู้ใช้งานที่ค้นหา
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($users as $u): ?>
                                <tr>
                                    <td><?= $u['use_id'] ?></td>
                                    <td>
                                        <?php 
                                            // แสดงรูปโปรไฟล์
                                            $avatarUrl = (!empty($u['use_avatar']) && file_exists(FCPATH . 'uploads/' . $u['use_avatar'])) 
                                                ? base_url('uploads/' . $u['use_avatar']) 
                                                : "https://ui-avatars.com/api/?name=".urlencode($u['use_name'])."&background=random&color=fff&size=64";
                                        ?>
                                        <img src="<?= $avatarUrl ?>" class="rounded-circle" width="40" height="40" alt="avatar" style="object-fit: cover;">
                                    </td>
                                    <td><?= esc($u['use_username']) ?></td>
                                    <td><?= esc($u['use_name']) ?></td>
                                    <td><?= esc($u['use_email']) ?></td>
                                    <td>
                                        <?php if($u['use_role'] == 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($u['use_role'] != 'admin'): ?>
                                            <a href="/admin/user/delete/<?= $u['use_id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('ยืนยันที่จะลบผู้ใช้นี้? ข้อมูลการสอบจะหายไปทั้งหมด');">
                                               <i class="bi bi-trash"></i> ลบ
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>