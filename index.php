<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IT Monitor — Hospital Asset System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ============================================
   HOSPITAL IT MONITOR — DARK INDUSTRIAL THEME
   ============================================ */
:root {
  --bg:        #0a0e14;
  --bg2:       #111720;
  --bg3:       #171f2c;
  --border:    #1e2d42;
  --border2:   #263649;
  --text:      #c8d6e8;
  --text2:     #7a94b0;
  --text3:     #4a6480;
  --accent:    #00c2ff;
  --accent2:   #0090cc;
  --green:     #00e676;
  --green2:    #00b050;
  --red:       #ff4444;
  --yellow:    #ffb300;
  --purple:    #b48eff;
  --card-glow: 0 0 0 1px var(--border), 0 4px 24px rgba(0,0,0,0.4);
  --radius:    10px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'IBM Plex Sans Thai', 'Space Grotesk', sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  overflow-x: hidden;
}

/* GRID BG */
body::before {
  content:'';
  position:fixed;
  inset:0;
  background-image:
    linear-gradient(var(--border) 1px, transparent 1px),
    linear-gradient(90deg, var(--border) 1px, transparent 1px);
  background-size:60px 60px;
  opacity:0.25;
  pointer-events:none;
  z-index:0;
}

/* TOPBAR */
.topbar {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(10,14,20,0.92);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
  height: 60px;
  gap: 16px;
}
.topbar-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}
.topbar-brand .logo {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px;
}
.topbar-brand h1 {
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 0.04em;
  color: #fff;
  line-height: 1.1;
}
.topbar-brand span {
  font-size: 11px;
  color: var(--text2);
  font-weight: 400;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  display: block;
}
.topbar-center {
  flex: 1;
  max-width: 480px;
}
.topbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}

/* SEARCH */
.search-box {
  position: relative;
  width: 100%;
}
.search-box input {
  width: 100%;
  background: var(--bg3);
  border: 1px solid var(--border);
  color: var(--text);
  border-radius: 8px;
  padding: 8px 16px 8px 38px;
  font-size: 13px;
  font-family: inherit;
  outline: none;
  transition: border-color 0.2s;
}
.search-box input:focus { border-color: var(--accent); }
.search-box .icon {
  position: absolute;
  left: 12px; top: 50%;
  transform: translateY(-50%);
  color: var(--text3);
  font-size: 14px;
}
.search-box input::placeholder { color: var(--text3); }

/* BUTTONS */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: 7px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.15s;
  font-family: inherit;
  white-space: nowrap;
}
.btn-ghost {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--text2);
}
.btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
.btn-primary {
  background: var(--accent);
  color: #000;
}
.btn-primary:hover { background: #33d1ff; }

/* LIVE INDICATOR */
.live-dot {
  display: flex; align-items: center; gap: 6px;
  font-size: 11px; color: var(--green); font-weight: 600;
  letter-spacing: 0.06em; text-transform: uppercase;
}
.live-dot::before {
  content:'';
  width:7px; height:7px;
  border-radius:50%;
  background: var(--green);
  box-shadow: 0 0 8px var(--green);
  animation: pulse 1.5s ease-in-out infinite;
}
@keyframes pulse {
  0%,100% { opacity:1; transform: scale(1); }
  50% { opacity:0.5; transform: scale(1.3); }
}

/* MAIN */
main {
  position: relative;
  z-index: 1;
  padding: 24px 28px;
  max-width: 1600px;
  margin: 0 auto;
}

/* STATS STRIP */
.stats-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}
.stat-card {
  background: var(--bg2);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 18px 20px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  transition: border-color 0.2s;
}
.stat-card:hover { border-color: var(--border2); }
.stat-label {
  font-size: 11px;
  color: var(--text3);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 600;
}
.stat-value {
  font-family: 'JetBrains Mono', monospace;
  font-size: 32px;
  font-weight: 600;
  line-height: 1;
  color: #fff;
}
.stat-value.green  { color: var(--green); }
.stat-value.red    { color: var(--red); }
.stat-value.accent { color: var(--accent); }
.stat-sub {
  font-size: 11px;
  color: var(--text2);
}

