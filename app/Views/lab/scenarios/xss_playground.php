<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>XSS Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Courier Prime', monospace;
        }

        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
        }

        .card-header {
            background-color: #0f172a;
            border-bottom: 1px solid #334155;
            color: #f472b6;
            font-weight: bold;
        }

        .comment-box {
            background-color: #fff;
            color: #333;
            min-height: 200px;
            border-radius: 5px;
            padding: 15px;
            font-family: sans-serif;
            /* เว็บทั่วไปมักใช้ font นี้ */
        }

        .user-comment {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .user-comment strong {
            color: #2563eb;
        }

        /* Hacker Input Style */
        .hacker-input {
            background-color: #0f172a;
            color: #f472b6;
            /* สีชมพู Neon */
            border: 1px solid #334155;
            font-family: 'Courier Prime', monospace;
        }

        .hacker-input:focus {
            background-color: #0f172a;
            color: #f472b6;
            box-shadow: 0 0 10px rgba(244, 114, 182, 0.5);
            border-color: #f472b6;
        }

        .hacker-input::placeholder {
            color: #475569;
        }
        .text-muted {
            color: #94a3b8 !important;
        }

        /* Hint & Prevention Styles (เหมือน Lab ก่อนหน้า) */
        .btn-hint {
            color: #facc15;
            border-color: #facc15;
            font-size: 0.8rem;
        }

        .btn-hint:hover {
            background-color: #facc15;
            color: #000;
        }

        .hint-box {
            display: none;
            background-color: rgba(250, 204, 21, 0.1);
            border: 1px dashed #facc15;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            color: #facc15;
            font-size: 0.9rem;
            animation: fadeIn 0.5s;
        }

        .hint-box code {
            color: #fff;
            background-color: #000;
            padding: 2px 5px;
            border-radius: 3px;
        }

        #preventionSection {
            display: none;
            animation: slideUp 0.8s ease-out;
        }

        .code-block {
            background-color: #1a1a1a;
            border-left: 4px solid;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.9rem;
            border-radius: 0 5px 5px 0;
        }

        .code-bad {
            border-color: #ef4444;
            color: #fca5a5;
        }

        .code-good {
            border-color: #22c55e;
            color: #86efac;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="color: #f472b6;">👾 Lab XSS: The Cookie Stealer</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>🌐 Guestbook (สมุดเยี่ยมชม)</span>
                        <span class="badge bg-danger">Vulnerable</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">ความคิดเห็นล่าสุด:</p>
                        <div id="commentDisplay" class="comment-box shadow-inner mb-3 overflow-auto">
                            <div class="user-comment">
                                <strong>Admin:</strong><br> ยินดีต้อนรับสู่เว็บไซต์ของเรา! ฝากข้อความไว้ได้เลย
                            </div>
                            <div class="user-comment">
                                <strong>User123:</strong><br> เว็บสวยมากครับ ชอบๆ
                            </div>
                        </div>

                        <form id="xssForm" onsubmit="return false;">
                            <div class="input-group">
                                <input type="text" id="payloadInput" class="form-control hacker-input" placeholder="พิมพ์ข้อความที่นี่..." autocomplete="off">
                                <button class="btn btn-primary" onclick="postComment()">โพสต์</button>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">ลองพิมพ์ HTML เช่น <code>&lt;b&gt;Text&lt;/b&gt;</code> ดูสิ</small>
                            <button type="button" class="btn btn-sm btn-hint" onclick="toggleHint()">
                                <i class="bi bi-lightbulb"></i> ดูคำใบ้
                            </button>
                        </div>

                        <div id="hint-content" class="hint-box">
                            <strong>💡 คำใบ้:</strong> เราต้องการสั่งให้ Browser รันโค้ด JavaScript ลองใช้ Tag นี้ดู:<br>
                            <div class="text-center mt-2"><code>&lt;script&gt;alert('Hacked')&lt;/script&gt;</code></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div id="statusBox" class="card mb-3">
                    <div class="card-body">
                        <h5 class="text-info"><i class="bi bi-search"></i> วิเคราะห์ผลลัพธ์</h5>
                        <div id="analysisResult" class="text-muted">
                            ยังไม่มีการกระทำ... รอคุณโพสต์ข้อความ
                        </div>
                    </div>
                </div>

                <div id="successBox" class="alert alert-success border-success shadow-sm" style="display:none;">
                    <h4 class="alert-heading"><i class="bi bi-bug-fill"></i> HACKED SUCCESS!</h4>
                    <p>ยินดีด้วย! คุณสามารถฝัง Script ลงบนหน้าเว็บได้สำเร็จ</p>
                    <hr>
                    <p class="mb-0 small">ในสถานการณ์จริง Hacker อาจเปลี่ยนจาก <code>alert()</code> เป็นโค้ดส่ง Cookie ของผู้ใช้คนอื่นไปที่ Server ของ Hacker ได้</p>
                </div>

                <div id="preventionSection" class="card shadow-lg border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (XSS Prevention)</h5>
                    </div>
                    <div class="card-body bg-dark text-white">
                        <p>สาเหตุเกิดจากเว็บไซต์นำข้อความที่ผู้ใช้พิมพ์ มาแสดงผลเป็น <strong>HTML</strong> โดยตรง ทำให้ Browser ตีความ Tag <code>&lt;script&gt;</code> เป็นคำสั่ง</p>

                        <div class="code-block code-bad">
                            ❌ <strong>Code ที่ไม่ปลอดภัย:</strong><br>
                            echo $user_comment;
                        </div>

                        <p class="mt-3">วิธีแก้คือต้อง <strong>Escaping</strong> ตัวอักษรพิเศษ (เช่น <code>&lt;</code> เป็น <code>&amp;lt;</code>) เพื่อให้ Browser มองเห็นเป็นแค่ "ตัวหนังสือ" ไม่ใช่ "คำสั่ง":</p>

                        <div class="code-block code-good">
                            ✅ <strong>Code ที่ปลอดภัย (CI4):</strong><br>
                            // ใช้ฟังก์ชัน esc() ครอบตัวแปรเสมอ<br>
                            echo esc($user_comment);
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleHint() {
            var x = document.getElementById("hint-content");
            x.style.display = (x.style.display === "none" || x.style.display === "") ? "block" : "none";
        }

        function postComment() {
            let input = document.getElementById('payloadInput').value;
            let display = document.getElementById('commentDisplay');
            let analysis = document.getElementById('analysisResult');
            let successBox = document.getElementById('successBox');
            let prevention = document.getElementById('preventionSection');

            if (input.trim() === "") return;

            // 1. เพิ่มคอมเมนต์ลงในกล่อง (จำลองว่าเว็บแสดงผลออกมาเลย)
            // หมายเหตุ: ใน Lab นี้เราใช้ innerHTML เพื่อจำลองความเสี่ยงจริงๆ
            let newComment = document.createElement('div');
            newComment.className = 'user-comment';
            newComment.innerHTML = '<strong>You:</strong><br> ' + input;
            display.appendChild(newComment);

            // เลื่อน Scrollbar ลงล่างสุด
            display.scrollTop = display.scrollHeight;

            // 2. ตรวจจับ XSS Pattern (Simulation logic)
            // เราเช็คว่ามี <script> หรือ on... event หรือไม่
            let xssPattern = /<script>|javascript:|on\w+=/i;

            if (xssPattern.test(input)) {
                // --- กรณี Hack สำเร็จ ---

                // จำลองการ Alert (ใช้ setTimeout เพื่อให้ HTML render ก่อนนิดนึง)
                setTimeout(() => {
                    alert('Hacked! โค้ด JavaScript ทำงานแล้ว');
                }, 100);

                analysis.innerHTML = '<span class="text-danger fw-bold">⚠️ ตรวจพบ Executable Code!</span><br>Browser กำลังรันคำสั่งที่คุณพิมพ์ลงไป...';

                successBox.style.display = 'block';
                prevention.style.display = 'block';
                prevention.scrollIntoView({
                    behavior: 'smooth'
                });

            } else if (input.includes('<b>') || input.includes('<i>') || input.includes('<h1>')) {
                // --- กรณี HTML Injection (แต่ไม่ใช่ Script) ---
                analysis.innerHTML = '<span class="text-warning fw-bold">⚠️ HTML Injection Detected</span><br>คุณเปลี่ยนรูปแบบตัวอักษรได้ แต่ยังไม่ได้รัน Code';
                successBox.style.display = 'none';
                prevention.style.display = 'none';

            } else {
                // --- กรณีข้อความปกติ ---
                analysis.innerHTML = '<span class="text-success">✅ ข้อความธรรมดา</span><br>แสดงผลเป็น Text ปกติ ไม่เกิดอันตราย';
                successBox.style.display = 'none';
                prevention.style.display = 'none';
            }

            // เคลียร์ช่อง input
            document.getElementById('payloadInput').value = '';
        }
    </script>

</body>

</html>