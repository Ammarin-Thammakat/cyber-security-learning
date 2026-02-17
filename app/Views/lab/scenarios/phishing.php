<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Phishing Simulation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .email-container { border: 1px solid #ddd; padding: 20px; background: #fff; border-radius: 8px; }
        .email-header { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .hidden { display: none; }
    </style>
</head>
<body class="bg-dark text-white">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>🎣 ภารกิจ: จับผิดอีเมลลวง</h3>
            <div>
                <span class="badge bg-warning text-dark fs-5">Score: <span id="score">0</span>/3</span>
                <a href="/lab" class="btn btn-outline-light btn-sm ms-3">ออกจาก Lab</a>
            </div>
        </div>

        <div id="game-area">
            </div>

        <div id="end-screen" class="hidden text-center mt-5">
            <h1>🎉 ภารกิจเสร็จสิ้น!</h1>
            <h3 class="text-info">คุณทำได้ <span id="final-score"></span>/3 คะแนน</h3>
            <p class="mt-3" id="feedback-text"></p>
            <a href="/lab" class="btn btn-primary btn-lg mt-3">กลับไปหน้า Lab</a>
            <button onclick="location.reload()" class="btn btn-secondary btn-lg mt-3">เล่นอีกครั้ง</button>
        </div>
    </div>

    <script>
        // ข้อมูลโจทย์ (Data)
        const scenarios = [
            {
                id: 1,
                subject: "แจ้งเตือน: รหัสผ่านของคุณหมดอายุ",
                sender: "support@g00gle.com", // สังเกต 0 แทน o
                content: "เรียนผู้ใช้, รหัสผ่านของคุณกำลังจะหมดอายุ กรุณาคลิกที่ลิงก์ด้านล่างเพื่อเปลี่ยนรหัสผ่านทันที <br><br> <a href='#' class='text-primary'>[ คลิกเพื่อเปลี่ยนรหัสผ่าน ]</a>",
                isPhishing: true,
                reason: "สังเกตที่อีเมลผู้ส่ง เขียนว่า @g00gle.com (ใช้เลข 0 แทนตัว o) นี่คือการปลอมแปลงชื่อโดเมน!"
            },
            {
                id: 2,
                subject: "ใบเสร็จรับเงิน Netflix",
                sender: "no-reply@mailer.netflix.com",
                content: "ขอบคุณที่ใช้บริการ Netflix นี่คือใบเสร็จรับเงินประจำเดือนของคุณ หากมีข้อสงสัยโปรดติดต่อเราผ่านแอปพลิเคชัน",
                isPhishing: false,
                reason: "อีเมลนี้มาจาก Domain ของ Netflix จริง และไม่มีการแนบลิงก์แปลกปลอมให้กรอกข้อมูลส่วนตัว"
            },
            {
                id: 3,
                subject: "ด่วน! บัญชีธนาคารของคุณถูกระงับ",
                sender: "security@kbank-verify-users.com", // Domain ปลอมยาวๆ
                content: "เราตรวจพบกิจกรรมที่น่าสงสัย บัญชีของคุณถูกระงับชั่วคราว กรุณายืนยันตัวตนด่วนที่สุดที่ลิงก์นี้: <a href='#'>http://bit.ly/2Ks9...</a>",
                isPhishing: true,
                reason: "ธนาคารจะไม่ส่งลิงก์แบบ Short Link (bit.ly) และ Domain ผู้ส่งดูน่าสงสัย ไม่ใช่ Domain หลักของธนาคาร"
            }
        ];

        let currentLevel = 0;
        let score = 0;

        function renderScenario() {
            if (currentLevel >= scenarios.length) {
                showEndScreen();
                return;
            }

            const data = scenarios[currentLevel];
            const html = `
                <div class="email-container text-dark shadow">
                    <div class="email-header">
                        <strong>Subject:</strong> ${data.subject} <br>
                        <strong>From:</strong> <span class="text-muted">${data.sender}</span>
                    </div>
                    <div class="email-body mb-4">
                        ${data.content}
                    </div>
                    <hr>
                    <p class="text-center text-muted small">คุณคิดว่าอีเมลนี้ จริง หรือ หลอก?</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button onclick="checkAnswer(false)" class="btn btn-success px-5">✅ ของจริง (Safe)</button>
                        <button onclick="checkAnswer(true)" class="btn btn-danger px-5">❌ หลอกลวง (Phishing)</button>
                    </div>
                </div>
            `;
            document.getElementById('game-area').innerHTML = html;
        }

        function checkAnswer(userSaysPhishing) {
            const data = scenarios[currentLevel];
            let isCorrect = (userSaysPhishing === data.isPhishing);

            if (isCorrect) {
                score++;
                document.getElementById('score').innerText = score;
                alert("ถูกต้อง! 🎉\n\n" + data.reason);
            } else {
                alert("ผิดครับ! ❌\n\n" + data.reason);
            }

            currentLevel++;
            renderScenario();
        }

        function showEndScreen() {
            document.getElementById('game-area').style.display = 'none';
            document.getElementById('end-screen').classList.remove('hidden');
            document.getElementById('final-score').innerText = score;
            
            const feedback = document.getElementById('feedback-text');
            if(score === 3) feedback.innerText = "สุดยอด! คุณเป็นนักจับ Phishing ระดับเซียน";
            else if(score >= 1) feedback.innerText = "ทำได้ดี แต่ต้องสังเกตให้รอบคอบกว่านี้อีกนิด";
            else feedback.innerText = "อันตราย! คุณมีความเสี่ยงสูงที่จะโดนหลอก ควรทบทวนบทเรียนด่วน";
        }

        // เริ่มเกม
        renderScenario();
    </script>
</body>
</html>