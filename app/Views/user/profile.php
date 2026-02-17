<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">✏️ แก้ไขข้อมูลส่วนตัว</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(session()->getFlashdata('success')):?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif;?>

                        <form action="/student/profile/update" method="post" enctype="multipart/form-data">
                            
                            <div class="text-center mb-4">
                                <?php $avatar = $user['use_avatar'] ? '/uploads/'.$user['use_avatar'] : 'https://via.placeholder.com/150'; ?>
                                <img src="<?= $avatar ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="mt-2">
                                    <label class="form-label">เปลี่ยนรูปโปรไฟล์</label>
                                    <input type="file" name="avatar" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>ชื่อ-นามสกุล</label>
                                <input type="text" name="name" class="form-control" value="<?= $user['use_name'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $user['use_email'] ?>" required>
                            </div>
                            
                            <hr>
                            <div class="mb-3">
                                <label class="text-danger">เปลี่ยนรหัสผ่าน (ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</label>
                                <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่านใหม่...">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/student/dashboard" class="btn btn-secondary me-md-2">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>