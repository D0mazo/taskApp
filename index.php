<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Task Calendar Pro</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;color:#1a1a2e;min-height:100vh;}

/* ── LOGIN ── */
#login-page{display:flex;align-items:center;justify-content:center;min-height:100vh;background:linear-gradient(135deg,#1a1a2e,#0f3460);}
.login-box{background:#fff;border-radius:16px;padding:40px 36px;width:380px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
.login-box h1{font-size:22px;font-weight:700;margin-bottom:4px;}
.login-box p{font-size:13px;color:#888;margin-bottom:24px;}
.pro-badge{display:inline-block;background:linear-gradient(90deg,#f0a500,#e85d24);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;margin-left:8px;vertical-align:middle;}
.tabs{display:flex;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;margin-bottom:20px;}
.tab-btn{flex:1;padding:9px;font-size:13px;border:none;background:#f8f8f8;cursor:pointer;font-weight:500;color:#888;}
.tab-btn.active{background:#378ADD;color:#fff;}
.form-group{margin-bottom:14px;}
.form-group label{display:block;font-size:11px;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:.04em;margin-bottom:5px;}
.form-group input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;}
.form-group input:focus{outline:none;border-color:#378ADD;box-shadow:0 0 0 3px rgba(55,138,221,.1);}
.btn-primary{width:100%;padding:11px;background:#378ADD;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;}
.btn-primary:hover{background:#185FA5;}
.msg{font-size:13px;padding:9px 12px;border-radius:6px;margin-bottom:12px;display:none;}
.msg.error{background:#FCEBEB;color:#A32D2D;}
.msg.success{background:#EAF3DE;color:#3B6D11;}

/* ── TOPBAR ── */
#app-page{display:none;}
.topbar{background:#1a1a2e;color:#fff;padding:0 20px;height:54px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.topbar-left{display:flex;align-items:center;gap:20px;}
.topbar h2{font-size:15px;font-weight:700;white-space:nowrap;}
.nav-tabs{display:flex;gap:2px;}
.nav-tab{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;color:rgba(255,255,255,.6);border:none;background:none;white-space:nowrap;}
.nav-tab:hover{color:#fff;background:rgba(255,255,255,.08);}
.nav-tab.active{color:#fff;background:rgba(55,138,221,.45);}
.admin-tab{color:rgba(240,165,0,.8)!important;}
.admin-tab.active{background:rgba(240,165,0,.25)!important;color:#f0a500!important;}
.topbar-right{display:flex;align-items:center;gap:10px;}
.user-badge{font-size:12px;color:rgba(255,255,255,.65);}
.role-pill{font-size:10px;padding:2px 7px;border-radius:4px;font-weight:700;background:rgba(240,165,0,.25);color:#f0a500;}
.logout-btn{font-size:12px;padding:4px 10px;border:1px solid rgba(255,255,255,.2);border-radius:6px;background:none;color:rgba(255,255,255,.6);cursor:pointer;}
.logout-btn:hover{background:rgba(255,255,255,.1);color:#fff;}
.content{padding:20px;max-width:1400px;margin:0 auto;}

/* ── VIEWS ── */
.view{display:none;}
.view.active{display:block;}

/* ── CALENDAR ── */
.cal-layout{display:grid;grid-template-columns:1fr 340px;gap:18px;}
.cal-card{background:#fff;border-radius:14px;padding:18px;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.cal-title{font-size:17px;font-weight:700;}
.nav-btn{background:none;border:1px solid #ddd;border-radius:8px;padding:5px 14px;cursor:pointer;font-size:13px;}
.nav-btn:hover{background:#f5f5f5;}
.weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:3px;}
.weekday{text-align:center;font-size:10px;color:#aaa;padding:4px 0;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
.days-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}
.day{min-height:82px;border-radius:8px;border:1px solid #f0f0f0;padding:5px;cursor:pointer;background:#fff;transition:.15s;}
.day:hover{border-color:#bbb;background:#fafcff;}
.day.other-month{background:#fafafa;}
.day.other-month .dnum{color:#ccc;}
.day.today{border-color:#378ADD;background:#f0f7ff;}
.day.selected{border-color:#534AB7;border-width:2px;}
.dnum{font-size:11px;font-weight:600;margin-bottom:3px;}
.today-dot{background:#378ADD;color:#fff;border-radius:50%;width:19px;height:19px;display:inline-flex;align-items:center;justify-content:center;font-size:10px;}
.tdot{font-size:10px;padding:2px 4px;border-radius:3px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.tdot.low{background:#EAF3DE;color:#3B6D11;}
.tdot.medium{background:#FAEEDA;color:#854F0B;}
.tdot.high{background:#FCEBEB;color:#A32D2D;}
.tdot.done{opacity:.4;text-decoration:line-through;}

/* ── SIDEBAR ── */
.sidebar{display:flex;flex-direction:column;gap:12px;}
.panel{background:#fff;border-radius:14px;padding:15px;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.panel-title{font-size:11px;font-weight:700;color:#aaa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;}
.sel-label{font-size:15px;font-weight:700;margin-bottom:10px;}
.task-item{display:flex;align-items:flex-start;gap:8px;padding:7px 0;border-bottom:1px solid #f5f5f5;}
.task-item:last-child{border-bottom:none;}
.chk{width:15px;height:15px;border-radius:4px;border:1.5px solid #ccc;cursor:pointer;flex-shrink:0;margin-top:2px;display:flex;align-items:center;justify-content:center;font-size:9px;}
.chk.checked{background:#378ADD;border-color:#378ADD;color:#fff;}
.task-body{flex:1;min-width:0;}
.ttitle{font-size:13px;font-weight:500;line-height:1.4;}
.ttitle.done{text-decoration:line-through;color:#aaa;}
.tmeta{display:flex;align-items:center;gap:5px;margin-top:3px;flex-wrap:wrap;}
.badge{font-size:10px;padding:2px 6px;border-radius:4px;font-weight:500;}
.badge-low{background:#EAF3DE;color:#3B6D11;}
.badge-medium{background:#FAEEDA;color:#854F0B;}
.badge-high{background:#FCEBEB;color:#A32D2D;}
.badge-done{background:#e8f5e9;color:#2e7d32;}
.badge-cat{color:#fff;font-size:10px;padding:2px 6px;border-radius:4px;}
.badge-assign{background:#e8f0fe;color:#1a5fcc;font-size:10px;padding:2px 6px;border-radius:4px;}
.badge-recur{background:#f3e8ff;color:#7c3aed;font-size:10px;padding:2px 6px;border-radius:4px;}
.prog-mini{height:3px;background:#f0f0f0;border-radius:2px;margin-top:4px;}
.prog-mini-fill{height:100%;border-radius:2px;background:#378ADD;}
.task-actions{display:flex;gap:3px;flex-shrink:0;}
.icon-btn{background:none;border:none;cursor:pointer;font-size:13px;color:#ccc;padding:2px 3px;border-radius:4px;}
.icon-btn:hover{color:#378ADD;background:#f0f7ff;}
.icon-btn.del:hover{color:#E24B4A;background:#FCEBEB;}
.empty-msg{font-size:13px;color:#bbb;text-align:center;padding:16px 0;}

/* ── ADD FORM ── */
.add-form{display:flex;flex-direction:column;gap:7px;}
.add-form input,.add-form select,.add-form textarea{width:100%;padding:7px 10px;border:1px solid #ddd;border-radius:7px;font-size:13px;font-family:inherit;}
.add-form input:focus,.add-form select:focus{outline:none;border-color:#378ADD;}
.btn-add{background:#378ADD;color:#fff;border:none;border-radius:8px;padding:9px;font-size:13px;cursor:pointer;font-weight:600;}
.btn-add:hover{background:#185FA5;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:7px;}

/* ── UPCOMING ── */
.stats-row{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:18px;}
.stat-card{background:#fff;border-radius:12px;padding:12px 14px;box-shadow:0 2px 10px rgba(0,0,0,.06);text-align:center;}
.stat-num{font-size:24px;font-weight:700;}
.stat-label{font-size:10px;color:#aaa;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}
.stat-blue .stat-num{color:#378ADD;}
.stat-orange .stat-num{color:#f0a500;}
.stat-red .stat-num{color:#E24B4A;}
.stat-green .stat-num{color:#63c55a;}
.stat-purple .stat-num{color:#7c3aed;}
.upcoming-filters{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;}
.filter-btn{padding:5px 12px;border-radius:20px;border:1px solid #ddd;background:#fff;font-size:12px;cursor:pointer;font-weight:500;color:#555;}
.filter-btn.active{background:#378ADD;color:#fff;border-color:#378ADD;}
.upcoming-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;}
.task-card{background:#fff;border-radius:12px;padding:15px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid #ddd;}
.task-card.prio-low{border-left-color:#63c55a;}
.task-card.prio-medium{border-left-color:#f0a500;}
.task-card.prio-high{border-left-color:#E24B4A;}
.task-card.overdue{border-left-color:#A32D2D;background:#fffbfb;}
.card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;}
.card-title{font-size:14px;font-weight:600;flex:1;margin-right:8px;}
.days-badge{font-size:10px;padding:3px 7px;border-radius:5px;font-weight:700;white-space:nowrap;}
.days-green{background:#EAF3DE;color:#3B6D11;}
.days-yellow{background:#FAEEDA;color:#854F0B;}
.days-red{background:#FCEBEB;color:#A32D2D;}
.days-overdue{background:#A32D2D;color:#fff;}
.card-desc{font-size:12px;color:#888;margin-bottom:8px;line-height:1.5;}
.card-meta{display:flex;gap:5px;flex-wrap:wrap;margin-bottom:8px;}
.progress-bar{height:5px;background:#f0f0f0;border-radius:3px;overflow:hidden;margin-bottom:5px;}
.progress-fill{height:100%;border-radius:3px;background:#378ADD;}
.card-bottom{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}
.card-actions{display:flex;gap:6px;}
.btn-sm{font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid #ddd;background:#fff;cursor:pointer;color:#555;}
.btn-sm:hover{background:#f5f5f5;}
.btn-sm.danger:hover{background:#FCEBEB;color:#A32D2D;border-color:#E24B4A;}

/* ── COMMENTS ── */
.comments-section{margin-top:10px;border-top:1px solid #f0f0f0;padding-top:10px;}
.comment-item{display:flex;gap:8px;margin-bottom:8px;}
.comment-avatar{width:26px;height:26px;border-radius:50%;background:#e8f0fe;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#1a5fcc;flex-shrink:0;}
.comment-body{flex:1;}
.comment-header{display:flex;align-items:center;gap:6px;margin-bottom:2px;}
.comment-user{font-size:11px;font-weight:600;color:#1a1a2e;}
.comment-time{font-size:10px;color:#aaa;}
.comment-text{font-size:12px;color:#444;line-height:1.5;}
.comment-input-row{display:flex;gap:6px;margin-top:6px;}
.comment-input-row input{flex:1;padding:6px 10px;border:1px solid #ddd;border-radius:6px;font-size:12px;}
.comment-input-row button{padding:6px 12px;background:#378ADD;color:#fff;border:none;border-radius:6px;font-size:12px;cursor:pointer;}

/* ── ADMIN PANEL ── */
.admin-layout{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.admin-card{background:#fff;border-radius:14px;padding:18px;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.admin-card h3{font-size:15px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.user-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f5f5f5;}
.user-row:last-child{border-bottom:none;}
.user-avatar{width:32px;height:32px;border-radius:50%;background:#e8f0fe;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#1a5fcc;flex-shrink:0;}
.user-info{flex:1;}
.user-name{font-size:13px;font-weight:600;}
.user-email{font-size:11px;color:#aaa;}
.role-select{font-size:12px;padding:3px 6px;border:1px solid #ddd;border-radius:5px;background:#fff;}
.btn-danger-sm{padding:4px 8px;background:#FCEBEB;color:#A32D2D;border:1px solid #E24B4A;border-radius:5px;font-size:11px;cursor:pointer;}
.cat-row{display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid #f5f5f5;}
.cat-row:last-child{border-bottom:none;}
.cat-dot{width:12px;height:12px;border-radius:50%;flex-shrink:0;}
.cat-name{flex:1;font-size:13px;}
.add-cat-form{display:flex;gap:8px;margin-top:10px;}
.add-cat-form input{flex:1;padding:7px 10px;border:1px solid #ddd;border-radius:7px;font-size:13px;}
.add-cat-form input[type=color]{width:40px;padding:3px;}
.add-cat-form button{padding:7px 14px;background:#378ADD;color:#fff;border:none;border-radius:7px;font-size:13px;cursor:pointer;}

/* ── EDIT MODAL ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.open{display:flex;}
.modal{background:#fff;border-radius:16px;padding:26px;width:540px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);}
.modal h3{font-size:16px;font-weight:700;margin-bottom:18px;}
.modal-form{display:flex;flex-direction:column;gap:11px;}
.modal-form label{font-size:11px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;display:block;}
.modal-form input,.modal-form select,.modal-form textarea{width:100%;padding:8px 11px;border:1px solid #ddd;border-radius:8px;font-size:13px;font-family:inherit;}
.modal-form textarea{min-height:70px;resize:vertical;}
.modal-form .form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.prog-row{display:flex;align-items:center;gap:8px;}
.prog-row input[type=range]{flex:1;}
.prog-val{font-size:12px;font-weight:700;color:#378ADD;min-width:32px;}
.prog-preview{height:7px;background:#f0f0f0;border-radius:4px;overflow:hidden;margin-top:4px;}
.prog-preview-fill{height:100%;border-radius:4px;background:#378ADD;}
.modal-btns{display:flex;gap:10px;margin-top:10px;}
.btn-save{flex:1;padding:10px;background:#378ADD;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;}
.btn-cancel{padding:10px 18px;background:#f5f5f5;color:#555;border:none;border-radius:8px;font-size:14px;cursor:pointer;}
.modal-tabs{display:flex;gap:0;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;margin-bottom:16px;}
.modal-tab{flex:1;padding:7px;font-size:12px;font-weight:500;border:none;background:#f8f8f8;cursor:pointer;color:#888;}
.modal-tab.active{background:#378ADD;color:#fff;}

@media(max-width:900px){
  .cal-layout{grid-template-columns:1fr;}
  .admin-layout{grid-template-columns:1fr;}
  .stats-row{grid-template-columns:repeat(3,1fr);}
}
@media(max-width:600px){
  .stats-row{grid-template-columns:repeat(2,1fr);}
  .upcoming-grid{grid-template-columns:1fr;}
  .nav-tab span{display:none;}
}
</style>
</head>
<body>

<!-- LOGIN -->
<div id="login-page">
  <div class="login-box">
    <h1>📋 Task Calendar <span class="pro-badge">PRO</span></h1>
    <p>Family &amp; team task management</p>
    <div class="tabs">
      <button class="tab-btn active" onclick="switchTab('login')">Login</button>
      <button class="tab-btn" onclick="switchTab('register')">Register</button>
    </div>
    <div id="auth-msg" class="msg"></div>
    <div id="login-form">
      <div class="form-group"><label>Username</label><input type="text" id="l-user" placeholder="Username" /></div>
      <div class="form-group"><label>Password</label><input type="password" id="l-pass" placeholder="Password" onkeydown="if(event.key==='Enter')doLogin()" /></div>
      <button class="btn-primary" onclick="doLogin()">Login →</button>
    </div>
    <div id="register-form" style="display:none">
      <div class="form-group"><label>Username</label><input type="text" id="r-user" placeholder="Choose username" /></div>
      <div class="form-group"><label>Email <span style="color:#aaa;font-weight:400">(for notifications)</span></label><input type="email" id="r-email" placeholder="your@email.com" /></div>
      <div class="form-group"><label>Password</label><input type="password" id="r-pass" placeholder="Min 4 characters" /></div>
      <button class="btn-primary" onclick="doRegister()">Create account →</button>
      <p style="font-size:11px;color:#aaa;margin-top:10px;text-align:center;">First account created becomes Admin</p>
    </div>
  </div>
</div>

<!-- APP -->
<div id="app-page">
  <div class="topbar">
    <div class="topbar-left">
      <h2>📋 Task Calendar <span class="pro-badge">PRO</span></h2>
      <div class="nav-tabs">
        <button class="nav-tab active" onclick="showView('calendar',this)">📅 <span>Calendar</span></button>
        <button class="nav-tab" onclick="showView('upcoming',this)">🔔 <span>Upcoming</span></button>
        <button class="nav-tab admin-tab" id="admin-nav-btn" style="display:none" onclick="showView('admin',this)">⚙️ <span>Admin</span></button>
      </div>
    </div>
    <div class="topbar-right">
      <span class="user-badge" id="user-label"></span>
      <span class="role-pill" id="role-pill"></span>
      <button class="logout-btn" onclick="doLogout()">Logout</button>
    </div>
  </div>

  <div class="content">

    <!-- CALENDAR VIEW -->
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
            <div class="panel-title">Add task</div>
            <div class="add-form">
              <input type="text" id="task-input" placeholder="Task title..." />
              <input type="text" id="task-desc-input" placeholder="Description (optional)" />
              <div class="form-row">
                <select id="priority-select">
                  <option value="low">🟢 Low</option>
                  <option value="medium" selected>🟡 Medium</option>
                  <option value="high">🔴 High</option>
                </select>
                <select id="category-select"><option value="">No category</option></select>
              </div>
              <div class="form-row">
                <select id="assign-select"><option value="">Unassigned</option></select>
                <select id="recurring-select">
                  <option value="none">No repeat</option>
                  <option value="daily">🔁 Daily</option>
                  <option value="weekly">🔁 Weekly</option>
                  <option value="monthly">🔁 Monthly</option>
                </select>
              </div>
              <button class="btn-add" onclick="addTask()">+ Add Task</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- UPCOMING VIEW -->
    <div class="view" id="view-upcoming">
      <div class="stats-row" id="stats-row"></div>
      <div class="upcoming-filters" id="upcoming-filters">
        <button class="filter-btn active" onclick="filterUpcoming('all',this)">All</button>
        <button class="filter-btn" onclick="filterUpcoming('mine',this)">Mine</button>
        <button class="filter-btn" onclick="filterUpcoming('overdue',this)">Overdue</button>
        <button class="filter-btn" onclick="filterUpcoming('high',this)">🔴 High</button>
      </div>
      <div class="upcoming-grid" id="upcoming-grid"></div>
    </div>

    <!-- ADMIN VIEW -->
    <div class="view" id="view-admin">
      <div class="admin-layout">
        <div class="admin-card">
          <h3>👥 Family Members</h3>
          <div id="users-list"></div>
        </div>
        <div class="admin-card">
          <h3>🏷️ Categories</h3>
          <div id="cats-list"></div>
          <div class="add-cat-form">
            <input type="color" id="cat-color" value="#378ADD" />
            <input type="text" id="cat-name" placeholder="New category name..." />
            <button onclick="addCategory()">Add</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="modal">
  <div class="modal">
    <div class="modal-tabs">
      <button class="modal-tab active" onclick="switchModalTab('details',this)">📝 Details</button>
      <button class="modal-tab" onclick="switchModalTab('comments',this)">💬 Comments</button>
    </div>
    <div id="modal-details">
      <div class="modal-form">
        <input type="hidden" id="edit-id" />
        <div><label>Title</label><input type="text" id="edit-title" /></div>
        <div><label>Description</label><textarea id="edit-desc"></textarea></div>
        <div class="form-row">
          <div><label>Due date</label><input type="date" id="edit-due" /></div>
          <div><label>Priority</label>
            <select id="edit-priority">
              <option value="low">🟢 Low</option>
              <option value="medium">🟡 Medium</option>
              <option value="high">🔴 High</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div><label>Assign to</label><select id="edit-assign"><option value="">Unassigned</option></select></div>
          <div><label>Category</label><select id="edit-category"><option value="">None</option></select></div>
        </div>
        <div><label>Recurring</label>
          <select id="edit-recurring">
            <option value="none">No repeat</option>
            <option value="daily">🔁 Daily</option>
            <option value="weekly">🔁 Weekly</option>
            <option value="monthly">🔁 Monthly</option>
          </select>
        </div>
        <div>
          <label>Progress: <span id="edit-prog-val" style="color:#378ADD;font-weight:700">0%</span></label>
          <div class="prog-row"><input type="range" id="edit-progress" min="0" max="100" value="0" oninput="updateProgBar(this.value)" /></div>
          <div class="prog-preview"><div class="prog-preview-fill" id="prog-preview-fill" style="width:0%"></div></div>
        </div>
        <div><label>Progress notes</label><textarea id="edit-note" placeholder="What have you done so far?"></textarea></div>
        <div><label>Status</label>
          <select id="edit-done"><option value="0">In progress</option><option value="1">Done ✓</option></select>
        </div>
      </div>
      <div class="modal-btns">
        <button class="btn-cancel" onclick="closeModal()">Cancel</button>
        <button class="btn-save" onclick="saveEdit()">Save changes</button>
      </div>
    </div>
    <div id="modal-comments" style="display:none">
      <div id="comments-list"></div>
      <div class="comment-input-row">
        <input type="text" id="comment-input" placeholder="Write a comment..." onkeydown="if(event.key==='Enter')addComment()" />
        <button onclick="addComment()">Send</button>
      </div>
    </div>
  </div>
</div>

<script>
const API = 'api.php';
const today = new Date();
let cur = new Date(today.getFullYear(), today.getMonth(), 1);
let selectedDate = null;
let allTasks = {};
let upcomingTasks = [];
let allUsers = [];
let allCats = [];
let currentView = 'calendar';
let currentUid = null;
let currentRole = 'member';
let upcomingFilter = 'all';
let editingTaskId = null;

// ── UTILS ─────────────────────────────────────────────
function post(data) {
  const fd = new FormData();
  for (const k in data) fd.append(k, data[k] ?? '');
  return fetch(API, {method:'POST',body:fd}).then(r=>r.json()).catch(e=>({error:e.message}));
}
function get(params) {
  return fetch(API+'?'+new URLSearchParams(params)).then(r=>r.json()).catch(e=>({error:e.message}));
}
function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function dateKey(y,m,d){ return y+'-'+String(m+1).padStart(2,'0')+'-'+String(d).padStart(2,'0'); }
function daysLeft(due){
  if(!due) return null;
  return Math.round((new Date(due+'T00:00:00') - new Date(today.toDateString()))/86400000);
}
function daysBadge(due){
  const n=daysLeft(due);
  if(n===null) return '';
  if(n<0)  return `<span class="days-badge days-overdue">⚠ ${Math.abs(n)}d overdue</span>`;
  if(n===0) return `<span class="days-badge days-red">Due today!</span>`;
  if(n<=3)  return `<span class="days-badge days-red">${n}d left</span>`;
  if(n<=7)  return `<span class="days-badge days-yellow">${n}d left</span>`;
  return `<span class="days-badge days-green">${n}d left</span>`;
}
function updateProgBar(v){
  document.getElementById('edit-prog-val').textContent=v+'%';
  document.getElementById('prog-preview-fill').style.width=v+'%';
}

// ── AUTH ─────────────────────────────────────────────
function showMsg(txt,type){const e=document.getElementById('auth-msg');e.textContent=txt;e.className='msg '+type;e.style.display='block';}
function switchTab(t){
  document.querySelectorAll('.tab-btn').forEach((b,i)=>b.classList.toggle('active',(i===0&&t==='login')||(i===1&&t==='register')));
  document.getElementById('login-form').style.display=t==='login'?'block':'none';
  document.getElementById('register-form').style.display=t==='register'?'block':'none';
  document.getElementById('auth-msg').style.display='none';
}
async function doLogin(){
  const u=document.getElementById('l-user').value.trim();
  const p=document.getElementById('l-pass').value;
  if(!u||!p) return showMsg('Fill in all fields','error');
  showMsg('Logging in...','success');
  try{
    const r=await post({action:'login',username:u,password:p});
    if(r.ok) enterApp(r.username,r.role);
    else showMsg(r.error||'Login failed','error');
  }catch(e){showMsg('Network error: '+e.message,'error');}
}
async function doRegister(){
  const u=document.getElementById('r-user').value.trim();
  const p=document.getElementById('r-pass').value;
  const e=document.getElementById('r-email').value.trim();
  if(!u||!p) return showMsg('Fill in required fields','error');
  const r=await post({action:'register',username:u,password:p,email:e});
  if(r.ok){showMsg('Account created! You can now log in.','success');switchTab('login');}
  else showMsg(r.error||'Error','error');
}
async function doLogout(){
  await post({action:'logout'});
  document.getElementById('app-page').style.display='none';
  document.getElementById('login-page').style.display='flex';
}
function enterApp(username,role){
  document.getElementById('login-page').style.display='none';
  document.getElementById('app-page').style.display='block';
  document.getElementById('user-label').textContent='👤 '+username;
  document.getElementById('role-pill').textContent=role==='admin'?'Admin':'Member';
  currentRole=role;
  if(role==='admin') document.getElementById('admin-nav-btn').style.display='';
  loadMeta().then(()=>loadCalendar());
}

// ── META (users + categories) ─────────────────────────
async function loadMeta(){
  const [users,cats]=await Promise.all([get({action:'get_users'}),get({action:'get_categories'})]);
  allUsers=Array.isArray(users)?users:[];
  allCats=Array.isArray(cats)?cats:[];
  populateUserSelects();
  populateCatSelects();
}
function populateUserSelects(){
  const opts='<option value="">Unassigned</option>'+allUsers.map(u=>`<option value="${u.id}">${esc(u.username)}</option>`).join('');
  ['assign-select','edit-assign'].forEach(id=>{const el=document.getElementById(id);if(el)el.innerHTML=opts;});
}
function populateCatSelects(){
  const opts='<option value="">No category</option>'+allCats.map(c=>`<option value="${c.id}">${esc(c.name)}</option>`).join('');
  ['category-select','edit-category'].forEach(id=>{const el=document.getElementById(id);if(el)el.innerHTML=opts;});
}

// ── VIEWS ─────────────────────────────────────────────
function showView(v,btn){
  currentView=v;
  document.querySelectorAll('.view').forEach(e=>e.classList.remove('active'));
  document.getElementById('view-'+v).classList.add('active');
  document.querySelectorAll('.nav-tab').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
  if(v==='upcoming') loadUpcoming();
  if(v==='admin') loadAdmin();
}

// ── CALENDAR ─────────────────────────────────────────
async function loadCalendar(){
  const month=cur.getFullYear()+'-'+String(cur.getMonth()+1).padStart(2,'0');
  const tasks=await get({action:'get_tasks',month});
  allTasks={};
  (Array.isArray(tasks)?tasks:[]).forEach(t=>{
    const k=t.due_date;if(!k)return;
    if(!allTasks[k])allTasks[k]=[];
    allTasks[k].push(t);
  });
  renderCal();
  if(selectedDate) renderSidebar(selectedDate);
}
function renderCal(){
  const y=cur.getFullYear(),m=cur.getMonth();
  const months=['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('month-label').textContent=months[m]+' '+y;
  const grid=document.getElementById('days-grid');
  grid.innerHTML='';
  const firstDay=new Date(y,m,1).getDay();
  const dim=new Date(y,m+1,0).getDate();
  const prev=new Date(y,m,0).getDate();
  for(let i=0;i<firstDay;i++) grid.appendChild(makeDay(prev-firstDay+1+i,new Date(y,m-1,prev-firstDay+1+i),true));
  for(let d=1;d<=dim;d++) grid.appendChild(makeDay(d,new Date(y,m,d),false));
  const rem=42-firstDay-dim;
  for(let d=1;d<=rem;d++) grid.appendChild(makeDay(d,new Date(y,m+1,d),true));
}
function makeDay(d,date,other){
  const el=document.createElement('div');
  el.className='day'+(other?' other-month':'');
  const isToday=date.toDateString()===today.toDateString();
  if(isToday) el.classList.add('today');
  const key=dateKey(date.getFullYear(),date.getMonth(),date.getDate());
  if(selectedDate===key) el.classList.add('selected');
  const numEl=document.createElement('div');
  numEl.className='dnum';
  numEl.innerHTML=isToday?`<span class="today-dot">${d}</span>`:d;
  el.appendChild(numEl);
  (allTasks[key]||[]).slice(0,2).forEach(t=>{
    const dot=document.createElement('div');
    dot.className='tdot '+t.priority+(t.done==1?' done':'');
    dot.textContent=(t.assignee_name?'@'+t.assignee_name+' ':'')+t.title;
    el.appendChild(dot);
  });
  if((allTasks[key]||[]).length>2){
    const m2=document.createElement('div');
    m2.style.cssText='font-size:10px;color:#aaa;';
    m2.textContent='+'+(allTasks[key].length-2)+' more';
    el.appendChild(m2);
  }
  el.onclick=()=>{selectedDate=key;renderCal();renderSidebar(key);};
  return el;
}
function renderSidebar(key){
  const parts=key.split('-');
  const date=new Date(parseInt(parts[0]),parseInt(parts[1])-1,parseInt(parts[2]));
  const months=['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('sel-date-label').textContent=months[date.getMonth()]+' '+date.getDate()+', '+date.getFullYear();
  const list=document.getElementById('task-list');
  const tasks=allTasks[key]||[];
  if(!tasks.length){list.innerHTML='<div class="empty-msg">No tasks for this day</div>';return;}
  list.innerHTML='';
  tasks.forEach(t=>{
    const item=document.createElement('div');
    item.className='task-item';
    const catColor=t.category_color||'#aaa';
    item.innerHTML=`
      <div class="chk ${t.done==1?'checked':''}" onclick="toggleDone(${t.id},'${key}')">${t.done==1?'✓':''}</div>
      <div class="task-body">
        <div class="ttitle ${t.done==1?'done':''}">${esc(t.title)}</div>
        <div class="tmeta">
          <span class="badge badge-${t.priority}">${t.priority}</span>
          ${t.category_name?`<span class="badge-cat" style="background:${catColor}">${esc(t.category_name)}</span>`:''}
          ${t.assignee_name?`<span class="badge-assign">@${esc(t.assignee_name)}</span>`:''}
          ${t.recurring&&t.recurring!=='none'?`<span class="badge-recur">🔁${t.recurring}</span>`:''}
          ${t.done==1?'<span class="badge badge-done">Done</span>':''}
        </div>
        <div class="prog-mini"><div class="prog-mini-fill" style="width:${t.progress}%"></div></div>
      </div>
      <div class="task-actions">
        <button class="icon-btn" onclick="openEdit(${t.id})">✏️</button>
        <button class="icon-btn del" onclick="deleteTask(${t.id},'${key}')">🗑</button>
      </div>`;
    list.appendChild(item);
  });
}
async function addTask(){
  if(!selectedDate){alert('Select a day first.');return;}
  const title=document.getElementById('task-input').value.trim();
  const desc=document.getElementById('task-desc-input').value.trim();
  const pri=document.getElementById('priority-select').value;
  const cat=document.getElementById('category-select').value;
  const assign=document.getElementById('assign-select').value;
  const recur=document.getElementById('recurring-select').value;
  if(!title) return;
  await post({action:'add_task',title,description:desc,due_date:selectedDate,priority:pri,category_id:cat,assigned_to:assign,recurring:recur});
  document.getElementById('task-input').value='';
  document.getElementById('task-desc-input').value='';
  loadCalendar();
}
document.addEventListener('DOMContentLoaded',()=>{
  document.getElementById('task-input').addEventListener('keydown',e=>{if(e.key==='Enter')addTask();});
});
async function toggleDone(id,key){await post({action:'toggle_done',id});loadCalendar();}
async function deleteTask(id,key){
  if(!confirm('Delete this task?')) return;
  await post({action:'delete_task',id});
  loadCalendar();
}
function changeMonth(dir){cur.setMonth(cur.getMonth()+dir);selectedDate=null;loadCalendar();}

// ── UPCOMING ─────────────────────────────────────────
async function loadUpcoming(){
  upcomingTasks=await get({action:'get_all_active'});
  if(!Array.isArray(upcomingTasks)) upcomingTasks=[];
  renderUpcoming();
}
function filterUpcoming(f,btn){
  upcomingFilter=f;
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
  renderUpcoming();
}
function renderUpcoming(){
  let tasks=[...upcomingTasks];
  if(upcomingFilter==='mine') tasks=tasks.filter(t=>t.assigned_to==currentUid||t.created_by==currentUid);
  if(upcomingFilter==='overdue') tasks=tasks.filter(t=>daysLeft(t.due_date)<0);
  if(upcomingFilter==='high') tasks=tasks.filter(t=>t.priority==='high');
  const overdue=upcomingTasks.filter(t=>daysLeft(t.due_date)!==null&&daysLeft(t.due_date)<0).length;
  const dueToday=upcomingTasks.filter(t=>daysLeft(t.due_date)===0).length;
  const dueSoon=upcomingTasks.filter(t=>{const n=daysLeft(t.due_date);return n!==null&&n>0&&n<=7;}).length;
  const avgProg=upcomingTasks.length?Math.round(upcomingTasks.reduce((s,t)=>s+parseInt(t.progress),0)/upcomingTasks.length):0;
  const cats=new Set(upcomingTasks.map(t=>t.category_name).filter(Boolean)).size;
  document.getElementById('stats-row').innerHTML=`
    <div class="stat-card stat-red"><div class="stat-num">${overdue}</div><div class="stat-label">Overdue</div></div>
    <div class="stat-card stat-orange"><div class="stat-num">${dueToday}</div><div class="stat-label">Due today</div></div>
    <div class="stat-card stat-blue"><div class="stat-num">${dueSoon}</div><div class="stat-label">Due this week</div></div>
    <div class="stat-card stat-green"><div class="stat-num">${avgProg}%</div><div class="stat-label">Avg progress</div></div>
    <div class="stat-card stat-purple"><div class="stat-num">${upcomingTasks.length}</div><div class="stat-label">Total active</div></div>`;
  if(!tasks.length){
    document.getElementById('upcoming-grid').innerHTML='<div class="empty-msg" style="grid-column:1/-1;padding:40px 0;">No tasks here 🎉</div>';
    return;
  }
  tasks.sort((a,b)=>{
    const da=a.due_date?new Date(a.due_date):new Date('9999-12-31');
    const db=b.due_date?new Date(b.due_date):new Date('9999-12-31');
    return da-db;
  });
  document.getElementById('upcoming-grid').innerHTML=tasks.map(t=>{
    const ol=daysLeft(t.due_date)!==null&&daysLeft(t.due_date)<0;
    const catColor=t.category_color||'#aaa';
    return `
    <div class="task-card prio-${t.priority}${ol?' overdue':''}">
      <div class="card-top">
        <div class="card-title">${esc(t.title)}</div>
        ${t.due_date?daysBadge(t.due_date):'<span style="font-size:11px;color:#ccc">No date</span>'}
      </div>
      ${t.description?`<div class="card-desc">${esc(t.description)}</div>`:''}
      <div class="card-meta">
        <span class="badge badge-${t.priority}">${t.priority}</span>
        ${t.category_name?`<span class="badge-cat" style="background:${catColor}">${esc(t.category_name)}</span>`:''}
        ${t.assignee_name?`<span class="badge-assign">👤 ${esc(t.assignee_name)}</span>`:''}
        ${t.creator_name?`<span style="font-size:10px;color:#aaa">by ${esc(t.creator_name)}</span>`:''}
        ${t.recurring&&t.recurring!=='none'?`<span class="badge-recur">🔁 ${t.recurring}</span>`:''}
      </div>
      ${t.progress_note?`<div class="card-desc" style="color:#378ADD;font-style:italic">📝 ${esc(t.progress_note)}</div>`:''}
      <div class="progress-bar"><div class="progress-fill" style="width:${t.progress}%"></div></div>
      <div class="card-bottom"><span style="font-size:11px;color:#aaa">Progress</span><span style="font-size:12px;font-weight:700;color:#378ADD">${t.progress}%</span></div>
      <div class="card-actions">
        <button class="btn-sm" onclick="openEdit(${t.id})">✏️ Edit</button>
        <button class="btn-sm danger" onclick="deleteTaskUpcoming(${t.id})">🗑 Delete</button>
      </div>
    </div>`;
  }).join('');
}
async function deleteTaskUpcoming(id){
  if(!confirm('Delete this task?')) return;
  await post({action:'delete_task',id});
  loadUpcoming();
}

// ── EDIT MODAL ────────────────────────────────────────
function switchModalTab(t,btn){
  document.querySelectorAll('.modal-tab').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
  document.getElementById('modal-details').style.display=t==='details'?'block':'none';
  document.getElementById('modal-comments').style.display=t==='comments'?'block':'none';
  if(t==='comments'&&editingTaskId) loadComments(editingTaskId);
}
function findTask(id){
  for(const k in allTasks){const t=allTasks[k].find(t=>t.id==id);if(t)return t;}
  return upcomingTasks.find(t=>t.id==id);
}
function openEdit(id){
  editingTaskId=id;
  const t=findTask(id);
  if(!t)return;
  document.getElementById('edit-id').value=t.id;
  document.getElementById('edit-title').value=t.title;
  document.getElementById('edit-desc').value=t.description||'';
  document.getElementById('edit-due').value=t.due_date||'';
  document.getElementById('edit-priority').value=t.priority;
  document.getElementById('edit-assign').value=t.assigned_to||'';
  document.getElementById('edit-category').value=t.category_id||'';
  document.getElementById('edit-recurring').value=t.recurring||'none';
  document.getElementById('edit-progress').value=t.progress;
  document.getElementById('edit-note').value=t.progress_note||'';
  document.getElementById('edit-done').value=t.done;
  updateProgBar(t.progress);
  // switch to details tab
  switchModalTab('details',document.querySelector('.modal-tab'));
  document.getElementById('modal').classList.add('open');
}
function closeModal(){document.getElementById('modal').classList.remove('open');}
document.getElementById('modal').addEventListener('click',e=>{if(e.target===document.getElementById('modal'))closeModal();});
async function saveEdit(){
  const data={
    action:'update_task',
    id:document.getElementById('edit-id').value,
    title:document.getElementById('edit-title').value,
    description:document.getElementById('edit-desc').value,
    due_date:document.getElementById('edit-due').value,
    priority:document.getElementById('edit-priority').value,
    assigned_to:document.getElementById('edit-assign').value,
    category_id:document.getElementById('edit-category').value,
    recurring:document.getElementById('edit-recurring').value,
    progress:document.getElementById('edit-progress').value,
    progress_note:document.getElementById('edit-note').value,
    done:document.getElementById('edit-done').value,
  };
  await post(data);
  closeModal();
  if(currentView==='calendar') loadCalendar();
  else loadUpcoming();
}

// ── COMMENTS ─────────────────────────────────────────
async function loadComments(taskId){
  const comments=await get({action:'get_comments',task_id:taskId});
  const list=document.getElementById('comments-list');
  if(!Array.isArray(comments)||!comments.length){
    list.innerHTML='<div class="empty-msg">No comments yet</div>';return;
  }
  list.innerHTML=comments.map(c=>`
    <div class="comment-item">
      <div class="comment-avatar">${esc(c.username).substring(0,2).toUpperCase()}</div>
      <div class="comment-body">
        <div class="comment-header">
          <span class="comment-user">${esc(c.username)}</span>
          <span class="comment-time">${c.created_at}</span>
        </div>
        <div class="comment-text">${esc(c.body)}</div>
      </div>
    </div>`).join('');
}
async function addComment(){
  const body=document.getElementById('comment-input').value.trim();
  if(!body||!editingTaskId) return;
  await post({action:'add_comment',task_id:editingTaskId,body});
  document.getElementById('comment-input').value='';
  loadComments(editingTaskId);
}

// ── ADMIN ─────────────────────────────────────────────
async function loadAdmin(){
  await loadMeta();
  renderUsersList();
  renderCatsList();
}
function renderUsersList(){
  const list=document.getElementById('users-list');
  if(!allUsers.length){list.innerHTML='<div class="empty-msg">No users</div>';return;}
  list.innerHTML=allUsers.map(u=>`
    <div class="user-row">
      <div class="user-avatar">${esc(u.username).substring(0,2).toUpperCase()}</div>
      <div class="user-info">
        <div class="user-name">${esc(u.username)}</div>
        <div class="user-email">${esc(u.email||'No email')}</div>
      </div>
      <select class="role-select" onchange="setRole(${u.id},this.value)">
        <option value="member" ${u.role==='member'?'selected':''}>Member</option>
        <option value="admin" ${u.role==='admin'?'selected':''}>Admin</option>
      </select>
      <button class="btn-danger-sm" onclick="deleteUser(${u.id})">Remove</button>
    </div>`).join('');
}
function renderCatsList(){
  const list=document.getElementById('cats-list');
  if(!allCats.length){list.innerHTML='<div class="empty-msg">No categories yet</div>';return;}
  list.innerHTML=allCats.map(c=>`
    <div class="cat-row">
      <div class="cat-dot" style="background:${c.color}"></div>
      <div class="cat-name">${esc(c.name)}</div>
      <button class="btn-danger-sm" onclick="deleteCat(${c.id})">×</button>
    </div>`).join('');
}
async function setRole(id,role){await post({action:'set_role',id,role});loadAdmin();}
async function deleteUser(id){
  if(!confirm('Remove this user?')) return;
  await post({action:'delete_user',id});
  loadAdmin();
}
async function addCategory(){
  const name=document.getElementById('cat-name').value.trim();
  const color=document.getElementById('cat-color').value;
  if(!name) return;
  await post({action:'add_category',name,color});
  document.getElementById('cat-name').value='';
  await loadMeta();
  renderCatsList();
}
async function deleteCat(id){
  if(!confirm('Delete this category?')) return;
  await post({action:'delete_category',id});
  await loadMeta();
  renderCatsList();
}

// ── INIT ─────────────────────────────────────────────
(async()=>{
  const r=await get({action:'check'});
  if(r.loggedIn){
    currentUid=r.uid;
    currentRole=r.role;
    enterApp(r.username,r.role);
  }
})();
</script>
</body>
</html>