/* TOOLBAR */
.toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.filter-tabs {
  display: flex;
  background: var(--bg2);
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
}
.filter-tab {
  padding: 7px 16px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  color: var(--text2);
  transition: all 0.15s;
  border: none;
  background: transparent;
  font-family: inherit;
}
.filter-tab.active, .filter-tab:hover { background: var(--bg3); color: var(--text); }
.filter-tab.active { color: var(--accent); }

select.dept-select {
  background: var(--bg2);
  border: 1px solid var(--border);
  color: var(--text2);
  padding: 7px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-family: inherit;
  outline: none;
  cursor: pointer;
}
select.dept-select:focus { border-color: var(--accent); }

.toolbar-right { margin-left: auto; display: flex; gap: 8px; align-items: center; }
.count-badge {
  font-size: 11px;
  color: var(--text2);
  font-family: 'JetBrains Mono', monospace;
}

/* TABLE */
.table-wrap {
  background: var(--bg2);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
thead th {
  background: var(--bg3);
  padding: 10px 14px;
  text-align: left;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--text3);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
  cursor: pointer;
  user-select: none;
}
thead th:hover { color: var(--accent); }
thead th .sort-icon { margin-left: 4px; opacity: 0.4; }

tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background 0.12s;
  cursor: pointer;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: rgba(0,194,255,0.04); }
tbody td {
  padding: 11px 14px;
  vertical-align: middle;
}

/* STATUS BADGE */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 9px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.04em;
}
.status-badge.online  { background: rgba(0,230,118,0.12); color: var(--green); }
.status-badge.offline { background: rgba(255,68,68,0.10); color: var(--red); }
.status-badge::before {
  content:'';
  width:5px; height:5px;
  border-radius:50%;
}
.status-badge.online::before  { background: var(--green); box-shadow: 0 0 5px var(--green); }
.status-badge.offline::before { background: var(--red); }

/* CHIPS */
.chip {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 600;
}
.chip-dept { background: rgba(180,142,255,0.12); color: var(--purple); }

/* MONO */
.mono {
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
}
.text2 { color: var(--text2); }
.text3 { color: var(--text3); font-size: 11px; }

/* PROGRESS BAR */
.progress-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
}
.progress-bar {
  flex: 1;
  height: 4px;
  background: var(--bg3);
  border-radius: 2px;
  overflow: hidden;
  min-width: 60px;
}
.progress-fill {
  height: 100%;
  border-radius: 2px;
  transition: width 0.3s;
}
.bar-green  { background: var(--green2); }
.bar-yellow { background: var(--yellow); }
.bar-red    { background: var(--red); }
.progress-label {
  font-size: 11px;
  font-family: 'JetBrains Mono', monospace;
  color: var(--text2);
  min-width: 34px;
  text-align: right;
}

/* MODAL */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.7);
  backdrop-filter: blur(6px);
  z-index: 200;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.overlay.open { display: flex; }

.modal {
  background: var(--bg2);
  border: 1px solid var(--border2);
  border-radius: 14px;
  width: 100%;
  max-width: 820px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 24px 80px rgba(0,0,0,0.6);
  animation: slideUp 0.2s ease;
}
@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to   { transform: translateY(0); opacity: 1; }
}
.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
}
.modal-title {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
}
.modal-close {
  background: transparent;
  border: none;
  color: var(--text3);
  font-size: 20px;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 6px;
  transition: all 0.15s;
}
.modal-close:hover { background: var(--bg3); color: var(--text); }
.modal-body { padding: 24px; }

/* DETAIL GRID */
.detail-section {
  margin-bottom: 24px;
}
.detail-section-title {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: var(--accent);
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid var(--border);
}
.detail-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
.detail-item {
  background: var(--bg3);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 10px 14px;
}
.detail-item label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--text3);
  margin-bottom: 4px;
}
.detail-item .val {
  font-size: 14px;
  color: #fff;
  font-weight: 500;
}
.detail-item .val.mono {
  font-family: 'JetBrains Mono', monospace;
  font-size: 13px;
}

/* LOG TABLE */
.log-table { width:100%; border-collapse:collapse; font-size:12px; }
.log-table th {
  padding: 6px 10px;
  text-align: left;
  color: var(--text3);
  border-bottom: 1px solid var(--border);
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}
.log-table td {
  padding: 7px 10px;
  border-bottom: 1px solid var(--border);
  color: var(--text2);
  font-family: 'JetBrains Mono', monospace;
}
.log-table tr:last-child td { border-bottom: none; }

