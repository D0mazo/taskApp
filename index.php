<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Task Calendar</title>
<style>
/* ── RESET & BASE ─────────────────────────────── */
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;color:#1a1a2e;min-height:100vh;}

/* ── LOGIN PAGE ───────────────────────────────── */
#login-page{display:flex;align-items:center;justify-content:center;min-height:100vh;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);}
.login-box{background:#fff;border-radius:16px;padding:40px 36px;width:360px;box-shadow:0 20px 60px rgba(0,0,0,0.3);}
.login-box h1{font-size:22px;font-weight:600;margin-bottom:6px;color:#1a1a2e;}
.login-box p{font-size:13px;color:#888;margin-bottom:28px;}
.tabs{display:flex;gap:0;margin-bottom:24px;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;}
.tab-btn{flex:1;padding:9px;font-size:13px;border:none;background:#f8f8f8;cursor:pointer;font-weight:500;color:#888;transition:.15s;}
.tab-btn.active{background:#378ADD;color:#fff;}
.form-group{margin-bottom:16px;}
.form-group label{display:block;font-size:12px;font-weight:500;color:#555;margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.form-group input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;transition:.15s;}
.form-group input:focus{outline:none;border-color:#378ADD;box-shadow:0 0 0 3px rgba(55,138,221,.12);}
.btn-primary{width:100%;padding:11px;background:#378ADD;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:.15s;}
.btn-primary:hover{background:#185FA5;}
.msg{font-size:13px;padding:9px 12px;border-radius:6px;margin-bottom:14px;display:none;}
.msg.error{background:#FCEBEB;color:#A32D2D;}
.msg.success{background:#EAF3DE;color:#3B6D11;}

/* ── APP SHELL ────────────────────────────────── */
#app-page{display:none;}
.topbar{background:#1a1a2e;color:#fff;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.topbar-left{display:flex;align-items:center;gap:24px;}
.topbar h2{font-size:16px;font-weight:600;letter-spacing:.02em;}
.nav-tabs{display:flex;gap:4px;}
.nav-tab{padding:6px 14px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;color:rgba(255,255,255,.6);border:none;background:none;transition:.15s;}
.nav-tab:hover{color:#fff;background:rgba(255,255,255,.08);}
.nav-tab.active{color:#fff;background:rgba(55,138,221,.4);}
.topbar-right{display:flex;align-items:center;gap:12px;}
.user-badge{font-size:13px;color:rgba(255,255,255,.7);}
.logout-btn{font-size:12px;padding:5px 12px;border:1px solid rgba(255,255,255,.2);border-radius:6px;background:none;color:rgba(255,255,255,.7);cursor:pointer;}
.logout-btn:hover{background:rgba(255,255,255,.1);color:#fff;}
.content{padding:24px;max-width:1300px;margin:0 auto;}

/* ── PANELS (views) ───────────────────────────── */
.view{display:none;}
.view.active{display:block;}

/* ── CALENDAR VIEW ────────────────────────────── */
.cal-layout{display:grid;grid-template-columns:1fr 320px;gap:20px;}
.cal-card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.cal-title{font-size:17px;font-weight:600;}
.nav-btn{background:none;border:1px solid #ddd;border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;}
.nav-btn:hover{background:#f5f5f5;}
.weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:4px;}
.weekday{text-align:center;font-size:11px;color:#aaa;padding:4px 0;font-weight:600;text-transform:uppercase;letter-spacing:.04em;}
.days-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}
.day{min-height:80px;border-radius:8px;border:1px solid #f0f0f0;padding:6px;cursor:pointer;background:#fff;transition:.15s;position:relative;}
.day:hover{border-color:#bbb;background:#fafcff;}
.day.other-month{background:#fafafa;}
.day.other-month .dnum{color:#ccc;}
.day.today{border-color:#378ADD;background:#f0f7ff;}
.day.selected{border-color:#534AB7;border-width:2px;background:#f6f4ff;}
.dnum{font-size:11px;font-weight:600;margin-bottom:4px;color:#333;}
.today-dot{background:#378ADD;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;}
.tdot{font-size:10px;padding:2px 5px;border-radius:3px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.tdot.low{background:#EAF3DE;color:#3B6D11;}
.tdot.medium{background:#FAEEDA;color:#854F0B;}
.tdot.high{background:#FCEBEB;color:#A32D2D;}
.tdot.done{opacity:.45;text-decoration:line-through;}

/* ── SIDEBAR ──────────────────────────────────── */
.sidebar{display:flex;flex-direction:column;gap:14px;}
.panel{background:#fff;border-radius:14px;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.panel-title{font-size:11px;font-weight:700;color:#aaa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;}
.sel-label{font-size:15px;font-weight:600;margin-bottom:12px;color:#1a1a2e;}
.task-item{display:flex;align-items:flex-start;gap:8px;padding:7px 0;border-bottom:1px solid #f5f5f5;}
.task-item:last-child{border-bottom:none;}
.chk{width:15px;height:15px;border-radius:4px;border:1.5px solid #ccc;cursor:pointer;flex-shrink:0;margin-top:2px;display:flex;align-items:center;justify-content:center;font-size:9px;transition:.15s;}
.chk.checked{background:#378ADD;border-color:#378ADD;color:#fff;}
.task-body{flex:1;min-width:0;}
.task-title-text{font-size:13px;font-weight:500;color:#1a1a2e;line-height:1.4;}
.task-title-text.done{text-decoration:line-through;color:#aaa;}
.task-meta{display:flex;align-items:center;gap:6px;margin-top:3px;flex-wrap:wrap;}
.badge{font-size:10px;padding:2px 7px;border-radius:4px;font-weight:500;}
.badge-low{background:#EAF3DE;color:#3B6D11;}
.badge-medium{background:#FAEEDA;color:#854F0B;}
.badge-high{background:#FCEBEB;color:#A32D2D;}
.badge-done{background:#e8f5e9;color:#2e7d32;}
.progress-mini{height:4px;background:#f0f0f0;border-radius:2px;margin-top:4px;overflow:hidden;}
.progress-mini-fill{height:100%;border-radius:2px;background:#378ADD;transition:.3s;}
.task-actions{display:flex;gap:4px;flex-shrink:0;}
.icon-btn{background:none;border:none;cursor:pointer;font-size:14px;color:#bbb;padding:2px 4px;border-radius:4px;transition:.15s;}
.icon-btn:hover{color:#378ADD;background:#f0f7ff;}
.icon-btn.del:hover{color:#E24B4A;background:#FCEBEB;}
.empty-msg{font-size:13px;color:#bbb;text-align:center;padding:20px 0;}

/* ── ADD/EDIT FORM ────────────────────────────── */
.add-form{display:flex;flex-direction:column;gap:8px;}
.add-form input,.add-form select,.add-form textarea{width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:8px;font-size:13px;color:#1a1a2e;background:#fff;font-family:inherit;}
.add-form textarea{resize:vertical;min-height:60px;}
.add-form input:focus,.add-form select:focus,.add-form textarea:focus{outline:none;border-color:#378ADD;}
.btn-add{background:#378ADD;color:#fff;border:none;border-radius:8px;padding:9px;font-size:13px;cursor:pointer;font-weight:600;}
.btn-add:hover{background:#185FA5;}
.progress-row{display:flex;align-items:center;gap:8px;}
.progress-row input[type=range]{flex:1;}
.progress-val{font-size:12px;font-weight:600;color:#378ADD;min-width:30px;}

/* ── UPCOMING VIEW ────────────────────────────── */
.upcoming-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;}
.task-card{background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid #ddd;transition:.2s;}
.task-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.1);}
.task-card.prio-low{border-left-color:#63c55a;}
.task-card.prio-medium{border-left-color:#f0a500;}
.task-card.prio-high{border-left-color:#E24B4A;}
.task-card.overdue{border-left-color:#A32D2D;background:#fffbfb;}
.card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;}
.card-title{font-size:14px;font-weight:600;color:#1a1a2e;flex:1;margin-right:8px;}
.days-badge{font-size:11px;padding:3px 8px;border-radius:6px;font-weight:700;white-space:nowrap;flex-shrink:0;}
.days-green{background:#EAF3DE;color:#3B6D11;}
.days-yellow{background:#FAEEDA;color:#854F0B;}
.days-red{background:#FCEBEB;color:#A32D2D;}
.days-overdue{background:#A32D2D;color:#fff;}
.card-desc{font-size:12px;color:#888;margin-bottom:10px;line-height:1.5;}
.progress-bar{height:6px;background:#f0f0f0;border-radius:3px;overflow:hidden;margin-bottom:6px;}
.progress-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#378ADD,#534AB7);transition:.3s;}
.card-bottom{display:flex;justify-content:space-between;align-items:center;}
.prog-label{font-size:11px;color:#aaa;}
.prog-pct{font-size:12px;font-weight:700;color:#378ADD;}
.card-actions{display:flex;gap:6px;}

/* ── EDIT MODAL ───────────────────────────────── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.open{display:flex;}
.modal{background:#fff;border-radius:16px;padding:28px;width:500px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);}
.modal h3{font-size:17px;font-weight:600;margin-bottom:20px;}
.modal-form{display:flex;flex-direction:column;gap:12px;}
.modal-form label{font-size:12px;font-weight:600;color:#666;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;display:block;}
.modal-form input,.modal-form select,.modal-form textarea{width:100%;padding:9px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;font-family:inherit;}
.modal-form textarea{min-height:80px;resize:vertical;}
.modal-form input:focus,.modal-form select:focus,.modal-form textarea:focus{outline:none;border-color:#378ADD;}
.modal-btns{display:flex;gap:10px;margin-top:8px;}
.btn-save{flex:1;padding:10px;background:#378ADD;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;}
.btn-save:hover{background:#185FA5;}
.btn-cancel{padding:10px 20px;background:#f5f5f5;color:#555;border:none;border-radius:8px;font-size:14px;cursor:pointer;}
.btn-cancel:hover{background:#eee;}

/* ── STATS BAR ────────────────────────────────── */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
.stat-card{background:#fff;border-radius:12px;padding:14px 16px;box-shadow:0 2px 10px rgba(0,0,0,.06);text-align:center;}
.stat-num{font-size:26px;font-weight:700;color:#1a1a2e;}
.stat-label{font-size:11px;color:#aaa;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}
.stat-card.stat-blue .stat-num{color:#378ADD;}
.stat-card.stat-orange .stat-num{color:#f0a500;}
.stat-card.stat-red .stat-num{color:#E24B4A;}
.stat-card.stat-green .stat-num{color:#63c55a;}

/* ── RESPONSIVE ───────────────────────────────── */
@media(max-width:768px){
  .cal-layout{grid-template-columns:1fr;}
  .stats-row{grid-template-columns:repeat(2,1fr);}
  .upcoming-grid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<!-- ══════════════════ LOGIN PAGE ══════════════════ -->
<div id="login-page">
  <div class="login-box">
    <h1>📋 Task Calendar</h1>
    <p>Manage your tasks with a calendar view</p>
    <div class="tabs">
      <button class="tab-btn active" onclick="switchTab('login')">Login</button>
      <button class="tab-btn" onclick="switchTab('register')">Register</button>
    </div>
    <div id="auth-msg" class="msg"></div>
    <div id="login-form">
      <div class="form-group"><label>Username</label><input type="text" id="l-user" placeholder="Your username" /></div>
      <div class="form-group"><label>Password</label><input type="password" id="l-pass" placeholder="Your password" onkeydown="if(event.key==='Enter')doLogin()" /></div>
      <button class="btn-primary" onclick="doLogin()">Login →</button>
    </div>
    <div id="register-form" style="display:none">
      <div class="form-group"><label>Username</label><input type="text" id="r-user" placeholder="Choose a username" /></div>
      <div class="form-group"><label>Password</label><input type="password" id="r-pass" placeholder="Choose a password (min 4 chars)" /></div>
      <button class="btn-primary" onclick="doRegister()">Create account →</button>
    </div>
  </div>
</div>

<!-- ══════════════════ APP PAGE ══════════════════ -->
<div id="app-page">
  <div class="topbar">
    <div class="topbar-left">
      <h2>📋 Task Calendar</h2>
      <div class="nav-tabs">
        <button class="nav-tab active" onclick="showView('calendar')">📅 Calendar</button>
        <button class="nav-tab" onclick="showView('upcoming')">🔔 Upcoming</button>
      </div>
    </div>
    <div class="topbar-right">
      <span class="user-badge" id="user-label"></span>
      <button class="logout-btn" onclick="doLogout()">Logout</button>
    </div>
  </div>

  <div class="content">

    <!-- ── CALENDAR VIEW ── -->
    <div class="view active" id="view-calendar">
      <div class="cal-layout">
        <div class="cal-card">
          <div class="cal-header">
            <button class="nav-btn" onclick="changeMonth(-1)">← Prev</button>
            <span class="cal-title" id="month-label"></span>
            <button class="nav-btn" onclick="changeMonth(1)">Next →</button>
          </div>
          <div class="weekdays">
            <div class="weekday">Sun</div><div class="weekday">Mon</div><div class="weekday">Tue</div>
            <div class="weekday">Wed</div><div class="weekday">Thu</div><div class="weekday">Fri</div><div class="weekday">Sat</div>
          </div>
          <div class="days-grid" id="days-grid"></div>
        </div>
        <div class="sidebar">
          <div class="panel">
            <div class="sel-label" id="sel-date-label">Select a day</div>
            <div id="task-list"><div class="empty-msg">Click a day to see tasks</div></div>
          </div>
          <div class="panel">
            <div class="panel-title">Add task to selected day</div>
            <div class="add-form">
              <input type="text" id="task-input" placeholder="Task title..." />
              <input type="text" id="task-desc" placeholder="Description (optional)" />
              <select id="priority-select">
                <option value="low">🟢 Low priority</option>
                <option value="medium" selected>🟡 Medium priority</option>
                <option value="high">🔴 High priority</option>
              </select>
              <button class="btn-add" onclick="addTask()">+ Add Task</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── UPCOMING VIEW ── -->
    <div class="view" id="view-upcoming">
      <div class="stats-row" id="stats-row"></div>
      <div class="upcoming-grid" id="upcoming-grid"></div>
    </div>

  </div>
</div>

<!-- ══════════════════ EDIT MODAL ══════════════════ -->
<div class="modal-overlay" id="modal">
  <div class="modal">
    <h3>Edit Task</h3>
    <div class="modal-form">
      <input type="hidden" id="edit-id" />
      <div><label>Title</label><input type="text" id="edit-title" /></div>
      <div><label>Description</label><textarea id="edit-desc"></textarea></div>
      <div><label>Due Date</label><input type="date" id="edit-due" /></div>
      <div><label>Priority</label>
        <select id="edit-priority">
          <option value="low">🟢 Low</option>
          <option value="medium">🟡 Medium</option>
          <option value="high">🔴 High</option>
        </select>
      </div>
      <div>
        <label>Progress: <span id="edit-prog-val" style="color:#378ADD;font-weight:700;">0%</span></label>
        <div class="progress-row">
          <input type="range" id="edit-progress" min="0" max="100" value="0" oninput="document.getElementById('edit-prog-val').textContent=this.value+'%'" />
        </div>
        <div class="progress-bar" style="margin-top:8px;height:8px;">
          <div class="progress-fill" id="edit-progress-bar" style="width:0%"></div>
        </div>
      </div>
      <div><label>Progress notes</label><textarea id="edit-note" placeholder="What have you done so far?"></textarea></div>
      <div><label>Status</label>
        <select id="edit-done">
          <option value="0">In progress</option>
          <option value="1">Done ✓</option>
        </select>
      </div>
    </div>
    <div class="modal-btns">
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
      <button class="btn-save" onclick="saveEdit()">Save changes</button>
    </div>
  </div>
</div>

<script>
// ── STATE ──────────────────────────────────────────────
const API = 'api.php';
const today = new Date();
let cur = new Date(today.getFullYear(), today.getMonth(), 1);
let selectedDate = null;
let allTasks = {};
let upcomingTasks = [];
let currentView = 'calendar';

// ── UTILS ──────────────────────────────────────────────
function post(data) {
  const fd = new FormData();
  for (const k in data) fd.append(k, data[k]);
  return fetch(API, { method: 'POST', body: fd }).then(r => r.json());
}
function get(params) {
  const q = new URLSearchParams(params).toString();
  return fetch(API + '?' + q).then(r => r.json());
}
function dateKey(y, m, d) {
  return y + '-' + String(m + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
}
function daysLeft(due) {
  if (!due) return null;
  const d = new Date(due + 'T00:00:00');
  const diff = Math.round((d - new Date(today.toDateString())) / 86400000);
  return diff;
}
function daysBadge(due) {
  const n = daysLeft(due);
  if (n === null) return '';
  if (n < 0)  return `<span class="days-badge days-overdue">⚠ ${Math.abs(n)}d overdue</span>`;
  if (n === 0) return `<span class="days-badge days-red">Due today!</span>`;
  if (n <= 3)  return `<span class="days-badge days-red">${n}d left</span>`;
  if (n <= 7)  return `<span class="days-badge days-yellow">${n}d left</span>`;
  return `<span class="days-badge days-green">${n}d left</span>`;
}

// ── AUTH ──────────────────────────────────────────────
function showMsg(txt, type) {
  const el = document.getElementById('auth-msg');
  el.textContent = txt; el.className = 'msg ' + type; el.style.display = 'block';
}
function switchTab(t) {
  document.querySelectorAll('.tab-btn').forEach((b, i) => b.classList.toggle('active', (i === 0 && t === 'login') || (i === 1 && t === 'register')));
  document.getElementById('login-form').style.display = t === 'login' ? 'block' : 'none';
  document.getElementById('register-form').style.display = t === 'register' ? 'block' : 'none';
  document.getElementById('auth-msg').style.display = 'none';
}
async function doLogin() {
  const u = document.getElementById('l-user').value.trim();
  const p = document.getElementById('l-pass').value;
  if (!u || !p) return showMsg('Fill in all fields', 'error');
  const r = await post({ action: 'login', username: u, password: p });
  if (r.ok) { enterApp(r.username); }
  else showMsg(r.error || 'Error', 'error');
}
async function doRegister() {
  const u = document.getElementById('r-user').value.trim();
  const p = document.getElementById('r-pass').value;
  if (!u || !p) return showMsg('Fill in all fields', 'error');
  const r = await post({ action: 'register', username: u, password: p });
  if (r.ok) { showMsg('Account created! You can now log in.', 'success'); switchTab('login'); }
  else showMsg(r.error || 'Error', 'error');
}
async function doLogout() {
  await post({ action: 'logout' });
  document.getElementById('app-page').style.display = 'none';
  document.getElementById('login-page').style.display = 'flex';
}
function enterApp(username) {
  document.getElementById('login-page').style.display = 'none';
  document.getElementById('app-page').style.display = 'block';
  document.getElementById('user-label').textContent = '👤 ' + username;
  loadCalendar();
}

// ── VIEWS ─────────────────────────────────────────────
function showView(v) {
  currentView = v;
  document.querySelectorAll('.view').forEach(el => el.classList.remove('active'));
  document.getElementById('view-' + v).classList.add('active');
  document.querySelectorAll('.nav-tab').forEach(b => b.classList.remove('active'));
  event.target.classList.add('active');
  if (v === 'upcoming') loadUpcoming();
}

// ── CALENDAR ──────────────────────────────────────────
async function loadCalendar() {
  const month = cur.getFullYear() + '-' + String(cur.getMonth() + 1).padStart(2, '0');
  const tasks = await get({ action: 'get_tasks', month });
  allTasks = {};
  tasks.forEach(t => {
    const k = t.due_date;
    if (!allTasks[k]) allTasks[k] = [];
    allTasks[k].push(t);
  });
  renderCal();
  if (selectedDate) renderSidebar(selectedDate);
}
function renderCal() {
  const y = cur.getFullYear(), m = cur.getMonth();
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('month-label').textContent = months[m] + ' ' + y;
  const grid = document.getElementById('days-grid');
  grid.innerHTML = '';
  const firstDay = new Date(y, m, 1).getDay();
  const daysInMonth = new Date(y, m + 1, 0).getDate();
  const prevDays = new Date(y, m, 0).getDate();
  for (let i = 0; i < firstDay; i++) {
    const d = prevDays - firstDay + 1 + i;
    grid.appendChild(makeDay(d, new Date(y, m - 1, d), true));
  }
  for (let d = 1; d <= daysInMonth; d++) {
    grid.appendChild(makeDay(d, new Date(y, m, d), false));
  }
  const rem = 42 - firstDay - daysInMonth;
  for (let d = 1; d <= rem; d++) {
    grid.appendChild(makeDay(d, new Date(y, m + 1, d), true));
  }
}
function makeDay(d, date, other) {
  const el = document.createElement('div');
  el.className = 'day' + (other ? ' other-month' : '');
  const isToday = date.toDateString() === today.toDateString();
  if (isToday) el.classList.add('today');
  const key = dateKey(date.getFullYear(), date.getMonth(), date.getDate());
  if (selectedDate === key) el.classList.add('selected');
  const numEl = document.createElement('div');
  numEl.className = 'dnum';
  if (isToday) {
    numEl.innerHTML = `<span class="today-dot">${d}</span>`;
  } else {
    numEl.textContent = d;
  }
  el.appendChild(numEl);
  const dayTasks = allTasks[key] || [];
  dayTasks.slice(0, 2).forEach(t => {
    const dot = document.createElement('div');
    dot.className = 'tdot ' + t.priority + (t.done == 1 ? ' done' : '');
    dot.textContent = t.title;
    el.appendChild(dot);
  });
  if (dayTasks.length > 2) {
    const more = document.createElement('div');
    more.style.cssText = 'font-size:10px;color:#aaa;margin-top:2px;';
    more.textContent = '+' + (dayTasks.length - 2) + ' more';
    el.appendChild(more);
  }
  el.onclick = () => { selectedDate = key; renderCal(); renderSidebar(key); };
  return el;
}
function renderSidebar(key) {
  const parts = key.split('-');
  const date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('sel-date-label').textContent = months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
  const list = document.getElementById('task-list');
  const tasks = allTasks[key] || [];
  if (!tasks.length) { list.innerHTML = '<div class="empty-msg">No tasks for this day</div>'; return; }
  list.innerHTML = '';
  tasks.forEach(t => {
    const item = document.createElement('div');
    item.className = 'task-item';
    item.innerHTML = `
      <div class="chk ${t.done == 1 ? 'checked' : ''}" onclick="toggleDone(${t.id},'${key}')">${t.done == 1 ? '✓' : ''}</div>
      <div class="task-body">
        <div class="task-title-text ${t.done == 1 ? 'done' : ''}">${escHtml(t.title)}</div>
        <div class="task-meta">
          <span class="badge badge-${t.priority}">${t.priority}</span>
          ${t.done == 1 ? '<span class="badge badge-done">Done</span>' : ''}
          <span style="font-size:10px;color:#aaa;">${t.progress}%</span>
        </div>
        <div class="progress-mini"><div class="progress-mini-fill" style="width:${t.progress}%"></div></div>
      </div>
      <div class="task-actions">
        <button class="icon-btn" onclick="openEdit(${t.id})" title="Edit">✏️</button>
        <button class="icon-btn del" onclick="deleteTask(${t.id},'${key}')" title="Delete">🗑</button>
      </div>`;
    list.appendChild(item);
  });
}
async function addTask() {
  if (!selectedDate) { alert('Please select a day on the calendar first.'); return; }
  const title = document.getElementById('task-input').value.trim();
  const desc  = document.getElementById('task-desc').value.trim();
  const pri   = document.getElementById('priority-select').value;
  if (!title) return;
  await post({ action: 'add_task', title, description: desc, due_date: selectedDate, priority: pri });
  document.getElementById('task-input').value = '';
  document.getElementById('task-desc').value = '';
  loadCalendar();
}
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('task-input').addEventListener('keydown', e => { if (e.key === 'Enter') addTask(); });
});
async function toggleDone(id, key) {
  await post({ action: 'toggle_done', id });
  loadCalendar();
}
async function deleteTask(id, key) {
  if (!confirm('Delete this task?')) return;
  await post({ action: 'delete_task', id });
  loadCalendar();
}
function changeMonth(dir) {
  cur.setMonth(cur.getMonth() + dir);
  selectedDate = null;
  loadCalendar();
}

// ── UPCOMING ──────────────────────────────────────────
async function loadUpcoming() {
  upcomingTasks = await get({ action: 'get_all_active' });
  renderUpcoming();
}
function renderUpcoming() {
  const tasks = upcomingTasks;
  // Stats
  const overdue = tasks.filter(t => daysLeft(t.due_date) !== null && daysLeft(t.due_date) < 0).length;
  const dueToday = tasks.filter(t => daysLeft(t.due_date) === 0).length;
  const dueSoon  = tasks.filter(t => { const n = daysLeft(t.due_date); return n !== null && n > 0 && n <= 7; }).length;
  const avgProg  = tasks.length ? Math.round(tasks.reduce((s, t) => s + parseInt(t.progress), 0) / tasks.length) : 0;
  document.getElementById('stats-row').innerHTML = `
    <div class="stat-card stat-red"><div class="stat-num">${overdue}</div><div class="stat-label">Overdue</div></div>
    <div class="stat-card stat-orange"><div class="stat-num">${dueToday}</div><div class="stat-label">Due today</div></div>
    <div class="stat-card stat-blue"><div class="stat-num">${dueSoon}</div><div class="stat-label">Due this week</div></div>
    <div class="stat-card stat-green"><div class="stat-num">${avgProg}%</div><div class="stat-label">Avg progress</div></div>`;
  if (!tasks.length) {
    document.getElementById('upcoming-grid').innerHTML = '<div class="empty-msg" style="grid-column:1/-1;padding:40px;">No active tasks 🎉</div>';
    return;
  }
  const n = daysLeft;
  tasks.sort((a, b) => {
    const da = a.due_date ? new Date(a.due_date) : new Date('9999-12-31');
    const db = b.due_date ? new Date(b.due_date) : new Date('9999-12-31');
    return da - db;
  });
  document.getElementById('upcoming-grid').innerHTML = tasks.map(t => {
    const dl = daysLeft(t.due_date);
    const overdue = dl !== null && dl < 0;
    return `
    <div class="task-card prio-${t.priority} ${overdue ? 'overdue' : ''}">
      <div class="card-top">
        <div class="card-title">${escHtml(t.title)}</div>
        ${t.due_date ? daysBadge(t.due_date) : '<span style="font-size:11px;color:#ccc;">No due date</span>'}
      </div>
      ${t.description ? `<div class="card-desc">${escHtml(t.description)}</div>` : ''}
      ${t.progress_note ? `<div class="card-desc" style="color:#378ADD;font-style:italic;">📝 ${escHtml(t.progress_note)}</div>` : ''}
      <div class="progress-bar"><div class="progress-fill" style="width:${t.progress}%"></div></div>
      <div class="card-bottom">
        <span class="prog-label">Progress</span>
        <span class="prog-pct">${t.progress}%</span>
      </div>
      <div class="card-actions" style="margin-top:10px;">
        <button class="icon-btn" onclick="openEdit(${t.id})" style="font-size:12px;color:#378ADD;border:1px solid #ddd;border-radius:6px;padding:4px 10px;">✏️ Edit</button>
        <button class="icon-btn del" onclick="deleteTaskUpcoming(${t.id})" style="font-size:12px;border:1px solid #ddd;border-radius:6px;padding:4px 10px;">🗑 Delete</button>
      </div>
    </div>`;
  }).join('');
}
async function deleteTaskUpcoming(id) {
  if (!confirm('Delete this task?')) return;
  await post({ action: 'delete_task', id });
  loadUpcoming();
}

// ── EDIT MODAL ────────────────────────────────────────
let editingTask = null;
async function openEdit(id) {
  // Find task in allTasks or upcomingTasks
  let task = null;
  for (const k in allTasks) { task = allTasks[k].find(t => t.id == id) || task; }
  if (!task) task = upcomingTasks.find(t => t.id == id);
  if (!task) return;
  editingTask = task;
  document.getElementById('edit-id').value = task.id;
  document.getElementById('edit-title').value = task.title;
  document.getElementById('edit-desc').value = task.description || '';
  document.getElementById('edit-due').value = task.due_date || '';
  document.getElementById('edit-priority').value = task.priority;
  document.getElementById('edit-progress').value = task.progress;
  document.getElementById('edit-prog-val').textContent = task.progress + '%';
  document.getElementById('edit-progress-bar').style.width = task.progress + '%';
  document.getElementById('edit-note').value = task.progress_note || '';
  document.getElementById('edit-done').value = task.done;
  document.getElementById('modal').classList.add('open');
  document.getElementById('edit-progress').oninput = function() {
    document.getElementById('edit-prog-val').textContent = this.value + '%';
    document.getElementById('edit-progress-bar').style.width = this.value + '%';
  };
}
function closeModal() { document.getElementById('modal').classList.remove('open'); }
document.getElementById('modal').addEventListener('click', e => { if (e.target === document.getElementById('modal')) closeModal(); });
async function saveEdit() {
  const data = {
    action: 'update_task',
    id: document.getElementById('edit-id').value,
    title: document.getElementById('edit-title').value,
    description: document.getElementById('edit-desc').value,
    due_date: document.getElementById('edit-due').value,
    priority: document.getElementById('edit-priority').value,
    progress: document.getElementById('edit-progress').value,
    progress_note: document.getElementById('edit-note').value,
    done: document.getElementById('edit-done').value,
  };
  await post(data);
  closeModal();
  if (currentView === 'calendar') loadCalendar();
  else loadUpcoming();
}

// ── HELPERS ───────────────────────────────────────────
function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── INIT: check session ───────────────────────────────
(async () => {
  const r = await get({ action: 'check' });
  if (r.loggedIn) enterApp(r.username);
})();
</script>
</body>
</html>
