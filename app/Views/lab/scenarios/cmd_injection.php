<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Command Injection Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        .terminal-window {
            background-color: #0c0c0c;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
            min-height: 400px;
        }
        .small{
            color: #94a3b8 !important;
        }
        .output-text { color: #00ff00; white-space: pre-wrap; }
        .input-box {
            background: #1e293b; border: 1px solid #475569; color: white;
            font-family: 'Courier Prime', monospace;
        }
        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-success"><i class="bi bi-terminal"></i> Lab 7: Command Injection</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card bg-dark border-secondary shadow">
                    <div class="card-header border-secondary text-white">
                        🌐 Server Network Diagnostic Tool
                    </div>
                    <div class="card-body">
                        <p>กรุณาระบุ IP Address ที่ต้องการทดสอบการเชื่อมต่อ (Ping)</p>
                        <form onsubmit="return runPing()">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-secondary text-white">IP:</span>
                                <input type="text" id="ipInput" class="form-control input-box" placeholder="e.g., 8.8.8.8" autocomplete="off">
                                <button class="btn btn-success" type="submit">Ping</button>
                            </div>
                        </form>
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle"></i> เบื้องหลังระบบใช้คำสั่ง: <code>ping -c 4 [IP]</code>ตัวอย่างลองใส่ 1.1.1.1
                        </div>
                    </div>
                </div>

                <!-- Hint -->
                <div class="card bg-dark border-warning mt-3">
                    <div class="card-body">
                        <h6 class="text-warning">🕵️ ภารกิจ:</h6>
                        <p class="small mb-0">
                            เราต้องการดูรายชื่อไฟล์ใน Server (คำสั่ง <code>ls</code> หรือ <code>dir</code>)<br>
                            ลองใช้เครื่องหมาย <code>;</code> เพื่อจบคำสั่ง Ping แล้วต่อด้วยคำสั่งอื่นดูสิ
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="terminal-window">
                    <div class="text-muted border-bottom border-secondary mb-2 pb-1">root@server:~# output console</div>
                    <div id="consoleOutput" class="output-text">Waiting for command...</div>
                </div>
            </div>
        </div>

        <!-- Prevention Section -->
        <div id="preventionSection" class="card mt-4 border-success shadow-lg">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
            </div>
            <div class="card-body bg-dark text-white small">
                <p>ปัญหานี้เกิดจากการนำ Input ของ User ไปต่อกับคำสั่ง System โดยตรง (เช่น <code>system("ping " . $ip)</code>)</p>
                <ul>
                    <li><strong>หลีกเลี่ยงการใช้ System Call:</strong> ถ้าจะ Ping ให้ใช้ Library ของภาษาโปรแกรมแทนการเรียก OS Command</li>
                    <li><strong>Input Validation:</strong> ตรวจสอบว่า Input เป็น IP Address จริงๆ (เฉพาะตัวเลขและจุด) ห้ามมีอักขระพิเศษ (; | &)</li>
                    <li><strong>Least Privilege:</strong> รัน Web Server ด้วย User ที่มีสิทธิ์ต่ำสุด ห้ามรันด้วย root</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function runPing() {
            const ip = document.getElementById('ipInput').value;
            const consoleOut = document.getElementById('consoleOutput');
            const prevention = document.getElementById('preventionSection');

            if(!ip) return false;

            consoleOut.innerHTML = `> ping -c 4 ${ip}\nProcessing...`;

            // Simulation Logic
            setTimeout(() => {
                let output = "";
                
                // กรณี 1: User ใส่ IP ปกติ (เช่น 8.8.8.8)
                if (/^[\d.]+$/.test(ip)) {
                    output = `PING ${ip} (${ip}) 56(84) bytes of data.\n64 bytes from ${ip}: icmp_seq=1 ttl=117 time=14.2 ms\n64 bytes from ${ip}: icmp_seq=2 ttl=117 time=13.8 ms\n\n--- ${ip} ping statistics ---\n2 packets transmitted, 2 received, 0% packet loss`;
                }
                // กรณี 2: Hack สำเร็จ (มี ; ls หรือ ; dir)
                else if (ip.includes('; ls') || ip.includes(';ls') || ip.includes('; dir')) {
                    output = `ping: ${ip.split(';')[0]}: Name or service not known\n\n`;
                    output += `> Executing injected command: ls\n`;
                    output += `--------------------------------\n`;
                    output += `index.php\nconfig.php\nusers.db\npasswords.txt  <-- (SECRET FILE FOUND!)\nimages/`;
                    
                    prevention.style.display = 'block';
                    prevention.scrollIntoView({ behavior: 'smooth' });
                }
                // กรณี 3: Hack ผิด format
                else if (ip.includes(';')) {
                    output = `bash: syntax error near unexpected token`;
                }
                else {
                    output = `ping: ${ip}: Name or service not known`;
                }

                consoleOut.innerHTML = output;
            }, 800);

            return false; // Prevent form submit
        }
    </script>
</body>
</html>