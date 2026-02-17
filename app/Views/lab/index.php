<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ห้องปฏิบัติการจำลอง - Cyber Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .hover-shadow:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; 
            transition: all 0.3s;
        }
        .lab-card {
            border-left: 5px solid #0d6efd;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/student/dashboard"><i class="bi bi-robot"></i> Cyber Lab</a>
            <a href="/student/dashboard" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container pb-5">
        <h3 class="mb-4"><i class="bi bi-incognito"></i> เลือกสถานการณ์จำลอง (Virtual Labs)</h3>
        
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif;?>

        <div class="row">
            <?php foreach($labs as $lab): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm hover-shadow h-100 lab-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary"><?= $lab['sim_title'] ?></h5>
                        <p class="card-text text-muted flex-grow-1"><?= $lab['sim_desc'] ?></p>
                        
                        <!-- เปลี่ยนปุ่ม Link เป็นปุ่มเปิด Modal -->
                        <button class="btn btn-primary mt-3 w-100" 
                                onclick="openBriefing('<?= $lab['sim_file_path'] ?>', '<?= $lab['sim_title'] ?>')">
                            <i class="bi bi-play-circle-fill"></i> เข้าสู่ภารกิจ
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Briefing (หน้าต่างเตรียมความพร้อม) -->
    <div class="modal fade" id="briefingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="briefingTitle">Mission Briefing</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <!-- ส่วนเป้าหมาย -->
                    <div class="alert alert-info border-0 shadow-sm">
                        <h6><i class="bi bi-flag-fill"></i> เป้าหมายภารกิจ:</h6>
                        <p class="mb-0" id="briefingObjective">Loading...</p>
                    </div>

                    <!-- ส่วนคำศัพท์ -->
                    <div class="card mb-3 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <strong><i class="bi bi-book-half"></i> คำศัพท์เทคนิคที่ควรรู้ (Vocabulary)</strong>
                        </div>
                        <div class="card-body bg-light">
                            <ul id="briefingVocab" class="list-group list-group-flush bg-transparent">
                                <!-- รายการคำศัพท์จะถูกเติมด้วย JS -->
                            </ul>
                        </div>
                    </div>

                    <p class="text-muted small text-center"><i class="bi bi-info-circle"></i> กดปุ่ม "เริ่มทำ Lab" เพื่อเข้าสู่หน้าจำลองสถานการณ์</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <a href="#" id="btnStartLab" class="btn btn-success fw-bold px-4">
                        เริ่มทำ Lab <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script จัดการข้อมูล Briefing -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ฐานข้อมูลคำศัพท์และภารกิจ (เก็บใน JS เพื่อความรวดเร็ว)
        const labData = {
            'phishing': {
                obj: "ตรวจสอบอีเมลที่น่าสงสัยและระบุให้ได้ว่าฉบับไหนคืออีเมลหลอกลวง (Phishing)",
                vocab: [
                    "<b>Phishing:</b> การหลอกลวงทางอินเทอร์เน็ตเพื่อขอข้อมูลสำคัญ",
                    "<b>Sender Spoofing:</b> การปลอมชื่อผู้ส่งอีเมลให้ดูน่าเชื่อถือ",
                    "<b>Malicious Link:</b> ลิงก์อันตรายที่นำไปสู่เว็บปลอม"
                ]
            },
            'sqli_login': {
                obj: "พยายามล็อกอินเข้าสู่ระบบ Admin ให้ได้โดยไม่ต้องรู้รหัสผ่าน แต่ใช้ช่องโหว่ทางภาษา SQL แทน",
                vocab: [
                    "<b>SQL Injection (SQLi):</b> การแทรกคำสั่ง SQL ผ่านช่องกรอกข้อมูล",
                    "<b>Bypass Authentication:</b> การข้ามผ่านระบบตรวจสอบตัวตน",
                    "<b>Payload:</b> โค้ดหรือคำสั่งที่ใช้ในการโจมตี"
                ]
            },
            'xss_playground': {
                obj: "ฝังโค้ด JavaScript ลงในหน้าเว็บสมุดเยี่ยมชม เพื่อให้โค้ดทำงานเมื่อมีผู้อื่นมาเปิดดู",
                vocab: [
                    "<b>XSS (Cross-Site Scripting):</b> ช่องโหว่ที่ยอมให้ฝัง Script อันตราย",
                    "<b>Cookie Stealing:</b> การขโมยคุกกี้เพื่อยึด Session ของผู้ใช้",
                    "<b>Script Tag:</b> แท็ก HTML &lt;script&gt; ที่ใช้เขียน JavaScript"
                ]
            },
            'lfi_gallery': {
                obj: "เจาะระบบแกลเลอรี่รูปภาพ เพื่อแอบอ่านไฟล์ความลับของระบบ (/etc/passwd)",
                vocab: [
                    "<b>Path Traversal:</b> การใช้ ../ เพื่อถอยกลับไปเข้าถึงไฟล์นอกโฟลเดอร์ที่กำหนด",
                    "<b>LFI (Local File Inclusion):</b> ช่องโหว่ที่ยอมให้ไฟล์ในเครื่องถูกเรียกใช้งาน",
                    "<b>/etc/passwd:</b> ไฟล์เก็บรายชื่อผู้ใช้ทั้งหมดในระบบ Linux"
                ]
            },
            'rfi_notes': {
                obj: "หลอกให้เว็บไซต์ดาวน์โหลดไฟล์อันตรายจากเครื่องแฮกเกอร์เข้ามารัน",
                vocab: [
                    "<b>RFI (Remote File Inclusion):</b> การสั่งให้ Server โหลดไฟล์จากแหล่งภายนอกมารัน",
                    "<b>Web Shell:</b> สคริปต์ที่ทำให้แฮกเกอร์ควบคุมเครื่อง Server ได้",
                    "<b>Malicious Server:</b> เครื่องเซิร์ฟเวอร์ของแฮกเกอร์ที่เตรียมไว้ปล่อยของ"
                ]
            },
            'brute_force': {
                obj: "ใช้สคริปต์ Python เดารหัสผ่าน (Dictionary Attack) เพื่อปลดล็อกไฟล์สำคัญ",
                vocab: [
                    "<b>Brute Force:</b> การเดารหัสผ่านทุกความเป็นไปได้",
                    "<b>Dictionary Attack:</b> การเดารหัสผ่านโดยใช้คลังคำศัพท์ (Wordlist)",
                    "<b>Wordlist:</b> ไฟล์ Text ที่รวบรวมรหัสผ่านยอดนิยมเอาไว้"
                ]
            },
            'cmd_injection': {
                obj: "แทรกคำสั่ง OS ผ่านหน้าเว็บ Ping เพื่อแอบดูรายชื่อไฟล์ใน Server",
                vocab: [
                    "<b>Command Injection:</b> การแทรกคำสั่ง System (OS) ผ่าน Input หน้าเว็บ",
                    "<b>Shell Command:</b> คำสั่งพื้นฐานของระบบ เช่น ls, dir, ping",
                    "<b>Concatenation:</b> การต่อคำสั่ง (เช่นใช้ ; หรือ &&)"
                ]
            },
            'idor_profile': {
                obj: "เปลี่ยนเลข ID ใน URL เพื่อแอบดูข้อมูลเงินเดือนของ CEO",
                vocab: [
                    "<b>IDOR:</b> (Insecure Direct Object References) การเข้าถึงข้อมูลโดยเปลี่ยนเลข ID ตรงๆ",
                    "<b>Access Control:</b> ระบบควบคุมสิทธิ์การเข้าถึงข้อมูล",
                    "<b>Parameter Tampering:</b> การแก้ไขค่าพารามิเตอร์ที่ส่งไปหา Server"
                ]
            },
            'upload_hack': {
                obj: "เขียนโค้ด Web Shell และอัปโหลดขึ้น Server เพื่อยึดเครื่อง",
                vocab: [
                    "<b>Unrestricted File Upload:</b> ช่องโหว่ที่ยอมให้อัปโหลดไฟล์อันตราย",
                    "<b>Web Shell:</b> โปรแกรมประตูหลัง (Backdoor) บนเว็บ",
                    "<b>MIME Type:</b> ชนิดของไฟล์ (เช่น image/png, application/php)"
                ]
            },
            'cookie_hack': {
                obj: "แก้ไขค่า Cookie ใน Browser เพื่อเลื่อนขั้นตัวเองเป็น Admin",
                vocab: [
                    "<b>Cookie:</b> ไฟล์ขนาดเล็กที่เก็บข้อมูลผู้ใช้ใน Browser",
                    "<b>Privilege Escalation:</b> การยกระดับสิทธิ์จาก User เป็น Admin",
                    "<b>Session Hijacking:</b> การสวมรอยเป็นผู้อื่นโดยใช้ Session ID"
                ]
            },
            'eternalblue_sim': {
                obj: "จำลองการใช้ Metasploit เจาะช่องโหว่ EternalBlue ยึดเครื่อง Windows 7",
                vocab: [
                    "<b>EternalBlue (MS17-010):</b> ช่องโหว่ร้ายแรงใน SMB Protocol ของ Windows",
                    "<b>Metasploit:</b> โปรแกรม Framework ยอดนิยมสำหรับเจาะระบบ",
                    "<b>Exploit:</b> โค้ดที่เขียนมาเพื่อเจาะช่องโหว่เฉพาะเจาะจง"
                ]
            },
            'logic_flaw': {
                obj: "ดักจับและแก้ไขราคาสินค้า เพื่อซื้อของแพงในราคา 1 บาท",
                vocab: [
                    "<b>Business Logic Flaw:</b> ช่องโหว่ที่เกิดจากตรรกะการทำงานผิดพลาด",
                    "<b>Proxy Interceptor:</b> เครื่องมือดักจับและแก้ไขข้อมูลระหว่างทาง (เช่น Burp Suite)",
                    "<b>Request Tampering:</b> การแก้ไขข้อมูลคำสั่งซื้อก่อนส่งถึง Server"
                ]
            }
        };

        function openBriefing(filePath, title) {
            // 1. ตั้งค่า Title และ Link
            document.getElementById('briefingTitle').innerText = title;
            document.getElementById('btnStartLab').href = '/lab/play/' + filePath;

            // 2. ดึงข้อมูลจาก labData
            const data = labData[filePath];
            const objEl = document.getElementById('briefingObjective');
            const vocabEl = document.getElementById('briefingVocab');

            if (data) {
                // แสดงภารกิจ
                objEl.innerText = data.obj;
                
                // แสดงคำศัพท์ (วนลูปสร้าง li)
                let vocabHtml = "";
                data.vocab.forEach(item => {
                    vocabHtml += `<li class="list-group-item bg-transparent border-0 ps-0"><i class="bi bi-dot text-warning"></i> ${item}</li>`;
                });
                vocabEl.innerHTML = vocabHtml;
            } else {
                // กรณีไม่มีข้อมูล (Fallback)
                objEl.innerText = "ศึกษาและปฏิบัติภารกิจตามคำแนะนำในหน้า Lab";
                vocabEl.innerHTML = "<li class='list-group-item bg-transparent'>ไม่มีข้อมูลคำศัพท์เพิ่มเติม</li>";
            }

            // 3. เปิด Modal
            var myModal = new bootstrap.Modal(document.getElementById('briefingModal'));
            myModal.show();
        }
    </script>
</body>
</html>