<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Cyber Learning</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #020617;
            background-image: radial-gradient(circle at 50% 50%, #1e293b 0%, #020617 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-card {
            background-color: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid #334155;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .brand-logo {
            color: #38bdf8;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
        }

        .form-control {
            background-color: #1e293b;
            border: 1px solid #475569;
            color: #e2e8f0;
            padding: 0.75rem 1rem 0.75rem 2.5rem; /* เว้นที่ให้ไอคอน */
            border-radius: 8px;
        }
        .form-control:focus {
            background-color: #1e293b;
            border-color: #38bdf8;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }
        .input-group-text {
            background-color: transparent;
            border: none;
            color: #94a3b8;
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
        }
        
        .btn-cyber {
            background: linear-gradient(45deg, #0ea5e9, #2563eb);
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
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
            color: white;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }
        .shape-1 {
            width: 300px; height: 300px;
            background: rgba(56, 189, 248, 0.15);
            top: -100px; left: -100px;
        }
        .shape-2 {
            width: 250px; height: 250px;
            background: rgba(168, 85, 247, 0.15);
            bottom: -50px; right: -50px;
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
        <div class="login-card">
            
            <div class="brand-logo">
                <i class="bi bi-shield-lock-fill"></i> CyberSec
            </div>
            
            <h5 class="text-white text-center mb-4 fw-light">เข้าสู่ระบบเพื่อเริ่มเรียนรู้</h5>

            <!-- Alert Messages -->
            <?php if(session()->getFlashdata('msg')):?>
                <div class="alert alert-danger py-2 text-center border-0 bg-danger bg-opacity-10 text-danger small">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= session()->getFlashdata('msg') ?>
                </div>
            <?php endif;?>
            
            <?php if(session()->getFlashdata('success')):?>
                <div class="alert alert-success py-2 text-center border-0 bg-success bg-opacity-10 text-success small">
                    <i class="bi bi-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif;?>

            <form action="/login/auth" method="post">
                <div class="mb-3 position-relative">
                    <i class="bi bi-person input-group-text"></i>
                    <input type="text" name="login_id" class="form-control" placeholder="Username หรือ Email" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="bi bi-key input-group-text"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn btn-cyber">เข้าสู่ระบบ <i class="bi bi-box-arrow-in-right"></i></button>
            </form>

            <div class="auth-links">
                <p class="text-secondary mb-1">ยังไม่มีบัญชีใช่ไหม?</p>
                <a href="/register" class="fw-bold">สมัครสมาชิกใหม่</a>
                <div class="mt-3">
                    <a href="/" class="small"><i class="bi bi-arrow-left"></i> กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>