/* NOTES */
textarea.notes-input {
  width: 100%;
  background: var(--bg3);
  border: 1px solid var(--border);
  color: var(--text);
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 13px;
  font-family: inherit;
  resize: vertical;
  outline: none;
  min-height: 80px;
}
textarea.notes-input:focus { border-color: var(--accent); }

/* EMPTY STATE */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text3);
}
.empty-state .icon { font-size: 40px; margin-bottom: 12px; opacity: 0.5; }
.empty-state p { font-size: 13px; }

/* LOADING */
.loading {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px;
  color: var(--text3);
  gap: 10px;
}
.spinner {
  width: 20px; height: 20px;
  border: 2px solid var(--border);
  border-top-color: var(--accent);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* SCROLLBAR */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--text3); }

/* RESPONSIVE */
@media (max-width: 1024px) {
  .stats-strip { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
  main { padding: 16px; }
  .topbar { padding: 0 16px; }
  .topbar-center { display: none; }
  .stats-strip { grid-template-columns: repeat(2, 1fr); gap: 10px; }
  table { font-size: 12px; }
}

/* TOAST */
.toast {
  position: fixed;
  bottom: 24px; right: 24px;
  background: var(--bg3);
  border: 1px solid var(--green);
  color: var(--green);
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 13px;
  z-index: 500;
  animation: fadeIn 0.2s ease;
  display: none;
}
.toast.show { display: block; }
@keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <div class="topbar-brand">
    <div class="logo">🏥</div>
    <div>
      <h1 id="hosp-name">Hospital IT Monitor</h1>
      <span>Asset Monitor System</span>
    </div>
  </div>

  <div class="topbar-center">
    <div class="search-box">
      <span class="icon">🔍</span>
      <input type="text" id="search-input" placeholder="ค้นหา hostname, IP, แผนก, รุ่น, S/N..." autocomplete="off">
    </div>
  </div>

  <div class="topbar-right">
    <div class="live-dot" id="live-indicator">LIVE</div>
    <button class="btn btn-ghost" onclick="exportCSV()">⬇ Export CSV</button>
    <button class="btn btn-primary" onclick="fetchData()">↻ Refresh</button>
  </div>
</header>

<!-- MAIN -->
<main>

  <!-- STATS -->
  <div class="stats-strip">
    <div class="stat-card">
      <div class="stat-label">เครื่องทั้งหมด</div>
      <div class="stat-value accent" id="stat-total">—</div>
      <div class="stat-sub">ที่ลงทะเบียนไว้</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Online</div>
      <div class="stat-value green" id="stat-online">—</div>
      <div class="stat-sub" id="stat-online-pct">— % of total</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Offline</div>
      <div class="stat-value red" id="stat-offline">—</div>
      <div class="stat-sub">ไม่ตอบสนอง > 10 นาที</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">แผนก / Ward</div>
      <div class="stat-value" id="stat-dept">—</div>
      <div class="stat-sub">ที่มีข้อมูล</div>
    </div>
  </div>

  <!-- TOOLBAR -->
  <div class="toolbar">
    <div class="filter-tabs">
      <button class="filter-tab active" data-status="all" onclick="setStatus('all',this)">ทั้งหมด</button>
      <button class="filter-tab" data-status="online" onclick="setStatus('online',this)">🟢 Online</button>
      <button class="filter-tab" data-status="offline" onclick="setStatus('offline',this)">🔴 Offline</button>
    </div>

    <select class="dept-select" id="dept-filter" onchange="fetchData()">
      <option value="">— ทุกแผนก —</option>
    </select>

    <div class="toolbar-right">
      <span class="count-badge" id="count-badge"></span>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-wrap">
    <div class="loading" id="loading">
      <div class="spinner"></div>
      <span>กำลังโหลดข้อมูล...</span>
    </div>
    <div id="table-container" style="display:none;">
      <table id="main-table">
        <thead>
          <tr>
            <th onclick="sortBy('hostname')">Hostname <span class="sort-icon">↕</span></th>
            <th onclick="sortBy('department')">แผนก / Ward <span class="sort-icon">↕</span></th>
            <th onclick="sortBy('ip_address')">IP Address <span class="sort-icon">↕</span></th>
            <th>ยี่ห้อ / รุ่น</th>
            <th>CPU</th>
            <th onclick="sortBy('ram_total_gb')">RAM <span class="sort-icon">↕</span></th>
            <th onclick="sortBy('disk_free_gb')">Disk Free <span class="sort-icon">↕</span></th>
            <th>OS</th>
            <th>ผู้ใช้งาน</th>
            <th onclick="sortBy('last_seen')">Last Seen <span class="sort-icon">↕</span></th>
            <th>สถานะ</th>
          </tr>
        </thead>
        <tbody id="table-body"></tbody>
      </table>
      <div class="empty-state" id="empty-state" style="display:none;">
        <div class="icon">💻</div>
        <p>ไม่พบเครื่องคอมพิวเตอร์ที่ตรงกับเงื่อนไข</p>
      </div>
    </div>
  </div>

</main>

<!-- MODAL -->
<div class="overlay" id="detail-overlay" onclick="closeIfOverlay(event)">
  <div class="modal" id="detail-modal">
    <div class="modal-header">
      <div>
        <div class="modal-title" id="modal-hostname">—</div>
        <div class="text3" id="modal-status-line">—</div>
      </div>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div class="modal-body" id="modal-body">
      <div class="loading"><div class="spinner"></div><span>กำลังโหลด...</span></div>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
// ============================================
// STATE
// ============================================
let allData = [];
let currentSort = { key: 'last_seen', dir: -1 };
let currentStatus = 'all';
let refreshTimer = null;
const REFRESH_INTERVAL = 30000; // 30s

// ============================================
// FETCH DATA
// ============================================
async function fetchData() {
  const search = document.getElementById('search-input').value.trim();
  const dept   = document.getElementById('dept-filter').value;

  const params = new URLSearchParams({ search, department: dept, status: currentStatus });
  const url = `api/get_computers.php?${params}`;

  document.getElementById('loading').style.display = 'flex';
  document.getElementById('table-container').style.display = 'none';

  try {
    const res  = await fetch(url);
    const json = await res.json();

    allData = json.computers || [];

    // Stats
    document.getElementById('stat-total').textContent  = json.stats?.total  || 0;
    document.getElementById('stat-online').textContent = json.stats?.online || 0;
    document.getElementById('stat-dept').textContent   = json.stats?.departments || 0;
    const offline = (json.stats?.total || 0) - (json.stats?.online || 0);
    document.getElementById('stat-offline').textContent = offline;
    const pct = json.stats?.total > 0 ? Math.round((json.stats?.online / json.stats?.total) * 100) : 0;
    document.getElementById('stat-online-pct').textContent = `${pct}% ของทั้งหมด`;

    // Departments
    const deptSelect = document.getElementById('dept-filter');
    const currentDept = deptSelect.value;
    while (deptSelect.options.length > 1) deptSelect.remove(1);
    (json.departments || []).forEach(d => {
      const opt = new Option(d, d);
      if (d === currentDept) opt.selected = true;
      deptSelect.add(opt);
    });

    renderTable(allData);

    document.getElementById('loading').style.display = 'none';
    document.getElementById('table-container').style.display = 'block';

  } catch (e) {
    console.error(e);
    document.getElementById('loading').innerHTML = '<span style="color:var(--red)">⚠ ไม่สามารถเชื่อมต่อฐานข้อมูลได้</span>';
  }
}

// ============================================
// RENDER TABLE
// ============================================
function renderTable(data) {
  // Sort
  data = [...data].sort((a, b) => {
    let va = a[currentSort.key], vb = b[currentSort.key];
    if (va == null) va = '';
    if (vb == null) vb = '';
    if (typeof va === 'number') return currentSort.dir * (va - vb);
    return currentSort.dir * String(va).localeCompare(String(vb));
  });

  const tbody = document.getElementById('table-body');
  const empty = document.getElementById('empty-state');

  if (data.length === 0) {
    tbody.innerHTML = '';
    empty.style.display = 'block';
    document.getElementById('count-badge').textContent = '0 เครื่อง';
    return;
  }

  empty.style.display = 'none';
  document.getElementById('count-badge').textContent = `${data.length} เครื่อง`;

  tbody.innerHTML = data.map(c => {
    const online  = !!c.is_online;
    const badge   = online
      ? `<span class="status-badge online">Online</span>`
      : `<span class="status-badge offline">Offline</span>`;

    const ramPct   = c.ram_total_gb > 0 ? ((c.ram_used_gb / c.ram_total_gb) * 100) : 0;
    const diskPct  = c.disk_total_gb > 0 ? ((1 - c.disk_free_gb / c.disk_total_gb) * 100) : 0;
    const ramBar   = colorBar(ramPct);
    const diskBar  = colorBar(diskPct);

    const cpuShort = (c.cpu_name || '—').replace(/Intel\(R\)\s?/i,'').replace(/\s+CPU\s+/,' ').substring(0,28);
    const lastSeen = c.last_seen ? timeAgo(c.last_seen) : '—';
    const dept     = c.department ? `<span class="chip chip-dept">${esc(c.department)}</span>` : '—';

    return `<tr onclick="openDetail(${c.id})">
      <td>
        <div style="font-weight:600;color:#fff">${esc(c.hostname)}</div>
        <div class="text3">${esc(c.location || '')}</div>
      </td>
      <td>${dept}</td>
      <td><span class="mono">${esc(c.ip_address)}</span></td>
      <td>
        <div style="font-size:12px;color:#fff">${esc(c.manufacturer||'')} ${esc(c.model||'')}</div>
        <div class="text3 mono">${esc(c.serial_number ? 'S/N: '+c.serial_number : '')}</div>
      </td>
      <td>
        <div class="text2" style="font-size:11px;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${esc(c.cpu_name||'')}">
          ${esc(cpuShort||'—')}
        </div>
        <div class="text3">${c.cpu_cores ? c.cpu_cores+'C/'+c.cpu_threads+'T' : ''}</div>
      </td>
      <td>
        <div class="progress-wrap">
          <div class="progress-bar"><div class="progress-fill ${ramBar}" style="width:${ramPct}%"></div></div>
          <div class="progress-label">${c.ram_total_gb ? c.ram_total_gb+'GB' : '—'}</div>
        </div>
        <div class="text3">${c.ram_used_gb ? 'ใช้ '+c.ram_used_gb+' GB' : ''}</div>
      </td>
      <td>
        <div class="progress-wrap">
          <div class="progress-bar"><div class="progress-fill ${diskBar}" style="width:${diskPct}%"></div></div>
          <div class="progress-label">${c.disk_free_gb != null ? c.disk_free_gb+'GB' : '—'}</div>
        </div>
        <div class="text3">${c.disk_total_gb ? 'ทั้งหมด '+c.disk_total_gb+' GB' : ''}</div>
      </td>
      <td><div class="text2" style="font-size:11px">${esc((c.os_name||'').replace('Microsoft ',''))}</div>
          <div class="text3">${esc(c.os_architecture||'')}</div></td>
      <td><div class="text2" style="font-size:12px">${esc(c.logged_user||'—')}</div></td>
      <td><div class="text2" style="font-size:12px">${lastSeen}</div></td>
      <td>${badge}</td>
    </tr>`;
  }).join('');
}

// ============================================
// DETAIL MODAL
// ============================================
async function openDetail(id) {
  document.getElementById('detail-overlay').classList.add('open');
  document.getElementById('modal-hostname').textContent = 'Loading...';
  document.getElementById('modal-status-line').textContent = '';
  document.getElementById('modal-body').innerHTML = '<div class="loading"><div class="spinner"></div><span>กำลังโหลด...</span></div>';

  try {
    const res  = await fetch(`api/get_computer.php?id=${id}`);
    const json = await res.json();
    const c    = json.computer;
    const logs = json.logs || [];

    document.getElementById('modal-hostname').textContent = c.hostname;
    const online = !!c.is_online;
    document.getElementById('modal-status-line').innerHTML =
      `${online ? '🟢 Online' : '🔴 Offline'} &nbsp;|&nbsp; ${c.ip_address} &nbsp;|&nbsp; ${c.department||'ไม่ระบุแผนก'} &nbsp;|&nbsp; Last seen: ${c.last_seen}`;

    const ramPct  = c.ram_total_gb  > 0 ? ((c.ram_used_gb  / c.ram_total_gb)  * 100).toFixed(0) : 0;
    const diskPct = c.disk_total_gb > 0 ? ((1 - c.disk_free_gb / c.disk_total_gb) * 100).toFixed(0) : 0;

    document.getElementById('modal-body').innerHTML = `
      <!-- LOCATION -->
      <div class="detail-section">
        <div class="detail-section-title">📍 ตำแหน่งที่ตั้ง</div>
        <div class="detail-grid">
          ${dItem('Hostname', c.hostname, true)}
          ${dItem('IP Address', c.ip_address, true)}
          ${dItem('MAC Address', c.mac_address)}
          ${dItem('Location', c.location)}
          ${dItem('แผนก / Ward', c.department)}
          ${dItem('Domain', c.domain)}
          ${dItem('ผู้ใช้งานปัจจุบัน', c.logged_user)}
          ${dItem('Last Boot', c.last_boot)}
        </div>
      </div>

      <!-- HARDWARE -->
      <div class="detail-section">
        <div class="detail-section-title">🔩 ข้อมูลฮาร์ดแวร์</div>
        <div class="detail-grid">
          ${dItem('ยี่ห้อ (Manufacturer)', c.manufacturer)}
          ${dItem('รุ่น (Model)', c.model)}
          ${dItem('Serial Number', c.serial_number, true)}
          ${dItem('BIOS Version', c.bios_version)}
        </div>
      </div>

      <!-- CPU -->
      <div class="detail-section">
        <div class="detail-section-title">⚡ CPU</div>
        <div class="detail-grid">
          ${dItem('CPU Name', c.cpu_name)}
          ${dItem('Cores / Threads', c.cpu_cores && c.cpu_threads ? `${c.cpu_cores} Cores / ${c.cpu_threads} Threads` : null)}
          ${dItem('Base Speed', c.cpu_speed_mhz ? (c.cpu_speed_mhz/1000).toFixed(2)+' GHz' : null)}
        </div>
      </div>

      <!-- RAM -->
      <div class="detail-section">
        <div class="detail-section-title">🧠 Memory (RAM)</div>
        <div style="margin-bottom:12px">
          <div class="progress-wrap" style="gap:12px">
            <div class="progress-bar" style="height:8px;flex:1">
              <div class="progress-fill ${colorBar(ramPct)}" style="width:${ramPct}%"></div>
            </div>
            <span class="mono" style="font-size:13px;color:#fff">
              ${c.ram_used_gb||0} / ${c.ram_total_gb||0} GB (${ramPct}%)
            </span>
          </div>
        </div>
        <div class="detail-grid">
          ${dItem('Total RAM', c.ram_total_gb ? c.ram_total_gb+' GB' : null)}
          ${dItem('ใช้งาน', c.ram_used_gb ? c.ram_used_gb+' GB' : null)}
          ${dItem('RAM Slots', c.ram_slots)}
        </div>
      </div>

      <!-- STORAGE -->
      <div class="detail-section">
        <div class="detail-section-title">💾 Storage</div>
        <div style="margin-bottom:12px">
          <div class="progress-wrap" style="gap:12px">
            <div class="progress-bar" style="height:8px;flex:1">
              <div class="progress-fill ${colorBar(diskPct)}" style="width:${diskPct}%"></div>
            </div>
            <span class="mono" style="font-size:13px;color:#fff">
              ว่าง ${c.disk_free_gb||0} / ${c.disk_total_gb||0} GB (ใช้ ${diskPct}%)
            </span>
          </div>
        </div>
        <div class="detail-grid">
          ${dItem('Disk Total', c.disk_total_gb ? c.disk_total_gb+' GB' : null)}
          ${dItem('Disk Free', c.disk_free_gb  ? c.disk_free_gb+' GB' : null)}
          ${dItem('Disk Model', c.disk_model)}
          ${dItem('GPU', c.gpu_name)}
        </div>
      </div>

      <!-- OS -->
      <div class="detail-section">
        <div class="detail-section-title">🖥️ Operating System</div>
        <div class="detail-grid">
          ${dItem('OS Name', c.os_name)}
          ${dItem('Version', c.os_version)}
          ${dItem('Build', c.os_build)}
          ${dItem('Architecture', c.os_architecture)}
          ${dItem('Agent Version', c.agent_version)}
        </div>
      </div>

      <!-- NOTES -->
      <div class="detail-section">
        <div class="detail-section-title">📝 หมายเหตุ (IT Admin)</div>
        <textarea class="notes-input" id="notes-input" placeholder="บันทึกหมายเหตุเพิ่มเติม...">${esc(c.notes||'')}</textarea>
        <div style="margin-top:8px;display:flex;gap:8px">
          <button class="btn btn-primary" onclick="saveNotes(${c.id}, '${esc(c.location||'')}', '${esc(c.department||'')}')">💾 บันทึก</button>
        </div>
      </div>

      <!-- LOGS -->
      ${logs.length > 0 ? `
      <div class="detail-section">
        <div class="detail-section-title">📊 ประวัติ 24 ชั่วโมงล่าสุด</div>
        <div style="overflow-x:auto;max-height:220px;overflow-y:auto">
        <table class="log-table">
          <thead>
            <tr><th>เวลา</th><th>IP</th><th>RAM ที่ใช้</th><th>Disk Free</th><th>ผู้ใช้งาน</th></tr>
          </thead>
          <tbody>
            ${logs.map(l=>`
              <tr>
                <td>${l.logged_at}</td>
                <td>${l.ip_address}</td>
                <td>${l.ram_used_gb ? l.ram_used_gb+' GB' : '—'}</td>
                <td>${l.disk_free_gb ? l.disk_free_gb+' GB' : '—'}</td>
                <td>${l.logged_user || '—'}</td>
              </tr>`).join('')}
          </tbody>
        </table>
        </div>
      </div>` : ''}
    `;
  } catch(e) {
    document.getElementById('modal-body').innerHTML = `<p style="color:var(--red)">Error: ${e.message}</p>`;
  }
}

function dItem(label, val, mono=false) {
  if (val === null || val === undefined || val === '') val = '—';
  return `<div class="detail-item">
    <label>${label}</label>
    <div class="val ${mono?'mono':''}">${esc(String(val))}</div>
  </div>`;
}

async function saveNotes(id, location, department) {
  const notes = document.getElementById('notes-input').value;
  await fetch(`api/get_computer.php?id=${id}`, {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({notes, location, department})
  });
  showToast('✅ บันทึกหมายเหตุแล้ว');
}

function closeModal() {
  document.getElementById('detail-overlay').classList.remove('open');
}
function closeIfOverlay(e) {
  if (e.target === document.getElementById('detail-overlay')) closeModal();
}

// ============================================
// HELPERS
// ============================================
function colorBar(pct) {
  if (pct >= 85) return 'bar-red';
  if (pct >= 70) return 'bar-yellow';
  return 'bar-green';
}

function timeAgo(dt) {
  const diff = Math.floor((Date.now() - new Date(dt).getTime()) / 1000);
  if (diff < 60)    return `${diff}s ago`;
  if (diff < 3600)  return `${Math.floor(diff/60)}m ago`;
  if (diff < 86400) return `${Math.floor(diff/3600)}h ago`;
  return `${Math.floor(diff/86400)}d ago`;
}

function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function sortBy(key) {
  if (currentSort.key === key) currentSort.dir *= -1;
  else { currentSort.key = key; currentSort.dir = 1; }
  renderTable(allData);
}

function setStatus(status, el) {
  currentStatus = status;
  document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  fetchData();
}

function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}

