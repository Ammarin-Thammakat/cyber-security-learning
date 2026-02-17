<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .badge-notification {
            animation: pulse 2s infinite;
        }
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">👾 Admin Panel</a>
            <div class="d-flex">
                <a href="/student/dashboard" class="btn btn-outline-light btn-sm me-2">ไปหน้าบ้าน</a>
                <a href="/logout" class="btn btn-danger btn-sm">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar เมนูฝั่งซ้าย -->
            <div class="col-md-2">
                <div class="list-group">
                    <a href="/admin/dashboard" class="list-group-item list-group-item-action active">ภาพรวมระบบ</a>
                    <a href="/admin/users" class="list-group-item list-group-item-action">จัดการผู้ใช้งาน</a>
                    <a href="/admin/lessons" class="list-group-item list-group-item-action">จัดการบทเรียน</a>
                    <a href="/admin/quizzes" class="list-group-item list-group-item-action">จัดการข้อสอบ</a>
                    <a href="/admin/tickets" class="list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center">
                        <span>แจ้งปัญหา</span>
                        <?php if(isset($pending_tickets) && $pending_tickets > 0): ?>
                            <span class="badge bg-danger rounded-pill badge-notification"><?= $pending_tickets ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Content เนื้อหา -->
            <div class="col-md-10">
                <h3>📊 ภาพรวมระบบ</h3>
                
                <?php if(isset($pending_tickets) && $pending_tickets > 0): ?>
                <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>
                    <div>
                        <strong>มีรายการแจ้งปัญหาใหม่ <?= $pending_tickets ?> รายการ!</strong>
                        <a href="/admin/tickets" class="alert-link">คลิกเพื่อตรวจสอบ</a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- แถวที่ 1: ข้อมูลพื้นฐาน -->
                <h5 class="text-secondary mb-3"><i class="bi bi-database"></i> ข้อมูลพื้นฐาน</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card text-white bg-primary h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">ผู้เรียนทั้งหมด</h6>
                                        <h2 class="mb-0"><?= $count_users ?> <span class="fs-6">คน</span></h2>
                                    </div>
                                    <i class="bi bi-people fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card text-white bg-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">บทเรียน/ข้อสอบ</h6>
                                        <h2 class="mb-0"><?= $count_quizzes ?> <span class="fs-6">ชุด</span></h2>
                                    </div>
                                    <i class="bi bi-journal-text fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card text-white bg-info h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">จำนวนการสอบรวม</h6>
                                        <h2 class="mb-0"><?= $count_attempts ?> <span class="fs-6">ครั้ง</span></h2>
                                    </div>
                                    <i class="bi bi-pencil-fill fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- แถวที่ 2: วิเคราะห์ประสิทธิภาพ -->
                <h5 class="text-secondary mb-3"><i class="bi bi-graph-up-arrow"></i> วิเคราะห์ประสิทธิภาพการเรียนรู้</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card stat-card border-success h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-clipboard-data fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">คะแนนเฉลี่ยของผู้เรียน (Average Score)</h6>
                                        
                                        <!-- แก้ไขจุดแสดงผลตรงนี้: แสดงเป็นคะแนน / 25 -->
                                        <h3 class="mb-0 text-success"><?= $avg_score ?> <span class="fs-5 text-muted">/ 25</span></h3>
                                        
                                        <small class="text-muted">คะแนนเฉลี่ยจากคะแนนเต็ม 25 คะแนน</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card border-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="bi bi-check-circle fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">อัตราการสอบผ่าน (Success Rate)</h6>
                                        <h3 class="mb-0 text-primary"><?= $success_rate ?>%</h3>
                                        <small class="text-muted">อัตราส่วนผู้ที่ได้คะแนนเกินครึ่ง</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5><i class="bi bi-person-plus"></i> สมาชิกใหม่ล่าสุด</h5>
                    <div class="card shadow-sm border-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ชื่อผู้ใช้</th>
                                    <th>อีเมล</th>
                                    <th>วันที่สมัคร</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_users as $u): ?>
                                <tr>
                                    <td><?= esc($u['use_username']) ?></td>
                                    <td><?= esc($u['use_email']) ?></td>
                                    <td><?= $u['use_created_at'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>