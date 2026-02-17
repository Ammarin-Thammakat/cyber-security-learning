<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dictionary Attack Lab (Custom)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Courier Prime', monospace; }
        
        /* Terminal Styles */
        .terminal-window {
            background-color: #0c0c0c;
            border: 1px solid #333;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            font-family: 'Courier Prime', monospace;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 550px;
            position: relative;
        }
        .terminal-header {
            background-color: #1f1f1f;
            padding: 8px 15px;
            display: flex;
            gap: 8px;
            border-bottom: 1px solid #333;
        }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .red { background-color: #ff5f56; }
        .yellow { background-color: #ffbd2e; }
        .green { background-color: #27c93f; }
        
        .terminal-body {
            padding: 15px;
            color: #0f0;
            overflow-y: auto;
            flex-grow: 1;
            font-size: 0.9rem;
        }
        
        .command-line { display: flex; align-items: center; margin-bottom: 5px; }
        .prompt { color: #00f; margin-right: 10px; font-weight: bold; }
        .input-cmd {
            background: transparent;
            border: none;
            color: #fff;
            width: 100%;
            outline: none;
            font-family: 'Courier Prime', monospace;
            font-size: 1rem;
        }

        /* Editor Modal Overlay */
        #editorOverlay {
            display: none;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.9);
            z-index: 100;
            padding: 20px;
            flex-direction: column;
        }
        .editor-textarea {
            width: 100%;
            flex-grow: 1;
            background: #1e1e1e;
            color: #d4d4d4;
            border: 1px solid #444;
            font-family: 'Courier Prime', monospace;
            padding: 10px;
            resize: none;
        }
        .text-muted {
            color: #94a3b8 !important;
        }

        /* File & Code Styles */
        .file-card { background-color: #1e293b; border: 1px solid #334155; }
        .file-icon { font-size: 4rem; color: #cbd5e1; }
        .code-viewer {
            background-color: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            font-size: 0.85rem;
            color: #d4d4d4;
            border: 1px solid #444;
            max-height: 250px;
            overflow-y: auto;
        }
        .kw { color: #569cd6; } .str { color: #ce9178; } .fn { color: #dcdcaa; } .comment { color: #6a9955; }

        #preventionSection { display: none; animation: slideUp 0.8s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="container mt-4 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-danger">🔨 Lab 6: Dictionary Attack (Custom Wordlist)</h3>
            <a href="/lab" class="btn btn-outline-light btn-sm">ออกจาก Lab</a>
        </div>

        <div class="row">
            <div class="col-md-4">
                
                <div class="card file-card text-center py-3 mb-3 shadow-sm">
                    <div id="lockIcon">
                        <i class="bi bi-file-earmark-zip-fill file-icon"></i>
                        <i class="bi bi-lock-fill position-absolute text-danger" style="font-size: 2rem; margin-left: -15px; margin-top: 30px;"></i>
                    </div>
                    <h5 class="mt-2 text-white">confidential.zip</h5>
                    <div class="px-4 mt-2">
                        <input type="password" id="realPasswordInput" class="form-control form-control-sm text-center mb-2" placeholder="Password?" disabled>
                        <button id="unlockBtn" class="btn btn-sm btn-secondary w-100" onclick="checkUnlock()" disabled>Unlock</button>
                    </div>
                </div>

                <div class="card bg-dark border-secondary mb-3">
                    <div class="card-header border-secondary text-white small d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-folder2-open"></i> ~/Downloads/</span>
                        <span class="badge bg-warning text-dark">Editable</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center p-2 rounded hover-bg" style="border: 1px solid #444;">
                            <i class="bi bi-file-text text-white fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <div class="text-white fw-bold small">passwords.txt</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">Wordlist File</div>
                            </div>
                            <button class="btn btn-sm btn-outline-warning" onclick="openEditor()">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                        <div class="mt-2 small text-muted fst-italic">
                            * ลองกด Edit เพื่อใส่คำศัพท์ของคุณเอง ระบบจะสุ่ม 1 คำจากในนั้นมาเป็นรหัสผ่าน
                        </div>
                    </div>
                </div>

                <div class="card bg-dark border-secondary">
                    <div class="card-header border-secondary text-white small">
                        <i class="bi bi-filetype-py"></i> crack.py
                    </div>
                    <div class="code-viewer">
                        <span class="kw">import</span> sys<br>
                        filename = sys.argv[1]<br>
                        <span class="kw">with</span> <span class="fn">open</span>(filename) <span class="kw">as</span> f:<br>
                        &nbsp;&nbsp;<span class="kw">for</span> line <span class="kw">in</span> f:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;pwd = line.strip()<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">print</span>(f<span class="str">"Testing: {pwd}"</span>)<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> try_unlock(pwd):<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">print</span>(<span class="str">"FOUND: "</span> + pwd)<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">break</span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="terminal-window">
                    
                    <div id="editorOverlay">
                        <div class="d-flex justify-content-between text-white mb-2">
                            <span>Editing: passwords.txt</span>
                            <button class="btn btn-sm btn-danger" onclick="closeEditorWithoutSave()"><i class="bi bi-x"></i></button>
                        </div>
                        <textarea id="wordlistEditor" class="editor-textarea" spellcheck="false"></textarea>
                        <div class="d-flex justify-content-end mt-2 gap-2">
                            <button class="btn btn-sm btn-secondary" onclick="closeEditorWithoutSave()">Cancel</button>
                            <button class="btn btn-sm btn-success" onclick="saveWordlist()"><i class="bi bi-save"></i> Save & Randomize Target</button>
                        </div>
                    </div>

                    <div class="terminal-header">
                        <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                        <span class="ms-2 text-muted small">hacker@kali:~/Downloads</span>
                    </div>
                    
                    <div class="terminal-body" id="terminalOutput">
                        <div class="text-muted">Kali Linux Rolling (amd64)</div>
                        <div class="text-muted">Type 'ls' to list files.</div>
                        <br>
                        
                        <div class="command-line" id="inputLine">
                            <span class="prompt">hacker@kali:~/Downloads$</span>
                            <input type="text" id="cmdInput" class="input-cmd" autocomplete="off" autofocus onkeypress="handleCommand(event)">
                        </div>
                    </div>
                </div>

                <div id="preventionSection" class="card mt-3 border-success shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> วิธีป้องกัน (Prevention)</h6>
                    </div>
                    <div class="card-body bg-dark text-white small">
                        <p>Lab นี้พิสูจน์ให้เห็นว่า <strong>"ถ้าคุณใช้รหัสผ่านที่มีใน Dictionary แฮกเกอร์จะเจอมันแน่นอน"</strong></p>
                        <ul>
                            <li><strong>ตั้งให้ยาก:</strong> อย่าใช้คำศัพท์ที่มีความหมาย (เช่น love, admin, dragon)</li>
                            <li><strong>ผสมคำ:</strong> ถ้าจำยาก ให้ใช้ประโยคยาวๆ (Passphrase) เช่น <code>My-Cat-Eats-Pizza-2024</code></li>
                            <li><strong>2FA:</strong> เปิดใช้งาน Two-Factor Authentication เสมอ เพื่อกันการเดารหัสผ่าน</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // รายการคำเริ่มต้น
        let currentWordlist = [
            "123456", "password", "admin", "welcome", "football", 
            "monkey", "dragon", "supersecret", "iloveyou", "letmein"
        ];
        
        let targetPassword = ""; 
        let isCracking = false;

        // สุ่มรหัสผ่านครั้งแรกตอนโหลดหน้าเว็บ
        randomizeTarget();

        function randomizeTarget() {
            if (currentWordlist.length > 0) {
                // สุ่ม 1 คำจาก Wordlist มาเป็นคำตอบ
                const randomIndex = Math.floor(Math.random() * currentWordlist.length);
                targetPassword = currentWordlist[randomIndex];
                console.log("Secret Password set to: " + targetPassword); // แอบดูใน Console ได้
            }
        }

        // --- ส่วนจัดการ Editor ---
        function openEditor() {
            const editor = document.getElementById('editorOverlay');
            const textarea = document.getElementById('wordlistEditor');
            
            // เอาข้อมูลปัจจุบันไปใส่ใน Textarea (บรรทัดละคำ)
            textarea.value = currentWordlist.join('\n');
            editor.style.display = 'flex';
            textarea.focus();
        }

        function closeEditorWithoutSave() {
            document.getElementById('editorOverlay').style.display = 'none';
        }

        function saveWordlist() {
            const textarea = document.getElementById('wordlistEditor');
            const text = textarea.value.trim();
            
            if (!text) {
                alert("กรุณาใส่คำศัพท์อย่างน้อย 1 คำ");
                return;
            }

            // แปลง Text กลับเป็น Array (แยกบรรทัด)
            currentWordlist = text.split('\n').map(line => line.trim()).filter(line => line.length > 0);
            
            // *** ไฮไลท์: สุ่มคำตอบใหม่จากรายการที่ user เพิ่งแก้ ***
            randomizeTarget();

            document.getElementById('editorOverlay').style.display = 'none';
            
            // แสดงข้อความใน Terminal ว่าแก้ไขไฟล์แล้ว
            const terminal = document.getElementById('terminalOutput');
            const msg = document.createElement('div');
            msg.className = "text-warning mb-2";
            msg.innerText = `[System] File 'passwords.txt' updated. (${currentWordlist.length} words)`;
            terminal.insertBefore(msg, document.getElementById('inputLine'));
            terminal.scrollTop = terminal.scrollHeight;
        }

        // --- ส่วน Terminal Logic ---
        function handleCommand(e) {
            if (e.key === 'Enter') {
                const input = document.getElementById('cmdInput');
                const terminal = document.getElementById('terminalOutput');
                const cmd = input.value.trim();
                
                // Print command
                const oldLine = document.createElement('div');
                oldLine.className = 'command-line';
                oldLine.innerHTML = `<span class="prompt">hacker@kali:~/Downloads$</span> <span class="text-white">${cmd}</span>`;
                terminal.insertBefore(oldLine, document.getElementById('inputLine'));
                input.value = '';

                // Logic
                const parts = cmd.split(' ');
                
                if (cmd === 'ls') {
                    printOutput('crack.py  passwords.txt  confidential.zip');
                } 
                else if (parts[0] === 'python' && parts[1] === 'crack.py') {
                    if (parts[2] === 'passwords.txt') {
                        startDictionaryAttack();
                    } else if (!parts[2]) {
                        printOutput('Usage: python crack.py [wordlist_file]', 'text-warning');
                    } else {
                        printOutput(`Error: File '${parts[2]}' not found.`, 'text-danger');
                    }
                }
                else if (cmd === 'clear') {
                    terminal.innerHTML = '';
                    terminal.appendChild(document.getElementById('inputLine'));
                } 
                else if (cmd !== '') {
                    printOutput(`bash: ${parts[0]}: command not found`, 'text-danger');
                }
                
                terminal.scrollTop = terminal.scrollHeight;
            }
        }

        function printOutput(text, className = 'text-muted') {
            const terminal = document.getElementById('terminalOutput');
            const div = document.createElement('div');
            div.className = className + ' mb-2';
            div.innerText = text;
            terminal.insertBefore(div, document.getElementById('inputLine'));
        }

        function startDictionaryAttack() {
            if (isCracking) return;
            isCracking = true;
            
            const terminal = document.getElementById('terminalOutput');
            const inputLine = document.getElementById('inputLine');
            inputLine.style.display = 'none';

            let index = 0;
            const outputDiv = document.createElement('div');
            terminal.insertBefore(outputDiv, inputLine);

            const interval = setInterval(() => {
                if (index >= currentWordlist.length) {
                    // วนจนครบแล้วไม่เจอ (กรณี user ลบคำตอบทิ้ง)
                    clearInterval(interval);
                    isCracking = false;
                    outputDiv.innerHTML += `<div class="text-danger mt-2">[-] Password NOT FOUND in dictionary.</div>`;
                    inputLine.style.display = 'flex';
                    terminal.scrollTop = terminal.scrollHeight;
                    return;
                }

                const word = currentWordlist[index];
                
                // แสดงผลบรรทัดล่าสุด
                outputDiv.innerHTML = `<div>[*] Testing: <span class="text-info">${word}</span> ... <span class="text-danger">Invalid</span></div>`;

                if (word === targetPassword) {
                    clearInterval(interval);
                    
                    // เจอแล้ว!
                    outputDiv.innerHTML += `
                        <div class="mt-2 text-success">
                            [+] FOUND MATCH: <strong>${targetPassword}</strong>
                        </div>
                        <div class="text-success small">
                            >> Cracking process finished successfully. <<
                        </div>
                        <br>
                    `;
                    
                    inputLine.style.display = 'flex';
                    enableFileUnlock(targetPassword);
                }

                index++;
                terminal.scrollTop = terminal.scrollHeight; 

            }, 100); 
        }

        function enableFileUnlock(pass) {
            const passInput = document.getElementById('realPasswordInput');
            const unlockBtn = document.getElementById('unlockBtn');
            
            passInput.disabled = false;
            passInput.value = pass;
            passInput.classList.add('border-success', 'text-success', 'fw-bold');
            
            unlockBtn.disabled = false;
            unlockBtn.classList.remove('btn-secondary');
            unlockBtn.classList.add('btn-success');
            unlockBtn.innerText = "Unlock File";
            unlockBtn.focus();
        }

        function checkUnlock() {
            const input = document.getElementById('realPasswordInput').value;
            if (input === targetPassword) {
                document.getElementById('lockIcon').innerHTML = `
                    <i class="bi bi-file-earmark-check-fill text-success" style="font-size: 5rem;"></i>
                `;
                document.querySelector('.file-card h5').innerText = "confidential_data.pdf";
                
                document.getElementById('realPasswordInput').style.display = 'none';
                document.getElementById('unlockBtn').style.display = 'none';
                
                const dlBtn = document.createElement('button');
                dlBtn.className = "btn btn-outline-success btn-sm mt-2";
                dlBtn.innerHTML = '<i class="bi bi-download"></i> Download';
                dlBtn.onclick = function() { alert("Mission Complete! คุณเข้าใจหลักการทำงานของ Dictionary Attack แล้ว"); };
                document.querySelector('.file-card').appendChild(dlBtn);

                const prevention = document.getElementById('preventionSection');
                prevention.style.display = 'block';
                prevention.scrollIntoView({ behavior: 'smooth' });

            } else {
                alert("Incorrect Password!");
            }
        }
    </script>

</body>
</html>