<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Asset Monitor — โรงพยาบาลแม่แตง</title>
    <style>
        :root {
            --bg-color: #0b0e14;
            --card-bg: #151921;
            --text-primary: #ffffff;
            --text-secondary: #8b949e;
            --accent-blue: #00d1ff;
            --accent-green: #00ff88;
            --accent-red: #ff4d4d;
            --border-color: #30363d;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .topbar h1 { margin: 0; font-size: 24px; color: var(--accent-blue); }
        .topbar span { color: var(--text-secondary); font-size: 14px; }

        .stats-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .stat-label { color: var(--text-secondary); font-size: 12px; margin-bottom: 5px; }
        .stat-value { font-size: 28px; font-weight: bold; }
        .accent { color: var(--accent-blue); }
        .green { color: var(--accent-green); }

        .table-wrap {
            background: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #1c212b;
            padding: 15px;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .mono { font-family: 'Courier New', Courier, monospace; color: var(--accent-blue); }
        .chip { background: #232933; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        
        .live-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
        }

        .btn-refresh {
            background: var(--accent-blue);
            border: none;
            color: #000;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-refresh:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <header class="topbar">
        <div>
            <h1>🏥 โรงพยาบาลแม่แตง</h1>
            <span>IT Asset Management System (Cloud v2.2)</span>
        </div>
        <button class="btn-refresh" onclick="fetchSheetData()">↻ รีเฟรชข้อมูล</button>
    </header>

    <main>
        <div class="stats-strip">
            <div class="stat-card">
                <div class="stat-label">คอมพิวเตอร์ในระบบ</div>
                <div class="stat-value accent" id="stat-total">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Online (10 นาทีล่าสุด)</div>
                <div class="stat-value green" id="stat-online">0</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>HOSTNAME</th>
                        <th>MAINBOARD</th>
                        <th>CPU / CORES</th>
                        <th>RAM SIZE / TYPE</th>
                        <th>GPU / VRAM</th>
                        <th>USER / OS</th>
                        <th>LAST SEEN</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    </tbody>
            </table>
        </div>
    </main>

    <script>
        // *** สำคัญ: นำ URL (CSV) ที่ได้จาก Publish to Web ของ Google Sheet มาวางในเครื่องหมายคำพูดด้านล่างครับ ***
        const SHEET_CSV_URL = 'วาง_URL_CSV_ที่นี่';

        async function fetchSheetData() {
            const tbody = document.getElementById('table-body');
            try {
                const response = await fetch(SHEET_CSV_URL);
                const csvData = await response.text();
                const rows = csvData.split('\n').map(row => row.split(',')).slice(1);

                tbody.innerHTML = '';
                let onlineCount = 0;

                rows.forEach(cols => {
                    if (cols.length < 5) return;
                    
                    // ทำความสะอาดข้อมูล ลบเครื่องหมาย "
                    const c = cols.map(item => item.replace(/"/g, '').trim());
                    
                    // ลำดับคอลัมน์ตาม Google Apps Script:
                    // 0:Hostname, 1:IP, 2:Dept, 3:Loc, 4:Mainboard, 5:CPU, 6:Cores, 7:RAM, 8:Type, 9:GPU, 10:VRAM, 11:OS, 12:User, 13:LastUpdate
                    
                    const lastSeenStr = c[13];
                    const lastSeenDate = new Date(lastSeenStr);
                    const isOnline = (new Date() - lastSeenDate) / 1000 / 60 <= 10;
                    const statusColor = isOnline ? '#00ff88' : '#ff4d4d';
                    if (isOnline) onlineCount++;

                    tbody.innerHTML += `
                        <tr>
                            <td>
                                <div class="live-dot" style="background:${statusColor}"></div>
                                <b style="color:#fff">${c[0]}</b><br>
                                <small class="mono">${c[1]}</small>
                            </td>
                            <td style="font-size:11px; color:#8b949e">${c[4]}</td>
                            <td>
                                <div style="color:var(--accent-blue); font-size:13px;">${c[5]}</div>
                                <div style="font-size:10px; color:#555;">${c[6]}</div>
                            </td>
                            <td>
                                <span class="chip">${c[7]}</span>
                                <small style="color:#8b949e">${c[8]}</small>
                            </td>
                            <td>
                                <div style="font-size:12px;">${c[9]}</div>
                                <div style="font-size:10px; color:var(--accent-green);">${c[10] !== '-' ? 'VRAM: ' + c[10] : ''}</div>
                            </td>
                            <td style="font-size:11px;">
                                <div>${c[12]}</div>
                                <div style="color:#555;">${c[11]}</div>
                            </td>
                            <td style="font-size:11px; color:#8b949e;">${c[13]}</td>
                        </tr>
                    `;
                });

                document.getElementById('stat-total').textContent = rows.length;
                document.getElementById('stat-online').textContent = onlineCount;

            } catch (error) {
                console.error('Error fetching data:', error);
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:red;">ไม่สามารถโหลดข้อมูลได้ ตรวจสอบ URL CSV ใน Code ครับ</td></tr>';
            }
        }

        // โหลดทันทีและรีเฟรชทุก 1 นาที
        fetchSheetData();
        setInterval(fetchSheetData, 60000);
    </script>
</body>
</html>