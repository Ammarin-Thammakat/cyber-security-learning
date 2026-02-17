<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Cookie Manipulation Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        
        .browser-window {
            background-color: #fff; color: #333; border-radius: 8px; overflow: hidden;
            font-family: sans-serif; min-height: 500px; position: relative;
        }
        .browser-header { background: #e2e8f0; padding: 10px; border-bottom: 1px solid #ccc; display: flex; justify-content: space-between; align-items: center; }
        .web-content { padding: 20px; }
        
        /* DevTools Styles */
        .devtools-panel {
            position: absolute; bottom: 0; left: 0; right: 0; height: 200px;
            background: #242424; color: #ccc; border-top: 1px solid #444;
            font-family: sans-serif; font-size: 0.85rem; display: flex; flex-direction: column;
        }
        .dt-tabs { background: #333; display: flex; border-bottom: 1px solid #444; }
        .dt-tab { padding: 5px 15px; cursor: pointer; border-right: 1px solid #444; }
        .dt-tab.active { background: #242424; color: #fff; font-weight: bold; }
        .dt-body { padding: 0; overflow: auto; flex-grow: 1; }
        
        .cookie-table { width: 100%; border-collapse: collapse; }
        .cookie-table th { text-align: left; background: #333; color: #fff; padding: 5px; border-bottom: 1px solid #555; }
        .cookie-table td { padding: 5px; border-bottom: 1px solid #444; }
        .editable { background: #000; color: #facc15; border: 1px solid #555; padding: 2px 5px; cursor: text; }

        .admin-btn { pointer-events: none; opacity: 0.5; }
        .admin-unlocked { pointer-events: auto; opacity: 1; }

        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary">🍪 Lab 10: Cookie Manipulation</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <div class="browser-window shadow-lg">
                    <!-- Browser Header -->
                    <div class="browser-header">
                        <div class="d-flex gap-2">
                            <i class="bi bi-arrow-left text-secondary"></i>
                            <i class="bi bi-arrow-right text-secondary"></i>
                            <i class="bi bi-arrow-clockwise text-primary cursor-pointer" onclick="refreshPage()"></i>
                        </div>
                        <div class="bg-white px-3 py-1 rounded border text-muted small w-50 text-center">
                            http://my-bank.com/dashboard
                        </div>
                        <i class="bi bi-list text-secondary"></i>
                    </div>

                    <!-- Web Content -->
                    <div class="web-content text-center" id="pageContent">
                        <img src="https://ui-avatars.com/api/?name=User&background=random" class="rounded-circle mb-3 shadow">
                        <h3>Welcome, Guest User</h3>
                        <p class="text-muted">You have standard access privileges.</p>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-outline-primary">View Balance</button>
                            <button class="btn btn-outline-primary">Transfer Money</button>
                            <!-- ปุ่ม Admin ที่ถูกล็อก -->
                            <button id="adminBtn" class="btn btn-danger admin-btn">
                                <i class="bi bi-lock-fill"></i> Admin Panel (Locked)
                            </button>
                        </div>
                    </div>

                    <!-- Fake DevTools -->
                    <div class="devtools-panel">
                        <div class="dt-tabs">
                            <div class="dt-tab">Console</div>
                            <div class="dt-tab">Network</div>
                            <div class="dt-tab active">Application</div>
                        </div>
                        <div class="dt-body">
                            <div class="d-flex h-100">
                                <div style="width: 150px; background: #2a2a2a; border-right: 1px solid #444; padding: 10px;">
                                    <div class="text-white mb-2"><i class="bi bi-hdd"></i> Storage</div>
                                    <div class="ps-3 text-info"><i class="bi bi-cookie"></i> Cookies</div>
                                    <div class="ps-4 text-warning">http://my-bank.com</div>
                                </div>
                                <div class="flex-grow-1">
                                    <table class="cookie-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <th>Domain</th>
                                                <th>Path</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>session_id</td>
                                                <td>xyz123abc...</td>
                                                <td>my-bank.com</td>
                                                <td>/</td>
                                            </tr>
                                            <tr>
                                                <td>role</td>
                                                <!-- ส่วนที่ให้แก้ -->
                                                <td>
                                                    <input type="text" id="cookieValue" class="editable" value="user">
                                                </td>
                                                <td>my-bank.com</td>
                                                <td>/</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="p-2 text-warning small">
                                        * Tip: ลองเปลี่ยน value เป็น <code>admin</code> แล้วกดปุ่ม Refresh ด้านบน
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prevention -->
                <div id="preventionSection" class="card mt-4 border-success bg-dark text-white">
                    <div class="card-header bg-success">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body small">
                        <p>การเก็บสถานะสำคัญ (เช่น Role, User ID) ไว้ใน Cookie ที่ฝั่ง Client แก้ไขได้ เป็นอันตรายมาก</p>
                        <ul>
                            <li><strong>Server-Side Session:</strong> เก็บข้อมูล Role ไว้ใน Session ฝั่ง Server เท่านั้น</li>
                            <li><strong>Signed Cookies:</strong> หากจำเป็นต้องเก็บใน Cookie ต้องมีการเข้ารหัสหรือเซ็น Digital Signature เพื่อป้องกันการแก้ไข</li>
                            <li><strong>HttpOnly Flag:</strong> ตั้งค่า Cookie เป็น HttpOnly เพื่อป้องกัน XSS ขโมย Cookie (แม้จะไม่กันการแก้เอง แต่เป็น Best Practice)</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function refreshPage() {
            const cookieVal = document.getElementById('cookieValue').value;
            const content = document.getElementById('pageContent');
            const btn = document.getElementById('adminBtn');
            const prevention = document.getElementById('preventionSection');

            // จำลองการโหลดหน้าเว็บใหม่
            content.style.opacity = '0.5';
            
            setTimeout(() => {
                content.style.opacity = '1';

                if (cookieVal.toLowerCase() === 'admin') {
                    // Success Scenario
                    content.innerHTML = `
                        <img src="https://ui-avatars.com/api/?name=Admin&background=ef4444&color=fff" class="rounded-circle mb-3 shadow">
                        <h3 class="text-danger">Welcome, Administrator!</h3>
                        <p class="text-danger fw-bold">SYSTEM UNLOCKED</p>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-outline-primary">View Balance</button>
                            <button class="btn btn-danger admin-unlocked" onclick="alert('Admin Action Executed!')">
                                <i class="bi bi-unlock-fill"></i> Delete Database
                            </button>
                        </div>
                        <div class="alert alert-success mt-3">
                            🎉 Access Control Broken! You are now Admin.
                        </div>
                    `;
                    
                    prevention.style.display = 'block';
                    prevention.scrollIntoView({ behavior: 'smooth' });

                } else {
                    // Normal Scenario
                    content.innerHTML = `
                        <img src="https://ui-avatars.com/api/?name=User&background=random" class="rounded-circle mb-3 shadow">
                        <h3>Welcome, Guest User</h3>
                        <p class="text-muted">You have standard access privileges.</p>
                        
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button class="btn btn-outline-primary">View Balance</button>
                            <button class="btn btn-outline-primary">Transfer Money</button>
                            <button id="adminBtn" class="btn btn-danger admin-btn">
                                <i class="bi bi-lock-fill"></i> Admin Panel (Locked)
                            </button>
                        </div>
                    `;
                    prevention.style.display = 'none';
                }
            }, 800);
        }
    </script>
</body>
</html>