<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IT Monitor — Hospital Asset System</title>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg:      #0d1117;
  --bg2:     #161b22;
  --bg3:     #1c2230;
  --border:  #21303f;
  --border2: #2a3f54;
  --text:    #cdd9e5;
  --text2:   #768a9e;
  --text3:   #445566;
  --accent:  #1f9cf0;
  --green:   #3fb950;
  --red:     #f85149;
  --yellow:  #d29922;
  --purple:  #a371f7;
  --cyan:    #39c5cf;
  --r: 6px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:13px}
body{
  font-family:'IBM Plex Sans Thai',sans-serif;
  background:var(--bg);
  color:var(--text);
  min-height:100vh;
}

/* ── TOPBAR ── */
.topbar{
  position:sticky;top:0;z-index:100;
  background:rgba(13,17,23,0.95);
  backdrop-filter:blur(12px);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:14px;
  padding:0 20px;height:54px;
}
.brand{display:flex;align-items:center;gap:10px;flex-shrink:0}
.brand-icon{
  width:32px;height:32px;border-radius:7px;
  background:linear-gradient(135deg,#1f9cf0,#0070c0);
  display:flex;align-items:center;justify-content:center;font-size:16px;
}
.brand-text h1{font-size:14px;font-weight:700;color:#fff;line-height:1}
.brand-text p{font-size:10px;color:var(--text3);letter-spacing:.08em;text-transform:uppercase;margin-top:2px}
.topbar-search{
  flex:1;max-width:420px;position:relative;
}
.topbar-search input{
  width:100%;padding:7px 12px 7px 34px;
  background:var(--bg3);border:1px solid var(--border);
  color:var(--text);border-radius:var(--r);font-size:12px;font-family:inherit;outline:none;
  transition:border-color .2s;
}
.topbar-search input:focus{border-color:var(--accent)}
.topbar-search input::placeholder{color:var(--text3)}
.topbar-search .si{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:13px;pointer-events:none}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:10px}
.live{display:flex;align-items:center;gap:5px;font-size:10px;color:var(--green);font-weight:700;letter-spacing:.08em}
.live::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 6px var(--green);animation:blink 1.5s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-ghost{background:transparent;border:1px solid var(--border);color:var(--text2)}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#2fadf7}
.btn-danger{background:transparent;border:1px solid var(--border);color:var(--red)}
.btn-danger:hover{background:rgba(248,81,73,.1)}

/* ── MAIN ── */
main{padding:18px 20px;max-width:1800px;margin:0 auto}

/* ── STAT CARDS ── */
.stats{display:flex;gap:14px;margin-bottom:18px;flex-wrap:wrap}
.sc{
  background:var(--bg2);border:1px solid var(--border);
  border-radius:var(--r);padding:14px 18px;min-width:160px;
  display:flex;flex-direction:column;gap:4px;
}
.sc-label{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:700}
.sc-val{font-family:'JetBrains Mono',monospace;font-size:28px;font-weight:700;line-height:1;color:#fff}
.sc-val.g{color:var(--green)} .sc-val.r{color:var(--red)} .sc-val.a{color:var(--accent)}
.sc-sub{font-size:10px;color:var(--text2)}

/* ── TOOLBAR ── */
.toolbar{display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.tabs{display:flex;background:var(--bg2);border:1px solid var(--border);border-radius:var(--r);overflow:hidden}
.tab{padding:6px 14px;font-size:11px;font-weight:600;cursor:pointer;color:var(--text2);border:none;background:transparent;font-family:inherit;transition:all .15s}
.tab:hover,.tab.active{background:var(--bg3);color:var(--text)}
.tab.active{color:var(--accent)}
select.ds{
  background:var(--bg2);border:1px solid var(--border);color:var(--text2);
  padding:6px 10px;border-radius:var(--r);font-size:11px;font-family:inherit;outline:none;cursor:pointer
}
select.ds:focus{border-color:var(--accent)}
.ml{margin-left:auto;display:flex;align-items:center;gap:8px}
.cnt{font-size:10px;color:var(--text3);font-family:'JetBrains Mono',monospace}

/* ── TABLE ── */
.tw{
  background:var(--bg2);border:1px solid var(--border);
  border-radius:var(--r);overflow:hidden;
}
table{width:100%;border-collapse:collapse;font-size:12px}
thead th{
  background:var(--bg3);
  padding:9px 12px;text-align:left;
  font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;
  color:var(--text3);border-bottom:1px solid var(--border);white-space:nowrap;
}
thead th.sortable{cursor:pointer;user-select:none}
thead th.sortable:hover{color:var(--accent)}
thead th .si2{opacity:.35;margin-left:3px}

tbody tr{border-bottom:1px solid var(--border);transition:background .1s;cursor:pointer}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:rgba(31,156,240,.04)}

td{padding:10px 12px;vertical-align:top}

/* row number */
.rn{
  font-family:'JetBrains Mono',monospace;font-size:11px;
  color:var(--text3);text-align:center;width:36px;
}

/* hostname cell */
.hn-wrap{display:flex;align-items:flex-start;gap:7px}
.dot{
  width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:3px;
}
.dot.on{background:var(--green);box-shadow:0 0 5px var(--green)}
.dot.off{background:var(--red)}
.hn{font-weight:700;color:#fff;font-size:13px;line-height:1.2}
.ip{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--text3);margin-top:2px}

/* mainboard cell */
.mb-mfr{color:var(--text);font-weight:500;line-height:1.3}
.mb-sn{font-size:10px;margin-top:3px}
.mb-sn span{color:var(--text3)}
.mb-sn a,.mb-sn .snval{color:var(--cyan);font-family:'JetBrains Mono',monospace;font-size:10px}

/* cpu cell */
.cpu-id-link{
  font-family:'JetBrains Mono',monospace;font-size:10px;
  color:var(--accent);display:block;margin-bottom:3px;word-break:break-all;
}
.cpu-name{color:var(--text2);font-size:11px;line-height:1.3}
.cpu-cores{font-size:10px;color:var(--text3);margin-top:2px}

/* RAM cell */
.ram-badge{
  display:inline-flex;align-items:center;gap:6px;
  background:var(--bg3);border:1px solid var(--border);
  border-radius:20px;padding:3px 10px;
  font-size:11px;font-weight:600;color:var(--text);white-space:nowrap;
}
.ram-type{
  font-family:'JetBrains Mono',monospace;font-size:9px;
  background:rgba(163,113,247,.15);color:var(--purple);
  border-radius:3px;padding:1px 5px;font-weight:700;
}

/* Monitor cell */
.mon-list{display:flex;flex-direction:column;gap:4px}
.mon-item{display:flex;align-items:baseline;gap:5px;font-size:11px}
.mon-icon{font-size:11px;flex-shrink:0}
.mon-name{color:var(--text2);font-weight:500}
.mon-sn{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--text3)}
.no-mon{font-size:10px;color:var(--text3);font-style:italic}

/* last update cell */
.lu{font-size:11px;color:var(--text2);white-space:nowrap}
.lu-ago{font-size:10px;color:var(--text3);margin-top:2px}

/* status col */
.badge{
  display:inline-flex;align-items:center;gap:4px;
  padding:2px 8px;border-radius:20px;
  font-size:10px;font-weight:700;letter-spacing:.04em;
}
.badge.on{background:rgba(63,185,80,.12);color:var(--green)}
.badge.off{background:rgba(248,81,73,.10);color:var(--red)}

/* empty / loading */
.es{text-align:center;padding:50px 20px;color:var(--text3)}
.es .ei{font-size:32px;margin-bottom:10px;opacity:.4}
.es p{font-size:12px}
.ld{display:flex;align-items:center;justify-content:center;padding:50px;gap:10px;color:var(--text3)}
.sp{width:18px;height:18px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── MODAL ── */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);z-index:200;display:none;align-items:center;justify-content:center;padding:16px}
.overlay.open{display:flex}
.modal{background:var(--bg2);border:1px solid var(--border2);border-radius:12px;width:100%;max-width:800px;max-height:92vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,.6);animation:su .2s ease}
@keyframes su{from{transform:translateY(16px);opacity:0}to{transform:translateY(0);opacity:1}}
.mh{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border)}
.mt{font-size:16px;font-weight:700;color:#fff}
.ms{font-size:11px;color:var(--text3);margin-top:2px}
.mc{background:transparent;border:none;color:var(--text3);font-size:18px;cursor:pointer;padding:3px 7px;border-radius:5px;transition:.15s}
.mc:hover{background:var(--bg3);color:var(--text)}
.mb{padding:22px}
.ds2{margin-bottom:20px}
.dst{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--accent);margin-bottom:10px;padding-bottom:7px;border-bottom:1px solid var(--border)}
.dg{display:grid;grid-template-columns:repeat(2,1fr);gap:8px}
.di{background:var(--bg3);border:1px solid var(--border);border-radius:7px;padding:9px 12px}
.di label{display:block;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:3px}
.di .v{font-size:13px;color:#fff;font-weight:500}
.di .v.mo{font-family:'JetBrains Mono',monospace;font-size:12px}
.pb-wrap{display:flex;align-items:center;gap:10px;margin-bottom:10px}
.pb{flex:1;height:7px;background:var(--bg3);border-radius:4px;overflow:hidden}
.pf{height:100%;border-radius:4px}
.bg{background:var(--green)} .by{background:var(--yellow)} .br{background:var(--red)}
.pl{font-size:12px;font-family:'JetBrains Mono',monospace;color:#fff}
textarea.ni{width:100%;background:var(--bg3);border:1px solid var(--border);color:var(--text);border-radius:7px;padding:9px 12px;font-size:12px;font-family:inherit;resize:vertical;outline:none;min-height:72px}
textarea.ni:focus{border-color:var(--accent)}
.lt{width:100%;border-collapse:collapse;font-size:11px}
.lt th{padding:5px 8px;text-align:left;color:var(--text3);border-bottom:1px solid var(--border);font-size:9px;text-transform:uppercase;letter-spacing:.08em}
.lt td{padding:6px 8px;border-bottom:1px solid var(--border);color:var(--text2);font-family:'JetBrains Mono',monospace}
.lt tr:last-child td{border-bottom:none}

/* toast */
.toast{position:fixed;bottom:20px;right:20px;background:var(--bg3);border:1px solid var(--green);color:var(--green);padding:9px 16px;border-radius:7px;font-size:12px;z-index:500;display:none}
.toast.show{display:block}

::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:3px}
</style>
</head>
<body>

<header class="topbar">
  <div class="brand">
    <div class="brand-icon">🏥</div>
    <div class="brand-text">
      <h1 id="hosp-name">Hospital IT Monitor</h1>
      <p>IT Asset Monitor System</p>
    </div>
  </div>

  <div class="topbar-search">
    <span class="si">🔍</span>
    <input type="text" id="search-input" placeholder="ค้นหา Hostname, IP, S/N, CPU ID, รุ่น..." autocomplete="off">
  </div>

  <div class="topbar-right">
    <div class="live">LIVE</div>
    <button class="btn btn-ghost" onclick="exportCSV()">⬇ CSV</button>
    <button class="btn btn-primary" onclick="fetchData()">↻ Refresh</button>
  </div>
</header>

<main>
  <!-- STATS -->
  <div class="stats">
    <div class="sc">
      <div class="sc-label">เครื่องทั้งหมดในระบบ</div>
      <div class="sc-val a" id="st-total">—</div>
    </div>
    <div class="sc">
      <div class="sc-label">ออนไลน์ (10 นาทีล่าสุด)</div>
      <div class="sc-val g" id="st-online">—</div>
    </div>
    <div class="sc">
      <div class="sc-label">ออฟไลน์</div>
      <div class="sc-val r" id="st-offline">—</div>
    </div>
    <div class="sc">
      <div class="sc-label">แผนก / Ward</div>
      <div class="sc-val" id="st-dept">—</div>
    </div>
  </div>

  <!-- TOOLBAR -->
  <div class="toolbar">
    <div class="tabs">
      <button class="tab active" onclick="setStatus('all',this)">ทั้งหมด</button>
      <button class="tab" onclick="setStatus('online',this)">🟢 Online</button>
      <button class="tab" onclick="setStatus('offline',this)">🔴 Offline</button>
    </div>
    <select class="ds" id="dept-filter" onchange="fetchData()">
      <option value="">— ทุกแผนก —</option>
    </select>
    <div class="ml">
      <span class="cnt" id="cnt-badge"></span>
    </div>
  </div>

  <!-- TABLE -->
  <div class="tw">
    <div class="ld" id="loading"><div class="sp"></div><span>กำลังโหลด...</span></div>
    <div id="tc" style="display:none">
      <table>
        <thead>
          <tr>
            <th style="width:36px;text-align:center">#</th>
            <th class="sortable" onclick="sortBy('hostname')">HOSTNAME / IP <span class="si2">↕</span></th>
            <th class="sortable" onclick="sortBy('manufacturer')">MAINBOARD &amp; SERIAL <span class="si2">↕</span></th>
            <th class="sortable" onclick="sortBy('cpu_name')">CPU / CORES <span class="si2">↕</span></th>
            <th class="sortable" onclick="sortBy('ram_total_gb')">RAM (SIZE/TYPE) <span class="si2">↕</span></th>
            <th>MONITOR(S) / SN</th>
            <th class="sortable" onclick="sortBy('last_seen')">LAST UPDATE <span class="si2">↕</span></th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
      <div class="es" id="es" style="display:none"><div class="ei">💻</div><p>ไม่พบข้อมูล</p></div>
    </div>
  </div>
</main>

<!-- MODAL DETAIL -->
<div class="overlay" id="ov" onclick="closeOv(event)">
  <div class="modal">
    <div class="mh">
      <div><div class="mt" id="m-hn">—</div><div class="ms" id="m-sl">—</div></div>
      <button class="mc" onclick="closeModal()">✕</button>
    </div>
    <div class="mb" id="m-body"><div class="ld"><div class="sp"></div></div></div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
let allData = [];
let currentSort = {key:'last_seen', dir:-1};
let currentStatus = 'all';
const REFRESH = 30000;
let timer;

async function fetchData() {
  const s = document.getElementById('search-input').value.trim();
  const d = document.getElementById('dept-filter').value;
  const p = new URLSearchParams({search:s, department:d, status:currentStatus});
  document.getElementById('loading').style.display = 'flex';
  document.getElementById('tc').style.display = 'none';
  try {
    const r = await fetch('api/get_computers.php?' + p);
    const j = await r.json();
    allData = j.computers || [];

    document.getElementById('st-total').textContent   = j.stats?.total || 0;
    document.getElementById('st-online').textContent  = j.stats?.online || 0;
    document.getElementById('st-offline').textContent = (j.stats?.total||0) - (j.stats?.online||0);
    document.getElementById('st-dept').textContent    = j.stats?.departments || 0;

    const ds = document.getElementById('dept-filter');
    const cv = ds.value;
    while (ds.options.length > 1) ds.remove(1);
    (j.departments||[]).forEach(dep => {
      const o = new Option(dep, dep);
      if (dep === cv) o.selected = true;
      ds.add(o);
    });

    render(allData);
    document.getElementById('loading').style.display = 'none';
    document.getElementById('tc').style.display = 'block';
  } catch(e) {
    document.getElementById('loading').innerHTML = '<span style="color:var(--red)">⚠ เชื่อมต่อไม่ได้</span>';
  }
}

function render(data) {
  data = [...data].sort((a,b) => {
    let va = a[currentSort.key] ?? '', vb = b[currentSort.key] ?? '';
    if (typeof va === 'number') return currentSort.dir * (va - vb);
    return currentSort.dir * String(va).localeCompare(String(vb));
  });

  const tbody = document.getElementById('tbody');
  const es    = document.getElementById('es');
  document.getElementById('cnt-badge').textContent = data.length + ' เครื่อง';

  if (!data.length) { tbody.innerHTML = ''; es.style.display = 'block'; return; }
  es.style.display = 'none';

  tbody.innerHTML = data.map((c, idx) => {
    const online = !!c.is_online;

    // Monitors
    let monHTML = `<span class="no-mon">- ไม่มีข้อมูล -</span>`;
    if (c.monitors) {
      try {
        const mons = JSON.parse(c.monitors);
        if (mons.length) {
          monHTML = `<div class="mon-list">` + mons.map(m =>
            `<div class="mon-item">
              <span class="mon-icon">🖥</span>
              <span class="mon-name">${esc(m.name||'Unknown')}</span>
              <span class="mon-sn">(SN: ${esc(m.sn||'—')})</span>
            </div>`
          ).join('') + `</div>`;
        }
      } catch(e) {}
    }

    // RAM badge
    const ramLabel = c.ram_total_gb
      ? `${c.cpu_cores||''}${c.cpu_cores ? ' Cores / ' : ''}${c.cpu_threads||''}${c.cpu_threads ? ' Threads ' : ''}${c.ram_total_gb} GB`
      : '—';

    // CPU ID
    const cpuIdHTML = c.cpu_id
      ? `<span class="cpu-id-link" title="CPU ID">${esc(c.cpu_id)}</span>`
      : '';

    // Last seen format
    const ls = c.last_seen || '';
    const ago = ls ? timeAgo(ls) : '—';

    return `<tr onclick="openDetail(${c.id})">
      <td class="rn">${idx + 1}</td>
      <td>
        <div class="hn-wrap">
          <div class="dot ${online?'on':'off'}"></div>
          <div>
            <div class="hn">${esc(c.hostname)}</div>
            <div class="ip">${esc(c.ip_address)}</div>
          </div>
        </div>
      </td>
      <td>
        <div class="mb-mfr">${esc(c.manufacturer||'—')}</div>
        <div class="mb-sn">
          <span>SN: </span>
          <span class="snval">${esc(c.serial_number||'—')}</span>
        </div>
      </td>
      <td>
        ${cpuIdHTML}
        <div class="cpu-name">${esc(c.cpu_name||'—')}</div>
        <div class="cpu-cores">${c.cpu_cores ? c.cpu_cores+' Cores / '+c.cpu_threads+' Threads' : ''}</div>
      </td>
      <td>
        <span class="ram-badge">
          ${c.cpu_cores?c.cpu_cores+' Cores / '+c.cpu_threads+' Threads ':''}<strong>${c.ram_total_gb||'—'} GB</strong>
          ${c.ram_type ? `<span class="ram-type">${esc(c.ram_type)}</span>` : ''}
        </span>
      </td>
      <td>${monHTML}</td>
      <td>
        <div class="lu">${esc(c.logged_user||'—')}</div>
        <div class="lu-ago">${ls ? ls.substring(0,16).replace('T',' ') : '—'}</div>
      </td>
    </tr>`;
  }).join('');
}

// ── DETAIL MODAL ──
async function openDetail(id) {
  document.getElementById('ov').classList.add('open');
  document.getElementById('m-hn').textContent = 'Loading...';
  document.getElementById('m-sl').textContent = '';
  document.getElementById('m-body').innerHTML = '<div class="ld"><div class="sp"></div></div>';
  try {
    const r = await fetch('api/get_computer.php?id=' + id);
    const j = await r.json();
    const c = j.computer, logs = j.logs||[];
    document.getElementById('m-hn').textContent = c.hostname;
    document.getElementById('m-sl').innerHTML =
      `${c.is_online?'🟢 Online':'🔴 Offline'} &nbsp;·&nbsp; ${c.ip_address} &nbsp;·&nbsp; ${c.department||'ไม่ระบุ'} &nbsp;·&nbsp; Last seen: ${c.last_seen}`;

    const rp = c.ram_total_gb>0 ? ((c.ram_used_gb/c.ram_total_gb)*100).toFixed(0) : 0;
    const dp = c.disk_total_gb>0 ? ((1-c.disk_free_gb/c.disk_total_gb)*100).toFixed(0) : 0;

    // Monitors detail
    let monDetail = '<div class="di" style="grid-column:span 2"><label>Monitor(s)</label><div class="v">';
    if (c.monitors) {
      try {
        const mons = JSON.parse(c.monitors);
        monDetail += mons.map(m => `🖥 <strong>${esc(m.name)}</strong> <span style="color:var(--text3);font-family:JetBrains Mono,monospace;font-size:11px">SN: ${esc(m.sn)}</span>`).join('<br>');
      } catch(e) { monDetail += '—'; }
    } else { monDetail += '<span style="color:var(--text3)">ไม่มีข้อมูล</span>'; }
    monDetail += '</div></div>';

    document.getElementById('m-body').innerHTML = `
      <div class="ds2">
        <div class="dst">📍 ตำแหน่ง / Network</div>
        <div class="dg">
          ${di('Hostname', c.hostname, true)} ${di('IP Address', c.ip_address, true)}
          ${di('MAC Address', c.mac_address)} ${di('Domain', c.domain)}
          ${di('Location', c.location)} ${di('แผนก', c.department)}
          ${di('ผู้ใช้งาน', c.logged_user)} ${di('Last Boot', c.last_boot)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">🔩 Hardware / Mainboard</div>
        <div class="dg">
          ${di('Manufacturer', c.manufacturer)} ${di('Model', c.model)}
          ${di('Serial Number', c.serial_number, true)} ${di('BIOS', c.bios_version)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">⚡ CPU</div>
        <div class="dg">
          ${di('CPU Name', c.cpu_name)}
          ${di('CPU ID', c.cpu_id, true)}
          ${di('Cores / Threads', c.cpu_cores?c.cpu_cores+'C / '+c.cpu_threads+'T':null)}
          ${di('Speed', c.cpu_speed_mhz?(c.cpu_speed_mhz/1000).toFixed(2)+' GHz':null)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">🧠 RAM</div>
        <div class="pb-wrap">
          <div class="pb"><div class="pf ${cb(rp)}" style="width:${rp}%"></div></div>
          <span class="pl">${c.ram_used_gb||0} / ${c.ram_total_gb||0} GB (${rp}%)</span>
        </div>
        <div class="dg">
          ${di('Total', c.ram_total_gb?c.ram_total_gb+' GB':null)}
          ${di('ใช้งาน', c.ram_used_gb?c.ram_used_gb+' GB':null)}
          ${di('Type', c.ram_type)} ${di('Slots', c.ram_slots)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">💾 Storage &amp; GPU</div>
        <div class="pb-wrap">
          <div class="pb"><div class="pf ${cb(dp)}" style="width:${dp}%"></div></div>
          <span class="pl">ว่าง ${c.disk_free_gb||0} / ${c.disk_total_gb||0} GB (ใช้ ${dp}%)</span>
        </div>
        <div class="dg">
          ${di('Disk Total', c.disk_total_gb?c.disk_total_gb+' GB':null)}
          ${di('Disk Free', c.disk_free_gb?c.disk_free_gb+' GB':null)}
          ${di('Disk Model', c.disk_model)} ${di('GPU', c.gpu_name)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">🖥️ Monitor(s)</div>
        <div class="dg">${monDetail}</div>
      </div>
      <div class="ds2">
        <div class="dst">🖥 OS</div>
        <div class="dg">
          ${di('OS', c.os_name)} ${di('Version', c.os_version)}
          ${di('Build', c.os_build)} ${di('Arch', c.os_architecture)}
        </div>
      </div>
      <div class="ds2">
        <div class="dst">📝 หมายเหตุ</div>
        <textarea class="ni" id="ni" placeholder="บันทึกหมายเหตุ...">${esc(c.notes||'')}</textarea>
        <div style="margin-top:6px">
          <button class="btn btn-primary" onclick="saveNotes(${c.id},'${esc(c.location||'')}','${esc(c.department||'')}')">💾 บันทึก</button>
        </div>
      </div>
      ${logs.length?`
      <div class="ds2">
        <div class="dst">📊 ประวัติ 24 ชั่วโมง</div>
        <div style="overflow-x:auto;max-height:200px;overflow-y:auto">
        <table class="lt">
          <thead><tr><th>เวลา</th><th>IP</th><th>RAM ใช้</th><th>Disk Free</th><th>ผู้ใช้</th></tr></thead>
          <tbody>${logs.map(l=>`<tr>
            <td>${l.logged_at}</td><td>${l.ip_address}</td>
            <td>${l.ram_used_gb?l.ram_used_gb+' GB':'—'}</td>
            <td>${l.disk_free_gb?l.disk_free_gb+' GB':'—'}</td>
            <td>${l.logged_user||'—'}</td>
          </tr>`).join('')}</tbody>
        </table></div>
      </div>`:''}
    `;
  } catch(e) {
    document.getElementById('m-body').innerHTML = `<p style="color:var(--red)">${e.message}</p>`;
  }
}

function di(label, val, mono=false) {
  if (val===null||val===undefined||val==='') val='—';
  return `<div class="di"><label>${label}</label><div class="v ${mono?'mo':''}">${esc(String(val))}</div></div>`;
}
function cb(p) { return p>=85?'br':p>=70?'by':'bg'; }
function closeModal() { document.getElementById('ov').classList.remove('open'); }
function closeOv(e) { if (e.target===document.getElementById('ov')) closeModal(); }

async function saveNotes(id, loc, dept) {
  const notes = document.getElementById('ni').value;
  await fetch('api/get_computer.php?id='+id, {
    method:'POST', headers:{'Content-Type':'application/json'},
    body:JSON.stringify({notes, location:loc, department:dept})
  });
  showToast('✅ บันทึกแล้ว');
}

// ── helpers ──
function sortBy(key) {
  currentSort = {key, dir: currentSort.key===key ? currentSort.dir*-1 : 1};
  render(allData);
}
function setStatus(s, el) {
  currentStatus = s;
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  fetchData();
}
function timeAgo(dt) {
  const d = Math.floor((Date.now()-new Date(dt).getTime())/1000);
  if (d<60) return d+'s ago';
  if (d<3600) return Math.floor(d/60)+'m ago';
  if (d<86400) return Math.floor(d/3600)+'h ago';
  return Math.floor(d/86400)+'d ago';
}
function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function showToast(msg) {
  const t=document.getElementById('toast'); t.textContent=msg; t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),2500);
}
function exportCSV() {
  if (!allData.length) return;
  const keys=['hostname','department','location','ip_address','mac_address','manufacturer','model','serial_number','cpu_name','cpu_id','cpu_cores','cpu_threads','ram_total_gb','ram_used_gb','ram_type','disk_total_gb','disk_free_gb','os_name','os_version','logged_user','last_seen'];
  const csv = [keys.join(','), ...allData.map(r=>keys.map(k=>`"${String(r[k]||'').replace(/"/g,'""')}"`).join(','))].join('\n');
  const a=document.createElement('a');
  a.href=URL.createObjectURL(new Blob(['\uFEFF'+csv],{type:'text/csv;charset=utf-8'}));
  a.download='hospital-it-'+new Date().toISOString().slice(0,10)+'.csv';
  a.click();
}

// search debounce
let st;
document.getElementById('search-input').addEventListener('input',()=>{ clearTimeout(st); st=setTimeout(fetchData,350); });
document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeModal(); });

<?php
require_once 'config.php';
echo "document.getElementById('hosp-name').textContent=" . json_encode(HOSPITAL_NAME) . ";";
?>

function autoRefresh() {
  if (timer) clearInterval(timer);
  timer = setInterval(fetchData, REFRESH);
}
fetchData();
autoRefresh();
</script>
</body>
</html>
