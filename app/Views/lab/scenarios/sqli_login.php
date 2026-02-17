<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
            font-family: 'Courier Prime', monospace;
        }
        /* ... (CSS ส่วนเดิม) ... */
        .card { background-color: #1e293b; border: 1px solid #334155; }
        .card-header { background-color: #0f172a; border-bottom: 1px solid #334155; color: #38bdf8; font-weight: bold; }
        .terminal-window {
            background-color: #000000;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            font-family: 'Courier Prime', monospace;
        }
        .sql-query { color: #ce9178; }
        .sql-var { color: #38bdf8; font-weight: bold; }
        .hacker-input {
            background-color: #0f172a;
            color: #4ade80;
            border: 1px solid #334155;
            font-family: 'Courier Prime', monospace;
        }
        .hacker-input:focus {
            background-color: #0f172a;
            color: #4ade80;
            box-shadow: 0 0 10px rgba(74, 222, 128, 0.5);
            border-color: #4ade80;
        }
        .btn-hint { color: #facc15; border-color: #facc15; font-size: 0.8rem; }
        .btn-hint:hover { background-color: #facc15; color: #000; }
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
        .hint-box code { color: #fff; background-color: #000; padding: 2px 5px; border-radius: 3px; }
        .step-list { list-style: none; padding-left: 0; }
        .step-list li { margin-bottom: 15px; border-left: 2px solid #334155; padding-left: 15px; }
        .step-list li strong { color: #38bdf8; }

        /* --- ส่วนป้องกัน (Prevention) --- */
        #preventionSection {
            display: none; /* ซ่อนไว้ก่อน */
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
        .code-bad { border-color: #ef4444; color: #fca5a5; } /* สีแดง */
        .code-good { border-color: #22c55e; color: #86efac; } /* สีเขียว */

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hacker-input::placeholder {
            color: #475569;
        }
        .text-muted {
            color: #94a3b8 !important;
        }
    </style>
</head>
<body>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="color: #38bdf8;">💉 Lab SQL Injection: Login Bypass</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center py-3">🔒 Admin Panel Login</div>
                    <div class="card-body p-4">
                        <form id="hackForm" onsubmit="return false;">
                            <div class="mb-3">
                                <label class="form-label text-info">Username</label>
                                <input type="text" id="username" class="form-control hacker-input" placeholder="admin" oninput="updateTerminal()">
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-info">Password</label>
                                <input type="text" id="password" class="form-control hacker-input" placeholder="ไม่รู้รหัสผ่าน..." oninput="updateTerminal()">
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-sm btn-hint" onclick="toggleHint()">
                                    <i class="bi bi-lightbulb"></i> ดูคำใบ้
                                </button>
                            </div>
                            
                            <div id="hint-content" class="hint-box mb-4">
                                <strong>💡 คำใบ้:</strong> ลองปิดประโยค SQL ด้วย <code>'</code> แล้วเชื่อมด้วยตรรกะที่เป็นจริงเสมอ เช่น:<br>
                                <div class="text-center mt-2"><code>' OR '1'='1</code></div>
                            </div>

                            <button type="button" onclick="executeHack()" class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
                <div id="resultBox" class="alert mt-3 text-center shadow-sm" style="display:none; border-width: 2px;"></div>
            </div>

            <div class="col-md-7">
                <div class="terminal-window">
                    <h5 class="text-secondary border-bottom border-secondary pb-2 mb-3">
                        <i class="bi bi-terminal-fill"></i> Backend Database Log
                    </h5>
                    <p class="text-muted mb-2">// Server กำลังประมวลผลคำสั่ง SQL นี้:</p>
                    <div class="p-3 rounded" style="background-color: #1a1a1a;">
                        <code style="color: #e2e8f0; font-size: 1.1rem;">
                            SELECT * FROM users <br>
                            WHERE username = '<span id="sql-user" class="sql-var"></span>' <br>
                            AND password = '<span id="sql-pass" class="sql-var"></span>';
                        </code>
                    </div>
                    <div class="mt-4">
                        <p class="text-muted mb-1">// สถานะ Query:</p>
                        <div id="query-status" class="fs-5">🔴 Waiting for input...</div>
                    </div>
                </div>

                <div id="explanation-box" class="card text-white shadow-sm" style="background-color: #1e293b;">
                    <div class="card-body">
                        <h5 class="text-info mb-3"><i class="bi bi-book"></i> วิธีคิดแบบ Hacker</h5>
                        <ul class="step-list small mb-0" style="color: #cbd5e1;">
                            <li><strong>1. สังเกต Query:</strong> ระบบใช้ <code>'...'</code> ครอบข้อความที่เราพิมพ์ลงไป</li>
                            <li><strong>2. หาจุดอ่อน:</strong> พิมพ์ <code>'</code> เพื่อปิด String ของระบบก่อนกำหนด</li>
                            <li><strong>3. สร้างเงื่อนไข:</strong> ใช้ <code>OR</code> เชื่อมกับเงื่อนไขที่เป็นจริงเสมอ</li>
                        </ul>
                    </div>
                </div>

                <div id="preventionSection" class="card mt-3 shadow-lg border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Security Best Practice)</h5>
                    </div>
                    <div class="card-body bg-dark text-white">
                        <p>สาเหตุที่โดนเจาะได้ เพราะเราเอาตัวแปรมา <strong>"ต่อข้อความ" (Concatenation)</strong> ตรงๆ แบบนี้:</p>
                        
                        <div class="code-block code-bad">
                            ❌ <strong>Code ที่ไม่ปลอดภัย:</strong><br>
                            $sql = "SELECT * FROM users WHERE pass = '" . $password . "'";
                        </div>

                        <p class="mt-3">วิธีแก้คือใช้ <strong>Prepared Statement</strong> หรือ Query Builder ของ Framework (เช่น CodeIgniter 4) ซึ่งจะแยก "คำสั่ง" กับ "ข้อมูล" ออกจากกัน:</p>
                        
                        <div class="code-block code-good">
                            ✅ <strong>Code ที่ปลอดภัย (CI4):</strong><br>
                            $model->where('username', $username)<br>
                                  ->where('password', $password)<br>
                                  ->first();
                        </div>
                        
                        <p class="small text-muted mt-2 mb-0">
                            *ระบบจะแปลงข้อมูลที่เรากรอกให้เป็น Parameter ปลอดภัยโดยอัตโนมัติ ไม่ว่าเราจะพิมพ์ <code>' OR 1=1</code> มันก็จะมองเป็นแค่ "ข้อความธรรมดา" ไม่ใช่คำสั่ง SQL
                        </p>
                    </div>
                </div>
                </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script>
        function toggleHint() {
            var x = document.getElementById("hint-content");
            x.style.display = (x.style.display === "none" || x.style.display === "") ? "block" : "none";
        }

        function updateTerminal() {
            let user = document.getElementById('username').value;
            let pass = document.getElementById('password').value;
            document.getElementById('sql-user').innerText = user;
            document.getElementById('sql-pass').innerText = pass;
            document.getElementById('query-status').innerHTML = '<span class="text-warning"><i class="bi bi-hourglass-split"></i> Processing...</span>';
            document.getElementById('resultBox').style.display = 'none';
        }

        function executeHack() {
            let user = document.getElementById('username').value;
            let pass = document.getElementById('password').value;
            let sqlInjectionPattern = /'(\s+)?OR(\s+)?'1'='1/i;

            let resultBox = document.getElementById('resultBox');
            let queryStatus = document.getElementById('query-status');
            let prevention = document.getElementById('preventionSection'); // ส่วนป้องกัน

            if (sqlInjectionPattern.test(pass) || sqlInjectionPattern.test(user)) {
                // Hack สำเร็จ
                queryStatus.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Query OK! (Logic: TRUE)</span>';
                resultBox.className = 'alert alert-success mt-3 border-success';
                resultBox.innerHTML = '<h4 class="alert-heading">🎉 HACKED SUCCESS!</h4><p class="mb-0">ยินดีด้วย! คุณเจาะระบบสำเร็จ</p>';
                resultBox.style.display = 'block';

                // โชว์ส่วนวิธีป้องกัน
                prevention.style.display = 'block';
                // เลื่อนหน้าจอลงไปให้เห็น
                prevention.scrollIntoView({ behavior: 'smooth' });

            } else if (user === 'admin' && pass === 'password123') {
                queryStatus.innerHTML = '<span class="text-info"><i class="bi bi-check-circle-fill"></i> Query OK! (Match Found)</span>';
                resultBox.className = 'alert alert-info mt-3 border-info';
                resultBox.innerHTML = 'Login สำเร็จ (แบบคนปกติ)';
                resultBox.style.display = 'block';
                prevention.style.display = 'none'; // ซ่อนถ้า Login ปกติ

            } else {
                queryStatus.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> Query Failed (No Match)</span>';
                resultBox.className = 'alert alert-danger mt-3 border-danger';
                resultBox.innerHTML = '❌ Access Denied: รหัสผ่านไม่ถูกต้อง';
                resultBox.style.display = 'block';
                prevention.style.display = 'none'; // ซ่อนถ้า Login พลาด
            }
        }

        updateTerminal();
    </script>
</body>
</html>