<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?= isset($lesson) ? 'แก้ไขบทเรียน' : 'เพิ่มบทเรียนใหม่' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><?= isset($lesson) ? '✏️ แก้ไขบทเรียน' : '➕ เพิ่มบทเรียนใหม่' ?></h4>
                    </div>
                    <div class="card-body">

                        <form action="/admin/lesson/save" method="post" enctype="multipart/form-data">
                            <?php if (isset($lesson)): ?>
                                <input type="hidden" name="les_id" value="<?= $lesson['les_id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label>ชื่อบทเรียน</label>
                                <input type="text" name="title" class="form-control" required
                                    value="<?= isset($lesson) ? $lesson['les_title'] : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label>คำอธิบายสั้นๆ (แสดงหน้าสารบัญ)</label>
                                <input type="text" name="desc" class="form-control" required
                                    value="<?= isset($lesson) ? $lesson['les_desc'] : '' ?>">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>ลิงก์วิดีโอ (YouTube Embed URL)</label>
                                    <input type="text" name="video_url" class="form-control" placeholder="เช่น https://www.youtube.com/embed/..."
                                        value="<?= isset($lesson) ? $lesson['les_video_url'] : '' ?>">
                                    <small class="text-muted">ต้องใช้ลิงก์แบบ Embed เท่านั้น</small>
                                </div>
                                    <!-- ส่วนอัปโหลดไฟล์ (เพิ่มใหม่) -->
                                    <div class="col-md-6">
                                        <label>เอกสารประกอบการเรียน (PDF, PPT)</label>
                                        <input type="file" name="lesson_file" class="form-control" accept=".pdf,.ppt,.pptx,.doc,.docx">

                                        <?php if (isset($lesson) && !empty($lesson['les_file'])): ?>
                                            <div class="mt-2 small">
                                                <span class="text-success"><i class="bi bi-file-earmark-check"></i> มีไฟล์เดิมอยู่แล้ว:</span>
                                                <a href="<?= base_url('uploads/lessons/' . $lesson['les_file']) ?>" target="_blank">เปิดดู</a>
                                                <span class="text-muted">(อัปโหลดใหม่เพื่อทับไฟล์เดิม)</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>ลำดับบทเรียน</label>
                                    <input type="number" name="order" class="form-control" required
                                        value="<?= isset($lesson) ? $lesson['les_order'] : '1' ?>">
                                </div>
                                <div class="col-md-3">
                                    <label>สถานะ</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= (isset($lesson) && $lesson['les_status'] == 'active') ? 'selected' : '' ?>>ใช้งาน</option>
                                        <option value="inactive" <?= (isset($lesson) && $lesson['les_status'] == 'inactive') ? 'selected' : '' ?>>ซ่อน</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>เนื้อหาบทเรียน (HTML)</label>
                                <textarea name="content" class="form-control" rows="10" placeholder="ใส่เนื้อหา หรือ Code HTML ที่นี่..."><?= isset($lesson) ? $lesson['les_content'] : '' ?></textarea>
                                <small class="text-muted">สามารถใส่ tag &lt;p&gt;, &lt;b&gt;, &lt;img&gt; ได้</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="/admin/lessons" class="btn btn-secondary">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>