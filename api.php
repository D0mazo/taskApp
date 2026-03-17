
Copy

<?php
ini_set("session.cookie_samesite", "Lax");
ini_set("session.use_strict_mode", 1);
session_name("TASKCAL");
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
require "db.php";

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── AUTH ──────────────────────────────────────────────────
if ($action === 'register') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (strlen($u) < 3 || strlen($p) < 4) { echo json_encode(['error'=>'Username ≥3 chars, password ≥4 chars']); exit; }
    $hash = password_hash($p, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
    $stmt->bind_param('ss', $u, $hash);
    if ($stmt->execute()) { echo json_encode(['ok'=>true]); }
    else { echo json_encode(['error'=>'Username already taken']); }
    exit;
}

if ($action === 'login') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT id,password FROM users WHERE username=?");
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && password_verify($p, $row['password'])) {
        $_SESSION['uid'] = $row['id'];
        $_SESSION['uname'] = $u;
        echo json_encode(['ok'=>true, 'username'=>$u]);
    } else {
        echo json_encode(['error'=>'Invalid credentials']);
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['ok'=>true]);
    exit;
}

if ($action === 'check') {
    if (!empty($_SESSION['uid'])) echo json_encode(['loggedIn'=>true,'username'=>$_SESSION['uname']]);
    else echo json_encode(['loggedIn'=>false]);
    exit;
}

// ── REQUIRE LOGIN ────────────────────────────────────────
if (empty($_SESSION['uid'])) { echo json_encode(['error'=>'Not logged in']); exit; }
$uid = (int)$_SESSION['uid'];

// ── TASKS ─────────────────────────────────────────────────
if ($action === 'get_tasks') {
    $month = $_GET['month'] ?? date('Y-m');
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? AND (due_date LIKE ? OR due_date IS NULL) ORDER BY due_date ASC, priority DESC");
    $like = $month . '%';
    $stmt->bind_param('is', $uid, $like);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'get_upcoming') {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? AND done=0 AND due_date >= CURDATE() ORDER BY due_date ASC LIMIT 50");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'get_all_active') {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? AND done=0 ORDER BY due_date ASC, priority DESC");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'add_task') {
    $title    = trim($_POST['title'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $due      = $_POST['due_date'] ?? null;
    $priority = $_POST['priority'] ?? 'medium';
    if (!$title) { echo json_encode(['error'=>'Title required']); exit; }
    if (empty($due)) $due = null;
    $stmt = $conn->prepare("INSERT INTO tasks (user_id,title,description,due_date,priority) VALUES (?,?,?,?,?)");
    $stmt->bind_param('issss', $uid, $title, $desc, $due, $priority);
    $stmt->execute();
    echo json_encode(['ok'=>true, 'id'=>$conn->insert_id]);
    exit;
}

if ($action === 'update_task') {
    $id       = (int)($_POST['id'] ?? 0);
    $title    = trim($_POST['title'] ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $due      = $_POST['due_date'] ?? null;
    $priority = $_POST['priority'] ?? 'medium';
    $progress = (int)($_POST['progress'] ?? 0);
    $note     = trim($_POST['progress_note'] ?? '');
    $done     = (int)($_POST['done'] ?? 0);
    if (empty($due)) $due = null;
    $stmt = $conn->prepare("UPDATE tasks SET title=?,description=?,due_date=?,priority=?,progress=?,progress_note=?,done=? WHERE id=? AND user_id=?");
    $stmt->bind_param('ssssiisii', $title, $desc, $due, $priority, $progress, $note, $done, $id, $uid);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
    exit;
}

if ($action === 'delete_task') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->bind_param('ii', $id, $uid);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
    exit;
}

if ($action === 'toggle_done') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $conn->prepare("UPDATE tasks SET done = 1-done WHERE id=? AND user_id=?");
    $stmt->bind_param('ii', $id, $uid);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
    exit;
}

echo json_encode(['error'=>'Unknown action']);