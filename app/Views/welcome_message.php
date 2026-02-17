<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberSec Learning Platform</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #020617;
            color: #e2e8f0;
            overflow-x: hidden;
        }
        
        /* Typography */
        h1, h2, h3, .brand-font {
            font-family: 'Rajdhani', 'Kanit', sans-serif;
            font-weight: 700;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #1e293b;
        }
        .navbar-brand {
            color: #38bdf8 !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .nav-link {
            color: #94a3b8 !important;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #fff !important;
        }

        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            background: radial-gradient(circle at top right, #1e293b 0%, #020617 60%);
            position: relative;
        }
        .hero-title {
            font-size: 4rem;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }
        .hero-desc {
            font-size: 1.2rem;
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        .btn-cyber {
            background: linear-gradient(45deg, #0ea5e9, #2563eb);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-cyber:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
            color: white;
        }

        /* Features Cards */
        .feature-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            padding: 2rem;
            border-radius: 16px;
            transition: all 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            border-color: #38bdf8;
            transform: translateY(-5px);
            background: #162038;
        }
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        /* Floating Elements Animation */
        .floating-icon {
            position: absolute;
            color: rgba(56, 189, 248, 0.1);
            animation: float 6s ease-in-out infinite;
            z-index: 0;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Footer */
        footer {
            border-top: 1px solid #1e293b;
            background-color: #020617;
            padding: 50px 0 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock-fill"></i> CyberSec Learning
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link me-3" href="#features">ฟีเจอร์</a></li>
                    <li class="nav-item">
                        <a href="/login" class="btn btn-outline-light rounded-pill px-4 me-2">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-cyber">สมัครสมาชิก</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <!-- Decoration Icons -->
        <i class="bi bi-code-slash floating-icon" style="top: 20%; left: 10%; font-size: 3rem; animation-delay: 0s;"></i>
        <i class="bi bi-bug floating-icon" style="top: 15%; right: 15%; font-size: 4rem; animation-delay: 1s;"></i>
        <i class="bi bi-shield-check floating-icon" style="bottom: 10%; left: 20%; font-size: 5rem; animation-delay: 2s;"></i>

        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-opacity-10 bg-info text-info border border-info mb-3 px-3 py-2 rounded-pill">
                        <i class="bi bi-stars"></i> Platform การเรียนรู้แห่งอนาคต
                    </span>
                    <h1 class="hero-title mb-3">เรียนรู้ความปลอดภัยไซเบอร์<br>จากศูนย์สู่เซียน</h1>
                    <p class="hero-desc">
                        แพลตฟอร์ม E-Learning ครบวงจร พร้อมห้องปฏิบัติการจำลอง (Virtual Labs) 
                        ที่จะให้คุณได้ลองเจาะระบบจริงอย่างถูกกฎหมาย เข้าใจง่าย ปลอดภัย และใช้งานได้ฟรี
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/register" class="btn btn-cyber btn-lg">เริ่มเรียนเลย <i class="bi bi-arrow-right"></i></a>
                        <a href="#features" class="btn btn-outline-secondary btn-lg rounded-pill px-4">ดูเพิ่มเติม</a>
                    </div>
                    
                    <div class="mt-5 d-flex gap-4 text-secondary">
                        <div>
                            <h3 class="text-white mb-0">10+</h3>
                            <small>Virtual Labs</small>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <h3 class="text-white mb-0">7+</h3>
                            <small>บทเรียนหลัก</small>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <h3 class="text-white mb-0">Free</h3>
                            <small>ไม่มีค่าใช้จ่าย</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <img src="https://cdn-icons-png.flaticon.com/512/2092/2092663.png" alt="Cyber Security" class="img-fluid" style="max-width: 80%; filter: drop-shadow(0 0 30px rgba(56, 189, 248, 0.3)); animation: float 4s ease-in-out infinite;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 text-white fw-bold">ทำไมต้องเรียนกับเรา?</h2>
                <p class="text-secondary">หลักสูตรที่เน้นการลงมือทำจริง ไม่ใช่แค่ทฤษฎี</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-play-circle"></i>
                        </div>
                        <h4 class="text-white">บทเรียนวิดีโอ</h4>
                        <p class="text-secondary">เรียนรู้ผ่านวิดีโอที่เข้าใจง่าย พร้อมเอกสารประกอบการเรียนที่อัปเดตใหม่เสมอ ครอบคลุมตั้งแต่พื้นฐาน CIA Triad ไปจนถึงช่องโหว่ OWASP Top 10</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="bi bi-terminal"></i>
                        </div>
                        <h4 class="text-white">Virtual Labs</h4>
                        <p class="text-secondary">ฝึกเจาะระบบในสภาพแวดล้อมจำลองที่ปลอดภัย (Simulation) ทั้ง SQL Injection, XSS, Brute Force และอีกมากมายกว่า 10 ฐาน</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-award"></i>
                        </div>
                        <h4 class="text-white">วัดผลจริง</h4>
                        <p class="text-secondary">ระบบแบบทดสอบท้ายบทเรียน และการติดตามความคืบหน้า (Progress Tracking) เพื่อให้มั่นใจว่าคุณเข้าใจเนื้อหาอย่างแท้จริง</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lab Showcase -->
    <section class="py-5 bg-dark bg-opacity-50">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-2">
                    <h2 class="text-white fw-bold mb-4">สัมผัสประสบการณ์<br><span class="text-info">การเป็น Hacker</span></h2>
                    <p class="text-secondary mb-4">
                        คุณจะได้เรียนรู้วิธีคิดของแฮกเกอร์ เพื่อที่จะป้องกันระบบได้อย่างถูกวิธี ด้วยระบบ Lab จำลองสถานการณ์จริงที่เราพัฒนาขึ้นเอง
                    </p>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> SQL Injection Login Bypass</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Cross-Site Scripting (XSS)</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Command Injection & Web Shell</li>
                        <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Business Logic Flaws</li>
                    </ul>
                    <a href="/register" class="btn btn-outline-light rounded-pill mt-2">สมัครสมาชิกเพื่อเข้า Lab ฟรี</a>
                </div>
                <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                    <div class="card bg-black border-secondary p-2 shadow-lg" style="transform: rotate(-2deg);">
                        <div class="card-header border-secondary d-flex gap-2">
                            <div class="rounded-circle bg-danger" style="width:10px;height:10px;"></div>
                            <div class="rounded-circle bg-warning" style="width:10px;height:10px;"></div>
                            <div class="rounded-circle bg-success" style="width:10px;height:10px;"></div>
                        </div>
                        <div class="card-body font-monospace text-success">
                            > initializing hack_tool...<br>
                            > target found: 192.168.1.10<br>
                            > exploiting vulnerability...<br>
                            > access granted.<br>
                            <span class="text-white">root@system:~# _</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3 brand-font"><i class="bi bi-shield-lock"></i> CyberSec Learning</h5>
                    <p class="text-secondary small">
                        แพลตฟอร์มการเรียนรู้ด้านความปลอดภัยไซเบอร์สำหรับทุกคน มุ่งเน้นการสร้างความตระหนักรู้และทักษะที่นำไปใช้ได้จริง
                    </p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-white mb-3">เมนู</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">หน้าแรก</a></li>
                        <li class="mb-2"><a href="/login" class="text-decoration-none text-secondary">เข้าสู่ระบบ</a></li>
                        <li class="mb-2"><a href="/register" class="text-decoration-none text-secondary">สมัครสมาชิก</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">ติดต่อเรา</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Rajamangala University of Technology Lanna Chiangrai</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> ammarin_ta65@live.rmutl.ac.th</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">ติดตามข่าวสาร</h6>
                    <div class="d-flex gap-3">
                        <a href="https://www.facebook.com/natthanan.kitjaroen" class="text-secondary fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="https://www.youtube.com/@anti666-m3j" class="text-secondary fs-5"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-secondary fs-5"><i class="bi bi-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-top border-secondary pt-4 mt-4 text-center text-secondary small">
                &copy; 2024 Cyber Security Learning Platform. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>