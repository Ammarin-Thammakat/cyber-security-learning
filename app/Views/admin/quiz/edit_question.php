<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขคำถาม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">✏️ แก้ไขคำถาม</span>
            <!-- ปุ่มย้อนกลับไปหน้าจัดการ Quiz เดิม -->
            <a href="/admin/quiz/manage/<?= $quiz['qui_id'] ?>" class="btn btn-outline-light btn-sm">ยกเลิก</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">แก้ไขข้อมูลคำถาม</h5>
                    </div>
                    <div class="card-body">
                        <form action="/admin/question/update" method="post">
                            <!-- ส่ง ID ไปด้วยแบบ Hidden -->
                            <input type="hidden" name="que_id" value="<?= $question['que_id'] ?>">
                            <input type="hidden" name="quiz_id" value="<?= $question['qui_id'] ?>">
                            
                            <div class="mb-3">
                                <label>โจทย์คำถาม</label>
                                <textarea name="question" class="form-control" rows="3" required><?= esc($question['que_text']) ?></textarea>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>ตัวเลือก A</label>
                                    <input type="text" name="opt_a" class="form-control" value="<?= esc($question['que_opt_a']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label>ตัวเลือก B</label>
                                    <input type="text" name="opt_b" class="form-control" value="<?= esc($question['que_opt_b']) ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>ตัวเลือก C</label>
                                    <input type="text" name="opt_c" class="form-control" value="<?= esc($question['que_opt_c']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label>ตัวเลือก D</label>
                                    <input type="text" name="opt_d" class="form-control" value="<?= esc($question['que_opt_d']) ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>คำตอบที่ถูกต้อง (เฉลย)</label>
                                    <select name="correct" class="form-select" required>
                                        <option value="a" <?= $question['que_correct']=='a' ? 'selected' : '' ?>>A</option>
                                        <option value="b" <?= $question['que_correct']=='b' ? 'selected' : '' ?>>B</option>
                                        <option value="c" <?= $question['que_correct']=='c' ? 'selected' : '' ?>>C</option>
                                        <option value="d" <?= $question['que_correct']=='d' ? 'selected' : '' ?>>D</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>คะแนน</label>
                                    <input type="number" name="score" class="form-control" value="<?= $question['que_score'] ?>" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>