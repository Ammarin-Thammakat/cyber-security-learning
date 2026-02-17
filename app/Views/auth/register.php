<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - Cyber Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #020617;
            background-image: radial-gradient(circle at 50% 50%, #1e293b 0%, #020617 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            background-color: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #334155;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
            position: relative;
            z-index: 10;
        }

        .brand-logo {
            color: #38bdf8;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1rem;
            text-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
        }

        .form-label {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .form-control {
            background-color: #1e293b;
            border: 1px solid #475569;
            color: #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .form-control:focus {
            background-color: #1e293b;
            border-color: #38bdf8;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }

        .btn-cyber {
            background: linear-gradient(45deg, #10b981, #059669);
            /* สีเขียวสำหรับการสมัคร */
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s;
        }

        .btn-cyber:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: rgba(16, 185, 129, 0.1);
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: rgba(56, 189, 248, 0.1);
            bottom: -50px;
            left: -50px;
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .auth-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .auth-links a:hover {
            color: #38bdf8;
        }

        .form-control::placeholder {
            color: #94a3b8;
            opacity: 0.5;
        }
    </style>
</head>

<body>

    <!-- พื้นหลังตกแต่ง -->
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="register-card">

            <div class="brand-logo">
                <i class="bi bi-person-plus-fill"></i> สมัครสมาชิก
            </div>
            <p class="text-secondary text-center mb-4">สร้างบัญชีเพื่อเข้าถึง Lab และบทเรียนฟรี</p>

            <!-- Alert Errors -->
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/register/save" method="post">
                <div class="mb-3">
                    <label class="form-label">ชื่อ-นามสกุล</label>
                    <input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" placeholder="เช่น สมชาย ใจดี" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= set_value('username') ?>" placeholder="ภาษาอังกฤษเท่านั้น" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" placeholder="email@example.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" placeholder="อย่างน้อย 6 ตัวอักษร" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="confpassword" class="form-control" placeholder="กรอกรหัสผ่านอีกครั้ง" required>
                </div>

                <button type="submit" class="btn btn-cyber">ยืนยันการสมัคร <i class="bi bi-arrow-right"></i></button>
            </form>

            <div class="auth-links">
                <p class="text-secondary mb-1">มีบัญชีอยู่แล้ว?</p>
                <a href="/login" class="text-primary fw-bold">เข้าสู่ระบบ</a>
                <div class="mt-3">
                    <a href="/" class="small"><i class="bi bi-arrow-left"></i> กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>