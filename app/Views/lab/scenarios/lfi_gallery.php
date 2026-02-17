<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Path Traversal Lab</title>
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

        /* Image Gallery Styles */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }
        .photo-card {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
            text-align: center;
        }
        .photo-card:hover { transform: scale(1.05); border: 2px solid #3b82f6; }
        .photo-card img { width: 100%; height: 100px; object-fit: cover; border-radius: 3px; }
        .photo-name { font-size: 0.8rem; margin-top: 5px; color: #64748b; }

        /* Hacker/Server Logs */
        .server-log {
            background-color: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier Prime', monospace;
            font-size: 0.9rem;
            border: 1px solid #334155;
            height: 100%;
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
    </style>
</head>
<body>

    <div class="container mt-4 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="color: #facc15;">📁 Lab 4: Path Traversal (LFI)</h3>
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
                                   value="http://cyber.lab:6001/photo.php?file=cat1.jpg"
                                   onkeypress="handleEnter(event)">
                            <button class="btn btn-sm btn-primary" onclick="loadUrl()">Go</button>
                        </div>

                        <div id="browserBody" class="browser-content">
                            </div>
                    </div>
                </div>
                
                <div class="mt-3 text-center">
                    <small class="text-muted">Tip: ลองคลิกขวาที่รูป (หรือคลิกซ้ายในแลปนี้) เพื่อดูชื่อไฟล์ แล้วลองแก้ URL ตามโจทย์</small>
                </div>
            </div>

            <div class="col-md-5">
                
                <div class="mb-3" style="height: 200px;">
                    <div class="server-log" id="serverLog">
                        <div class="border-bottom border-secondary mb-2 pb-1 text-secondary">root@server:/var/log/apache2# tail -f access.log</div>
                        <div id="logContent">
                            [INFO] Client connected from 192.168.1.10<br>
                            [INFO] Serving file: /var/www/html/photos/cat1.jpg
                        </div>
                    </div>
                </div>

                <div class="card bg-dark border-secondary text-white mb-3">
                    <div class="card-header border-secondary">
                        <i class="bi bi-list-task text-warning"></i> ภารกิจ Cyberninja
                    </div>
                    <div class="card-body small">
                        <p>เซิร์ฟเวอร์เก็บรูปภาพไว้ที่: <br><code class="text-warning">/var/www/html/photos/</code></p>
                        <ol class="ps-3 mb-0">
                            <li>สังเกต URL พารามิเตอร์ <code>?file=...</code></li>
                            <li>ลองเรียกไฟล์ <code>/etc/passwd</code> ตรงๆ ดูว่าจะได้ไหม?</li>
                            <li>ถ้าไม่ได้ ลองใช้ <code>../</code> ถอยกลับไป 4 ชั้น เพื่อไปหา root directory</li>
                            <li>Payload เป้าหมาย: <code>../../../../etc/passwd</code></li>
                        </ol>
                    </div>
                </div>

                <div id="preventionSection" class="card border-success shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body bg-dark text-white small">
                        <p>ปัญหานี้เกิดจากการรับค่าชื่อไฟล์จาก User ไปเปิดตรงๆ</p>
                        <div class="code-block code-bad mb-2">
                            ❌ Bad Code:<br>
                            include($_GET['file']);
                        </div>
                        <p>วิธีแก้: ใช้ <code>basename()</code> เพื่อตัด path ออก หรือเช็ค whitelist</p>
                        <div class="code-block code-good">
                            ✅ Good Code:<br>
                            $file = basename($_GET['file']); // ตัด ../ ทิ้ง<br>
                            if(in_array($file, ['cat1.jpg', 'dog.jpg'])) { ... }
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ข้อมูลจำลอง (Mock Data)
        const mockFiles = {
            'cat1.jpg': '<div class="text-center mt-5"><img src="https://placekitten.com/300/300" class="shadow rounded"><h4 class="mt-3">Cute Cat 1</h4></div>',
            'dog1.jpg': '<div class="text-center mt-5"><img src="https://placedog.net/300/300" class="shadow rounded"><h4 class="mt-3">Good Doggo</h4></div>',
            'hacker.png': '<div class="text-center mt-5"><img src="https://via.placeholder.com/300/000000/00FF00?text=HACKER" class="shadow rounded"><h4 class="mt-3">Matrix Code</h4></div>'
        };

        const passwdContent = `
root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sys:x:3:3:sys:/dev:/usr/sbin/nologin
www-data:x:33:33:www-data:/var/www:/usr/sbin/nologin
student:x:1000:1000:Student User,,,:/home/student:/bin/bash
# CONGRATULATIONS! YOU FOUND THE FLAG!
        `;

        // เริ่มต้นโหลดหน้าแรก
        renderGallery();

        function handleEnter(e) {
            if (e.key === 'Enter') loadUrl();
        }

        // ฟังก์ชันจำลองการทำงานของ Server
        function loadUrl() {
            const urlBar = document.getElementById('urlBar');
            const browserBody = document.getElementById('browserBody');
            const logContent = document.getElementById('logContent');
            const prevention = document.getElementById('preventionSection');
            
            let currentUrl = urlBar.value;
            
            // 1. ดึงค่า parameter 'file' ออกมา
            let urlObj;
            try {
                // แฮกนิดนึงเพื่อให้ new URL() ทำงานได้กับ domain ปลอม
                if(!currentUrl.startsWith('http')) currentUrl = 'http://' + currentUrl;
                urlObj = new URL(currentUrl);
            } catch (e) {
                alert('URL ไม่ถูกต้อง');
                return;
            }

            const fileParam = urlObj.searchParams.get("file");

            // อัปเดต Log ฝั่งขวา
            const timestamp = new Date().toLocaleTimeString();
            let logMsg = `<div class="text-muted">[${timestamp}] GET request: file=${fileParam}</div>`;
            
            // 2. Logic การทำงานของ Server (Vulnerable Logic)
            // Server ปัจจุบันอยู่ที่ /var/www/html/photos/
            
            if (!fileParam) {
                // ถ้าไม่มี parameter ให้กลับหน้า Home (Gallery)
                renderGallery();
                logMsg += `<div class="text-info">[INFO] No file specified. Showing gallery index.</div>`;
            } 
            else if (mockFiles[fileParam]) {
                // กรณี: เรียกไฟล์รูปปกติที่มีอยู่จริง (e.g., cat1.jpg)
                browserBody.innerHTML = mockFiles[fileParam] + '<div class="mt-4 text-center"><button class="btn btn-secondary btn-sm" onclick="resetToGallery()">Back to Gallery</button></div>';
                logMsg += `<div class="text-success">[SUCCESS] Serving image: /var/www/html/photos/${fileParam}</div>`;
            } 
            else if (fileParam === '/etc/passwd' || fileParam.startsWith('/')) {
                // กรณี: พยายามเรียก Absolute Path ตรงๆ (Step 3 ในโจทย์)
                // ระบบจะพยายามหาไฟล์ที่ /var/www/html/photos//etc/passwd (ซึ่งไม่มีจริง)
                browserBody.innerHTML = `
                    <div class="alert alert-warning">
                        <h4>⚠️ Warning: include(${fileParam}) failed.</h4>
                        <p><b>Message:</b> failed to open stream: No such file or directory.</p>
                        <p><b>Debug:</b> Server tried to look in <code>/var/www/html/photos/${fileParam}</code></p>
                    </div>
                `;
                logMsg += `<div class="text-danger">[ERROR] File not found: /var/www/html/photos/${fileParam}</div>`;
            }
            else if (fileParam.includes('../../../../etc/passwd')) {
                // กรณี: Hack สำเร็จ! (Step 4 ในโจทย์)
                // นับจำนวน ../ -> 4 ครั้ง ถอยจาก photos -> html -> www -> var -> root -> เจอ /etc/passwd
                
                browserBody.innerHTML = `
                    <div class="bg-dark text-white p-3 font-monospace rounded">
                        <pre style="color: #0f0; margin:0;">${passwdContent.trim()}</pre>
                    </div>
                    <div class="alert alert-success mt-3">
                        <h4>🎉 Mission Accomplished!</h4>
                        <p>คุณสามารถอ่านไฟล์ <code>/etc/passwd</code> ได้สำเร็จด้วยเทคนิค Directory Traversal</p>
                    </div>
                `;
                logMsg += `<div class="text-warning">[ALERT] Sensitive file accessed: /etc/passwd !!!</div>`;
                
                // โชว์วิธีป้องกัน
                prevention.style.display = 'block';
                prevention.scrollIntoView({ behavior: 'smooth' });
            } 
            else if (fileParam.includes('../')) {
                // กรณี: ใช้ ../ แต่ยังไม่ครบ 4 ชั้น หรือผิด path
                browserBody.innerHTML = `
                    <div class="alert alert-warning">
                        <h4>⚠️ Warning: include(${fileParam}) failed.</h4>
                        <p><b>Message:</b> failed to open stream: No such file or directory.</p>
                        <p>Hint: คุณอยู่ที่ <code>/var/www/html/photos/</code> คุณต้องถอยหลังกี่ชั้นถึงจะเจอ root (/) ?</p>
                    </div>
                `;
                logMsg += `<div class="text-danger">[ERROR] Path traversal attempt detected but file not found.</div>`;
            }
            else {
                // ไฟล์มั่วๆ
                browserBody.innerHTML = `<div class="alert alert-danger">Error: File <strong>${fileParam}</strong> not found.</div>`;
                logMsg += `<div class="text-danger">[ERROR] File not found.</div>`;
            }

            // Append Log
            logContent.innerHTML += logMsg;
            // Scroll Log to bottom
            const logContainer = document.getElementById('serverLog');
            logContainer.scrollTop = logContainer.scrollHeight;
        }

        // ฟังก์ชันวาดหน้า Gallery แรกเริ่ม
        function renderGallery() {
            const browserBody = document.getElementById('browserBody');
            const urlBar = document.getElementById('urlBar');
            
            // Reset URL Bar display (ไม่เปลี่ยนค่าจริง เพื่อให้ User แก้ต่อได้ง่าย)
            // urlBar.value = "http://cyber.lab:6001/"; 

            let html = '<h4 class="mb-3">Photo Gallery 📸</h4><div class="gallery-grid">';
            
            // Loop สร้างการ์ดรูปภาพ
            for (const [filename, content] of Object.entries(mockFiles)) {
                let imgUrl = "";
                if(filename.includes('cat')) imgUrl = "https://placekitten.com/150/150";
                else if(filename.includes('dog')) imgUrl = "https://placedog.net/150/150";
                else imgUrl = "https://via.placeholder.com/150/000000/00FF00?text=Hack";

                html += `
                    <div class="photo-card" onclick="selectPhoto('${filename}')">
                        <img src="${imgUrl}">
                        <div class="photo-name">${filename}</div>
                    </div>
                `;
            }
            html += '</div>';
            browserBody.innerHTML = html;
        }

        function selectPhoto(filename) {
            const urlBar = document.getElementById('urlBar');
            // เปลี่ยน URL ในช่อง Input เพื่อให้ User เห็น Pattern
            urlBar.value = `http://cyber.lab:6001/photo.php?file=${filename}`;
            // โหลดหน้านั้น
            loadUrl();
        }

        function resetToGallery() {
            const urlBar = document.getElementById('urlBar');
            urlBar.value = "http://cyber.lab:6001/";
            renderGallery();
        }
    </script>
</body>
</html>