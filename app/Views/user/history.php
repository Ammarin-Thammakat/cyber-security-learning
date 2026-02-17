<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติการสอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/student/dashboard">CyberSec Learning</a>
            <a href="/student/dashboard" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container">
        <h3>📜 ประวัติการทำแบบทดสอบ</h3>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>วันที่สอบ</th>
                            <th>วิชา</th>
                            <th>คะแนนที่ได้</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($attempts as $row): ?>
                        <tr>
                            <td><?= $row['att_date'] ?></td>
                            <td><?= $row['qui_title'] ?></td>
                            <td>
                                <span class="badge bg-info text-dark fs-6">
                                    <?= $row['att_score'] ?> / <?= $row['att_full_score'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['att_score'] >= ($row['att_full_score']/2)): ?>
                                    <span class="text-success">ผ่านเกณฑ์</span>
                                <?php else: ?>
                                    <span class="text-danger">ควรปรับปรุง</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($attempts)): ?>
                            <tr><td colspan="4" class="text-center">ยังไม่มีประวัติการสอบ</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>