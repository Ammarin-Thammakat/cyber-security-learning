<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อสอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/dashboard">⬅️ กลับ Dashboard</a>
        </div>
    </nav>

    <div class="container">
        
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">➕ สร้างชุดข้อสอบใหม่</h5>
            </div>
            <div class="card-body">
                <form action="/admin/quiz/create" method="post" class="row g-3">
                    <div class="col-md-6">
                        <label>เลือกบทเรียน</label>
                        <select name="les_id" class="form-select" required>
                            <option value="">-- เลือกบทเรียน --</option>
                            <?php foreach($lessons as $l): ?>
                                <option value="<?= $l['les_id'] ?>"><?= $l['les_title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>ชื่อชุดข้อสอบ</label>
                        <input type="text" name="qui_title" class="form-control" placeholder="เช่น แบบทดสอบท้ายบทที่..." required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">สร้าง</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">รายการข้อสอบทั้งหมด</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>บทเรียน</th>
                            <th>ชื่อชุดข้อสอบ</th>
                            <th>จัดการคำถาม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($quizzes as $q): ?>
                        <tr>
                            <td><?= $q['qui_id'] ?></td>
                            <td><?= $q['les_title'] ?></td>
                            <td><?= $q['qui_title'] ?></td>
                            <td>
                                <a href="/admin/quiz/manage/<?= $q['qui_id'] ?>" class="btn btn-warning btn-sm">
                                    📝 จัดการคำถาม
                                </a>
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