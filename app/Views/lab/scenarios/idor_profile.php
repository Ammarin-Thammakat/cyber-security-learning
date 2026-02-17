<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>IDOR Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        
        /* Browser Simulation */
        .browser-window {
            background-color: #fff; color: #333; border-radius: 8px; overflow: hidden;
            font-family: sans-serif; min-height: 400px;
        }
        .browser-bar {
            background-color: #e2e8f0; padding: 10px; border-bottom: 1px solid #cbd5e1;
            display: flex; gap: 10px;
        }
        .url-input {
            width: 100%; padding: 5px 10px; border-radius: 20px; border: 1px solid #cbd5e1;
            color: #333;
        }
                .text-muted{
            color: #94a3b8 !important;
        }
        
        /* Profile Card */
        .profile-header { height: 100px; background: linear-gradient(to right, #4facfe, #00f2fe); }
        .profile-img {
            width: 100px; height: 100px; border-radius: 50%; border: 4px solid #fff;
            margin-top: -50px; background: #ddd; object-fit: cover;
        }
        .sensitive-data { filter: blur(4px); transition: 0.3s; cursor: pointer; }
        .sensitive-data:hover { filter: blur(0); }
        
        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-info">🆔 Lab 8: IDOR (Insecure Direct Object References)</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <div class="browser-window shadow-lg">
                    <!-- URL Bar -->
                    <div class="browser-bar">
                        <input type="text" id="urlInput" class="url-input" 
                               value="http://internal-hr.corp/profile.php?id=105"
                               onkeypress="if(event.key === 'Enter') loadProfile()">
                        <button class="btn btn-sm btn-primary" onclick="loadProfile()">Go</button>
                    </div>

                    <!-- Content -->
                    <div id="profileContent" class="p-4 bg-light h-100">
                        <!-- Profile Card จะถูก Inject ตรงนี้ -->
                    </div>
                </div>

                <div class="mt-3 text-center text-muted">
                    <small>Tip: ลองเปลี่ยนเลข <code>id</code> ใน URL ดูสิ (พนักงานมี ID ตั้งแต่ 100 - 105)</small>
                </div>

                <!-- Prevention -->
                <div id="preventionSection" class="card mt-4 border-success bg-dark text-white">
                    <div class="card-header bg-success">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body small">
                        <p>IDOR เกิดจากการที่ระบบเชื่อใจ Input ของ User มากเกินไป โดยไม่เช็คสิทธิ์ความเป็นเจ้าของ</p>
                        <ul>
                            <li><strong>Access Control Check:</strong> ทุกครั้งที่ดึงข้อมูล ต้องเช็คว่า <code>Current_User.id == Requested_id</code> หรือไม่</li>
                            <li><strong>Use UUID:</strong> ใช้ ID ที่เดายากๆ เช่น <code>user_id=a1b2-c3d4...</code> แทนตัวเลขเรียงกัน (1, 2, 3)</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ข้อมูลจำลอง (Database)
        const db = {
            '100': { name: 'Elon M.', role: 'CEO', salary: '$5,000,000', img: 'https://ui-avatars.com/api/?name=Elon+M&background=0D8ABC&color=fff', secret: true },
            '101': { name: 'Alice HR', role: 'HR Manager', salary: '$80,000', img: 'https://ui-avatars.com/api/?name=Alice&background=random', secret: false },
            '105': { name: 'John Doe', role: 'Intern', salary: '$15,000', img: 'https://ui-avatars.com/api/?name=John&background=random', secret: false }
        };

        // โหลดครั้งแรก
        loadProfile();

        function loadProfile() {
            const urlBar = document.getElementById('urlInput');
            const content = document.getElementById('profileContent');
            const prevention = document.getElementById('preventionSection');

            // Parse ID
            let id = "105";
            try {
                const url = new URL(urlBar.value.startsWith('http') ? urlBar.value : 'http://' + urlBar.value);
                id = url.searchParams.get('id');
            } catch(e) { }

            const user = db[id];

            if (user) {
                let html = `
                    <div class="card border-0 shadow-sm mx-auto" style="max-width: 400px;">
                        <div class="profile-header"></div>
                        <div class="text-center">
                            <img src="${user.img}" class="profile-img shadow">
                            <h4 class="mt-2">${user.name}</h4>
                            <span class="badge bg-secondary">${user.role}</span>
                        </div>
                        <div class="card-body mt-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Employee ID:</span> <strong>${id}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Department:</span> <strong>Headquarters</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between bg-warning bg-opacity-10">
                                    <span>Salary (Confidential):</span> 
                                    <strong class="text-danger">${user.salary}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                `;
                content.innerHTML = html;

                if (user.secret) {
                    // ถ้าเจอ CEO (ID 100) ถือว่า Hack สำเร็จ
                    prevention.style.display = 'block';
                    prevention.scrollIntoView({ behavior: 'smooth' });
                    content.innerHTML += `<div class="alert alert-danger mt-3 text-center">🚨 ALERT: You accessed CEO's profile! (IDOR Success)</div>`;
                } else {
                    prevention.style.display = 'none';
                }

            } else {
                content.innerHTML = `<div class="alert alert-danger">Error: User ID ${id} not found.</div>`;
                prevention.style.display = 'none';
            }
        }
    </script>
</body>
</html>