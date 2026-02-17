<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Business Logic Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Shop UI */
        .shop-container {
            background-color: #fff;
            color: #333;
            border-radius: 8px;
            overflow: hidden;
            min-height: 500px;
        }
        .shop-header {
            background: linear-gradient(to right, #4f46e5, #06b6d4);
            color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;
        }
        .product-card {
            border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 15px;
            transition: all 0.2s;
        }
        .product-card:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .price-tag { font-size: 1.2rem; font-weight: bold; color: #4f46e5; }
        .wallet-badge { background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-weight: bold; }

        /* Interceptor Tool UI */
        .interceptor-panel {
            background-color: #1e1e1e;
            border: 1px solid #333;
            border-radius: 8px;
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'Courier Prime', monospace;
        }
        .tool-header {
            background-color: #2d2d2d; padding: 10px 15px; border-bottom: 1px solid #444;
            display: flex; justify-content: space-between; align-items: center;
        }
        .request-editor {
            background-color: #1e1e1e; color: #d4d4d4; border: none; padding: 15px;
            width: 100%; flex-grow: 1; resize: none; outline: none; font-size: 0.9rem;
        }
        .status-bar {
            padding: 5px 15px; font-size: 0.8rem; background: #252526; color: #ccc;
        }
        
        /* Toggle Switch */
        .form-switch .form-check-input { width: 3em; height: 1.5em; cursor: pointer; }
        .form-switch .form-check-input:checked { background-color: #f59e0b; border-color: #f59e0b; }

        /* Highlight classes */
        .highlight-edit { background-color: #37373d; color: #facc15; font-weight: bold; padding: 0 2px; }

        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-4 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-info">🛒 Lab 12: Business Logic Flaw (Price Tampering)</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <!-- ฝั่งซ้าย: เว็บขายของจำลอง -->
            <div class="col-md-7">
                <div class="shop-container shadow-lg">
                    <div class="shop-header">
                        <h4 class="mb-0"><i class="bi bi-cart3"></i> CyberStore</h4>
                        <div class="wallet-badge">
                            <i class="bi bi-wallet2"></i> Balance: $<span id="userMoney">100</span>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="alert alert-secondary small mb-4">
                            <i class="bi bi-info-circle-fill"></i> ยินดีต้อนรับ! คุณมีเงินติดตัว <strong>$100</strong> เลือกซื้อสินค้าที่คุณต้องการ
                        </div>

                        <!-- สินค้าปกติ -->
                        <div class="product-card d-flex align-items-center">
                            <div class="bg-light p-3 rounded me-3 text-center" style="width: 80px;">
                                <i class="bi bi-usb-drive display-6 text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">USB Flash Drive (8GB)</h5>
                                <div class="text-muted small">สินค้าธรรมดา สำหรับคนทั่วไป</div>
                            </div>
                            <div class="text-end">
                                <div class="price-tag mb-2">$50</div>
                                <button class="btn btn-primary btn-sm" onclick="buyItem('USB 8GB', 50)">Buy Now</button>
                            </div>
                        </div>

                        <!-- สินค้าเป้าหมาย -->
                        <div class="product-card d-flex align-items-center border-warning" style="background: #fffbeb;">
                            <div class="bg-warning p-3 rounded me-3 text-center" style="width: 80px;">
                                <i class="bi bi-cpu-fill display-6 text-dark"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 text-dark">Quantum Super Computer</h5>
                                <div class="text-danger small fw-bold">🔥 ITEM RARE! ต้องใช้ในการผ่านด่านนี้</div>
                            </div>
                            <div class="text-end">
                                <div class="price-tag text-danger mb-2">$100,000</div>
                                <button class="btn btn-danger btn-sm" onclick="buyItem('Quantum Computer', 100000)">Buy Now</button>
                            </div>
                        </div>

                        <!-- Log การซื้อ -->
                        <div id="shopLog" class="mt-4 p-3 bg-light rounded text-muted small" style="min-height: 100px;">
                            Waiting for transaction...
                        </div>
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: เครื่องมือ Interceptor -->
            <div class="col-md-5">
                <div class="interceptor-panel shadow-lg">
                    <!-- Header เครื่องมือ -->
                    <div class="tool-header">
                        <span class="text-warning fw-bold"><i class="bi bi-bug"></i> Proxy Interceptor</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="interceptToggle" onchange="toggleInterceptor()">
                            <label class="form-check-label text-white small" for="interceptToggle">Intercept is <span id="interceptState">OFF</span></label>
                        </div>
                    </div>

                    <!-- พื้นที่แก้ไข Request -->
                    <div class="position-relative flex-grow-1 d-flex flex-column">
                        <div class="status-bar border-bottom border-secondary">
                            <span id="reqStatus" class="text-muted">No request captured</span>
                        </div>
                        <textarea id="requestEditor" class="request-editor" spellcheck="false" disabled>
// เปิดสวิตช์ Intercept ด้านบน
// แล้วกดปุ่ม Buy ที่สินค้าเพื่อดักจับคำสั่งซื้อ
                        </textarea>
                        
                        <!-- ปุ่ม Action -->
                        <div class="p-3 bg-dark d-flex justify-content-end gap-2 border-top border-secondary">
                            <button id="dropBtn" class="btn btn-secondary btn-sm" onclick="dropRequest()" disabled>Drop</button>
                            <button id="forwardBtn" class="btn btn-warning btn-sm fw-bold" onclick="forwardRequest()" disabled>Forward Request <i class="bi bi-play-fill"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Prevention Section -->
                <div id="preventionSection" class="card mt-3 border-success bg-dark text-white">
                    <div class="card-header bg-success">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body small">
                        <p>ห้ามเชื่อข้อมูลราคาที่ส่งมาจาก Client (Browser) เด็ดขาด!</p>
                        <ul>
                            <li><strong>Server-Side Validation:</strong> เมื่อได้รับคำสั่งซื้อ ให้เอา `product_id` ไปดึงราคาจริงจาก Database เท่านั้น ห้ามใช้ราคาที่ส่งมากับ Request</li>
                            <li><strong>Integrity Check:</strong> ตรวจสอบว่าข้อมูลสำคัญถูกแก้ไขระหว่างทางหรือไม่ (เช่นใช้ HMAC)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let money = 100;
        let isIntercepting = false;
        let pendingRequest = null; // เก็บข้อมูลสินค้าที่กำลังจะซื้อ

        function toggleInterceptor() {
            const toggle = document.getElementById('interceptToggle');
            const stateLabel = document.getElementById('interceptState');
            isIntercepting = toggle.checked;
            
            if (isIntercepting) {
                stateLabel.innerText = "ON";
                stateLabel.className = "text-warning fw-bold";
                document.getElementById('requestEditor').value = "// Waiting for requests...";
            } else {
                stateLabel.innerText = "OFF";
                stateLabel.className = "";
                resetInterceptor();
            }
        }

        function buyItem(name, price) {
            if (isIntercepting) {
                // --- กรณีเปิด Interceptor: ดักจับ Request ---
                pendingRequest = { name: name, price: price };
                
                // สร้าง Raw HTTP Request ปลอมๆ เพื่อแสดงให้ user เห็น
                const rawRequest = `POST /shop/api/order HTTP/1.1
Host: cyber-store.local
Content-Type: application/x-www-form-urlencoded
Cookie: session_id=xyz123

product_name=${name}&price=${price}&quantity=1`;

                const editor = document.getElementById('requestEditor');
                editor.value = rawRequest;
                editor.disabled = false; // เปิดให้แก้ได้
                
                // อัปเดตสถานะ
                document.getElementById('reqStatus').innerHTML = '<span class="text-warning"><i class="bi bi-pause-circle"></i> Request Paused by User</span>';
                document.getElementById('forwardBtn').disabled = false;
                document.getElementById('dropBtn').disabled = false;
                
                // Highlight ให้ user เห็นว่าจะต้องแก้ตรงไหน (ทำไม่ได้ใน textarea แต่ใส่ comment แทน)
                // editor.focus();

            } else {
                // --- กรณีปกติ: ส่งไป Server เลย ---
                processTransaction(name, price);
            }
        }

        function forwardRequest() {
            // อ่านค่าจาก Textarea (ที่ User อาจจะแก้ไขแล้ว)
            const rawContent = document.getElementById('requestEditor').value;
            
            // พยายามแกะค่า price ออกมา (Simulation Parser)
            // หาคำว่า price=...
            const match = rawContent.match(/price=(\d+)/);
            
            if (match) {
                const newPrice = parseInt(match[1]);
                const name = pendingRequest ? pendingRequest.name : "Unknown Item";
                
                // ส่งค่าใหม่ไปประมวลผล
                processTransaction(name, newPrice);
                resetInterceptor();
            } else {
                alert("Error: Invalid Request Format! (หาพารามิเตอร์ price ไม่เจอ)");
            }
        }

        function dropRequest() {
            resetInterceptor();
            logShop("Transaction cancelled by user.", "text-muted");
        }

        function resetInterceptor() {
            pendingRequest = null;
            document.getElementById('requestEditor').value = isIntercepting ? "// Waiting for requests..." : "";
            document.getElementById('requestEditor').disabled = true;
            document.getElementById('forwardBtn').disabled = true;
            document.getElementById('dropBtn').disabled = true;
            document.getElementById('reqStatus').innerText = isIntercepting ? "Listening..." : "Interceptor Disabled";
        }

        function processTransaction(name, price) {
            // Logic ฝั่ง Server (จำลอง)
            
            logShop(`Processing order for "${name}" at price $${price}...`, "text-info");

            setTimeout(() => {
                if (money >= price) {
                    money -= price;
                    document.getElementById('userMoney').innerText = money;
                    
                    if (name === 'Quantum Computer') {
                        logShop(`SUCCESS! You bought ${name} for $${price}.`, "text-success fw-bold");
                        
                        // Hack สำเร็จ
                        const prevention = document.getElementById('preventionSection');
                        prevention.style.display = 'block';
                        prevention.scrollIntoView({ behavior: 'smooth' });
                        
                        alert("🎉 MISSION COMPLETE!\nคุณซื้อของราคาแสนด้วยเงินหลักร้อยสำเร็จ!");
                    } else {
                        logShop(`Purchase successful: ${name} (-$${price})`, "text-success");
                    }
                } else {
                    logShop(`FAILED! Insufficient funds. You need $${price} but have $${money}.`, "text-danger");
                    alert("❌ เงินไม่พอครับ! (Insufficient Funds)");
                }
            }, 500);
        }

        function logShop(msg, cssClass) {
            const log = document.getElementById('shopLog');
            log.innerHTML = `<div class="${cssClass}">[${new Date().toLocaleTimeString()}] ${msg}</div>` + log.innerHTML;
        }
    </script>

</body>
</html>
