<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการคำถาม - <?= $quiz['qui_title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">ชุดข้อสอบ: <?= $quiz['qui_title'] ?></span>
            <a href="/admin/quizzes" class="btn btn-outline-light btn-sm">ย้อนกลับ</a>
        </div>
    </nav>

    <div class="container">
        
        <div class="card shadow mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">เพิ่มคำถามใหม่</h5>
            </div>
            <div class="card-body">
                <form action="/admin/question/add" method="post">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['qui_id'] ?>">
                    
                    <div class="mb-3">
                        <label>โจทย์คำถาม</label>
                        <textarea name="question" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>ตัวเลือก A</label>
                            <input type="text" name="opt_a" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>ตัวเลือก B</label>
                            <input type="text" name="opt_b" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>ตัวเลือก C</label>
                            <input type="text" name="opt_c" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>ตัวเลือก D</label>
                            <input type="text" name="opt_d" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>คำตอบที่ถูกต้อง</label>
                            <select name="correct" class="form-select" required>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>คะแนน</label>
                            <input type="number" name="score" class="form-control" value="1" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">บันทึกคำถาม</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

         <h4>รายการคำถามในชุดนี้ (<?= count($questions) ?> ข้อ)</h4>
        <?php foreach($questions as $index => $q): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">ข้อที่ <?= $index+1 ?>: <?= esc($q['que_text']) ?></h5>
                        
                        <div>
                            <!-- ปุ่มแก้ไข (เพิ่มใหม่) -->
                            <a href="/admin/question/edit/<?= $q['que_id'] ?>" class="btn btn-warning btn-sm me-1">
                                ✏️ แก้ไข
                            </a>
                            
                            <!-- ปุ่มลบ (ของเดิม) -->
                            <a href="/admin/question/delete/<?= $q['que_id'] ?>/<?= $quiz['qui_id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('ลบคำถามข้อนี้?');">ลบ</a>
                        </div>
                    </div>
                    
                    <p class="card-text text-muted mb-1 mt-2">
                        A: <?= esc($q['que_opt_a']) ?> | B: <?= esc($q['que_opt_b']) ?> | 
                        C: <?= esc($q['que_opt_c']) ?> | D: <?= esc($q['que_opt_d']) ?>
                    </p>
                    <span class="badge bg-success">เฉลย: <?= strtoupper($q['que_correct']) ?></span>
                    <span class="badge bg-secondary">คะแนน: <?= $q['que_score'] ?></span>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</body>
</html>