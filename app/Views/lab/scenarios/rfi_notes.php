<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>RFI Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        
        /* Browser Simulation Styles */
        .browser-window {
            background-color: #fff;
            color: #333;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #475569;
            font-family: sans-serif;
            min-height: 500px;
        }
        .browser-bar {
            background-color: #e2e8f0;
            padding: 10px;
            border-bottom: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .url-input {
            width: 100%;
            padding: 5px 10px;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            font-family: sans-serif;
            color: #333;
        }
        .browser-content {
            padding: 20px;
            background-color: #f8fafc;
            height: 450px;
            overflow-y: auto;
        }

        /* Server Logs */
        .server-log {
            background-color: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier Prime', monospace;
            font-size: 0.9rem;
            border: 1px solid #334155;
            height: 200px;
            overflow-y: auto;
        }

        /* Prevention Section */
        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        .code-block {
            background-color: #1a1a1a;
            border-left: 4px solid;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.9rem;
            border-radius: 0 5px 5px 0;
        }
        .code-bad { border-color: #ef4444; color: #fca5a5; }
        .code-good { border-color: #22c55e; color: #86efac; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Fake PHP Info Style */
        .phpinfo-table { width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 0.8rem; }
        .phpinfo-table td, .phpinfo-table th { border: 1px solid #666; padding: 4px; }
        .phpinfo-header { background-color: #99c; color: #000; font-weight: bold; font-size: 1.2rem; padding: 10px; text-align: center; }
        .p-v { background-color: #ccf; color: #000; width: 30%; font-weight: bold; }
        .p-v-val { background-color: #ddd; color: #000; }
                .text-muted {
            color: #94a3b8 !important;
        }
    </style>
</head>
<body>

    <div class="container mt-4 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="color: #ef4444;">🌍 Lab 5: Remote File Inclusion (RFI)</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="card shadow-lg bg-transparent border-0">
                    <div class="browser-window">
                        <div class="browser-bar">
                            <div class="d-flex gap-2 text-secondary">
                                <i class="bi bi-arrow-left"></i>
                                <i class="bi bi-arrow-right"></i>
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <input type="text" id="urlBar" class="url-input" 
                                   value="http://cyber.lab:6002/note.php?url=intro.txt"
                                   onkeypress="handleEnter(event)">
                            <button class="btn btn-sm btn-primary" onclick="loadUrl()">Go</button>
                        </div>

                        <div id="browserBody" class="browser-content">
                            </div>
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">Tip: ลองเปลี่ยน <code>intro.txt</code> เป็น URL ของเครื่องแฮกเกอร์ เช่น <code>http://192.168.101.1:8080/phpinfo.php</code></small>
                </div>
            </div>

            <div class="col-md-5">
                
                <div class="mb-3">
                    <div class="text-white mb-1"><i class="bi bi-terminal"></i> Web Server Access Log</div>
                    <div class="server-log" id="serverLog">
                        <div class="text-muted">Waiting for request...</div>
                    </div>
                </div>

                <div class="card bg-dark border-secondary text-white mb-3">
                    <div class="card-header border-secondary">
                        <i class="bi bi-list-task text-warning"></i> ภารกิจ CyberLab
                    </div>
                    <div class="card-body small">
                        <p>เว็บไซต์นี้โหลดเนื้อหา Note ผ่านพารามิเตอร์ <code>?url=...</code></p>
                        <ol class="ps-3 mb-0">
                            <li>สมมติว่าคุณรัน Python Server ไว้ที่ <code>192.168.101.1:8080</code></li>
                            <li>คุณมีไฟล์ <code>phpinfo.php</code> อยู่ในเครื่องนั้น</li>
                            <li>ลองแก้ URL ให้เว็บเป้าหมายไปโหลดไฟล์จากเครื่องคุณแทน:
                                <br><code class="text-warning text-wrap">http://192.168.101.1:8080/phpinfo.php</code>
                            </li>
                        </ol>
                    </div>
                </div>

                <div id="preventionSection" class="card border-success shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body bg-dark text-white small">
                        <p>RFI เกิดขึ้นเพราะการตั้งค่า PHP และการเขียนโค้ดที่ไม่รัดกุม</p>
                        <div class="code-block code-bad mb-2">
                            ❌ Vulnerable Config (php.ini):<br>
                            allow_url_include = On
                        </div>
                        <p>วิธีแก้:</p>
                        <ul class="ps-3">
                            <li>ตั้งค่า <code>allow_url_include = Off</code> ใน php.ini (เป็น Default ใน PHP รุ่นใหม่)</li>
                            <li>หลีกเลี่ยงการรับ Input มาใส่ในฟังก์ชัน <code>include()</code> หรือ <code>require()</code> โดยตรง</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ข้อมูลจำลอง (Mock Data) สำหรับไฟล์ภายใน
        const localFiles = {
            'intro.txt': '<h4>ยินดีต้อนรับสู่ Note App</h4><p>นี่คือแอพจดบันทึกส่วนตัว คุณสามารถอ่านบันทึกต่างๆ ได้</p>',
            'todo.txt': '<h4>สิ่งที่ต้องทำ</h4><ul><li>ซื้อกาแฟ</li><li>ปั่นโปรเจกต์ Cyber Security</li><li>ให้อาหารแมว</li></ul>',
            'secret.txt': '<h4>ความลับ</h4><p>รหัสผ่าน WiFi คือ: supersecret123</p>'
        };

        // HTML จำลองหน้า PHP Info
        const phpInfoHTML = `
            <div style="background:#fff; color:#000; padding:10px;">
                <div class="phpinfo-header">PHP Version 8.1.10</div>
                <br>
                <table class="phpinfo-table">
                    <tr><td class="p-v">System</td><td class="p-v-val">Linux webserver 5.10.0-18-amd64</td></tr>
                    <tr><td class="p-v">Build Date</td><td class="p-v-val">Mar 30 2024 12:00:00</td></tr>
                    <tr><td class="p-v">Server API</td><td class="p-v-val">Apache 2.0 Handler</td></tr>
                    <tr><td class="p-v">allow_url_include</td><td class="p-v-val" style="color:red;">On</td></tr>
                </table>
                <br>
                <div class="alert alert-danger text-center">
                    <h4>⚠️ HACKED!</h4>
                    <p>Server ได้รันไฟล์ <code>phpinfo.php</code> จากเครื่องแฮกเกอร์เรียบร้อยแล้ว</p>
                </div>
            </div>
        `;

        // เริ่มต้น
        loadUrl();

        function handleEnter(e) {
            if (e.key === 'Enter') loadUrl();
        }

        function loadUrl() {
            const urlBar = document.getElementById('urlBar');
            const browserBody = document.getElementById('browserBody');
            const logContainer = document.getElementById('serverLog');
            const prevention = document.getElementById('preventionSection');
            
            let currentUrl = urlBar.value;
            let fileParam = "";

            try {
                if(!currentUrl.startsWith('http')) currentUrl = 'http://' + currentUrl;
                const urlObj = new URL(currentUrl);
                fileParam = urlObj.searchParams.get("url");
            } catch (e) {
                alert('URL ไม่ถูกต้อง'); return;
            }

            // Timestamp สำหรับ Log
            const timestamp = new Date().toLocaleTimeString();
            let logMsg = "";

            // --- Logic การจำลอง RFI ---
            
            if (!fileParam) {
                browserBody.innerHTML = '<div class="alert alert-info">กรุณาระบุไฟล์ที่ต้องการอ่านใน ?url=</div>';
                logMsg = `<div class="text-muted">[${timestamp}] GET /note.php (No params)</div>`;
            }
            // 1. กรณี: เรียก Local File ปกติ
            else if (localFiles[fileParam]) {
                browserBody.innerHTML = localFiles[fileParam];
                logMsg = `<div class="text-success">[${timestamp}] INCLUDE LOCAL: ${fileParam} (Success)</div>`;
            }
            // 2. กรณี: เรียก RFI (Hack สำเร็จ)
            // เช็คว่า URL ขึ้นต้นด้วย http และมีพอร์ต 8080 (ตามโจทย์) หรือเป็น phpinfo
            else if ((fileParam.includes('http://') || fileParam.includes('https://')) && fileParam.includes('phpinfo.php')) {
                
                browserBody.innerHTML = phpInfoHTML;
                
                logMsg = `<div class="text-danger fw-bold">[${timestamp}] RFI DETECTED!</div>`;
                logMsg += `<div class="text-warning ms-2">Fetching: ${fileParam}</div>`;
                logMsg += `<div class="text-warning ms-2">Executing remote PHP code...</div>`;
                
                // โชว์ส่วนป้องกัน
                prevention.style.display = 'block';
                prevention.scrollIntoView({ behavior: 'smooth' });
            }
            // 3. กรณี: พยายาม RFI แต่ไฟล์ไม่ใช่ phpinfo (เช่นไฟล์มั่วๆ)
            else if (fileParam.includes('http://') || fileParam.includes('https://')) {
                 browserBody.innerHTML = `
                    <div class="alert alert-warning">
                        <h4>Connection Timeout</h4>
                        <p>Server พยายามเชื่อมต่อไปยัง <code>${fileParam}</code> แต่ไม่สำเร็จ หรือไฟล์ไม่มีอยู่จริง</p>
                    </div>
                `;
                logMsg = `<div class="text-danger">[${timestamp}] RFI ATTEMPT: ${fileParam} (Failed/Timeout)</div>`;
            }
            // 4. กรณี: ไฟล์ไม่เจอ
            else {
                browserBody.innerHTML = `<div class="alert alert-danger">Error: Failed to open stream: No such file or directory for <strong>${fileParam}</strong></div>`;
                logMsg = `<div class="text-danger">[${timestamp}] ERROR: File not found (${fileParam})</div>`;
            }

            // Append Log
            logContainer.innerHTML += logMsg;
            logContainer.scrollTop = logContainer.scrollHeight;
        }
    </script>
</body>
</html>