function exportCSV() {
  if (!allData.length) return;
  const keys = ['hostname','department','location','ip_address','mac_address','manufacturer','model','serial_number','cpu_name','cpu_cores','cpu_threads','ram_total_gb','ram_used_gb','disk_total_gb','disk_free_gb','os_name','os_version','logged_user','last_seen'];
  const header = keys.join(',');
  const rows = allData.map(r => keys.map(k => `"${String(r[k]||'').replace(/"/g,'""')}"`).join(','));
  const csv = [header,...rows].join('\n');
  const blob = new Blob(['\uFEFF'+csv], {type:'text/csv;charset=utf-8'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = `hospital-computers-${new Date().toISOString().slice(0,10)}.csv`;
  a.click();
}

// Search debounce
let searchTimer;
document.getElementById('search-input').addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(fetchData, 350);
});

// Keyboard shortcut
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
  if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
    e.preventDefault();
    document.getElementById('search-input').focus();
  }
});

// Load hospital name from config (optional PHP echo)
<?php
require_once 'config.php';
echo "document.getElementById('hosp-name').textContent = " . json_encode(HOSPITAL_NAME) . ";";
?>

// Auto refresh
function startAutoRefresh() {
  if (refreshTimer) clearInterval(refreshTimer);
  refreshTimer = setInterval(fetchData, REFRESH_INTERVAL);
}

// INIT
fetchData();
startAutoRefresh();
</script>
</body>
</html>
