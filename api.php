<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
ini_set("session.cookie_samesite", "Lax");
ini_set("session.use_strict_mode", 1);
session_name("TASKCAL_PRO");
session_start();
ob_clean();
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
require "db.php";

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── AUTH ──────────────────────────────────────────────────────────────
if ($action === 'register') {
    $u     = trim($_POST['username'] ?? '');
    $p     = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    if (strlen($u) < 3 || strlen($p) < 4) { echo json_encode(['error'=>'Username ≥3 chars, password ≥4 chars']); exit; }
    // First user becomes admin
    $res  = $conn->query("SELECT COUNT(*) as c FROM users");
    $role = ($res->fetch_assoc()['c'] == 0) ? 'admin' : 'member';
    $hash = password_hash($p, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss', $u, $email, $hash, $role);
    if ($stmt->execute()) echo json_encode(['ok'=>true,'role'=>$role]);
    else echo json_encode(['error'=>'Username already taken']);
    exit;
}

if ($action === 'login') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (!$u || !$p) { echo json_encode(['error'=>'Fill in all fields']); exit; }
    $stmt = $conn->prepare("SELECT id,password,role,email FROM users WHERE username=?");
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) { echo json_encode(['error'=>'User not found']); exit; }
    if (password_verify($p, $row['password'])) {
        $_SESSION['uid']   = $row['id'];
        $_SESSION['uname'] = $u;
        $_SESSION['role']  = $row['role'];
        echo json_encode(['ok'=>true,'username'=>$u,'role'=>$row['role']]);
    } else {
        echo json_encode(['error'=>'Wrong password']);
    }
    exit;
}

if ($action === 'logout') { session_destroy(); echo json_encode(['ok'=>true]); exit; }

if ($action === 'check') {
    if (!empty($_SESSION['uid']))
        echo json_encode(['loggedIn'=>true,'username'=>$_SESSION['uname'],'role'=>$_SESSION['role'],'uid'=>$_SESSION['uid']]);
    else
        echo json_encode(['loggedIn'=>false]);
    exit;
}

// ── REQUIRE LOGIN ────────────────────────────────────────────────────
if (empty($_SESSION['uid'])) { echo json_encode(['error'=>'Not logged in']); exit; }
$uid  = (int)$_SESSION['uid'];
$role = $_SESSION['role'] ?? 'member';

// ── USERS ─────────────────────────────────────────────────────────────
if ($action === 'get_users') {
    $rows = $conn->query("SELECT id,username,email,role,created_at FROM users ORDER BY username")->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows); exit;
}
if ($action === 'delete_user') {
    if ($role !== 'admin') { echo json_encode(['error'=>'Admins only']); exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id === $uid) { echo json_encode(['error'=>'Cannot delete yourself']); exit; }
    $conn->query("DELETE FROM users WHERE id=$id");
    echo json_encode(['ok'=>true]); exit;
}
if ($action === 'set_role') {
    if ($role !== 'admin') { echo json_encode(['error'=>'Admins only']); exit; }
    $id  = (int)($_POST['id'] ?? 0);
    $r   = $_POST['role'] === 'admin' ? 'admin' : 'member';
    $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->bind_param('si', $r, $id);
    $stmt->execute();
    echo json_encode(['ok'=>true]); exit;
}

// ── CATEGORIES ────────────────────────────────────────────────────────
if ($action === 'get_categories') {
    $rows = $conn->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows); exit;
}
if ($action === 'add_category') {
    $name  = trim($_POST['name'] ?? '');
    $color = trim($_POST['color'] ?? '#378ADD');
    if (!$name) { echo json_encode(['error'=>'Name required']); exit; }
    $stmt = $conn->prepare("INSERT INTO categories (name,color,created_by) VALUES (?,?,?)");
    $stmt->bind_param('ssi', $name, $color, $uid);
    $stmt->execute();
    echo json_encode(['ok'=>true,'id'=>$conn->insert_id]); exit;
}
if ($action === 'delete_category') {
    if ($role !== 'admin') { echo json_encode(['error'=>'Admins only']); exit; }
    $id = (int)($_POST['id'] ?? 0);
    $conn->query("DELETE FROM categories WHERE id=$id");
    echo json_encode(['ok'=>true]); exit;
}

// ── TASKS ─────────────────────────────────────────────────────────────
if ($action === 'get_tasks') {
    $month = $_GET['month'] ?? date('Y-m');
    $like  = $month . '%';
    $stmt  = $conn->prepare("
        SELECT t.*, 
               u1.username as creator_name,
               u2.username as assignee_name,
               c.name as category_name, c.color as category_color
        FROM tasks t
        LEFT JOIN users u1 ON t.created_by = u1.id
        LEFT JOIN users u2 ON t.assigned_to = u2.id
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.due_date LIKE ? OR t.due_date IS NULL
        ORDER BY t.due_date ASC, t.priority DESC");
    $stmt->bind_param('s', $like);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC)); exit;
}

if ($action === 'get_all_active') {
    $rows = $conn->query("
        SELECT t.*,
               u1.username as creator_name,
               u2.username as assignee_name,
               c.name as category_name, c.color as category_color
        FROM tasks t
        LEFT JOIN users u1 ON t.created_by = u1.id
        LEFT JOIN users u2 ON t.assigned_to = u2.id
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.done=0
        ORDER BY t.due_date ASC, t.priority DESC")->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows); exit;
}

if ($action === 'add_task') {
    $title     = trim($_POST['title'] ?? '');
    $desc      = trim($_POST['description'] ?? '');
    $due       = $_POST['due_date'] ?? null;
    $priority  = $_POST['priority'] ?? 'medium';
    $assigned  = (int)($_POST['assigned_to'] ?? 0) ?: null;
    $cat       = (int)($_POST['category_id'] ?? 0) ?: null;
    $recurring = $_POST['recurring'] ?? 'none';
    if (!$title) { echo json_encode(['error'=>'Title required']); exit; }
    if (empty($due)) $due = null;
    $stmt = $conn->prepare("INSERT INTO tasks (created_by,assigned_to,category_id,title,description,due_date,priority,recurring) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('iiisssss', $uid, $assigned, $cat, $title, $desc, $due, $priority, $recurring);
    $stmt->execute();
    $newId = $conn->insert_id;
    // Send email notification to assigned user
    if ($assigned && $assigned !== $uid) {
        sendAssignEmail($conn, $assigned, $title, $due);
    }
    echo json_encode(['ok'=>true,'id'=>$newId]); exit;
}

if ($action === 'update_task') {
    $id        = (int)($_POST['id'] ?? 0);
    $title     = trim($_POST['title'] ?? '');
    $desc      = trim($_POST['description'] ?? '');
    $due       = $_POST['due_date'] ?? null;
    $priority  = $_POST['priority'] ?? 'medium';
    $progress  = (int)($_POST['progress'] ?? 0);
    $note      = trim($_POST['progress_note'] ?? '');
    $done      = (int)($_POST['done'] ?? 0);
    $assigned  = (int)($_POST['assigned_to'] ?? 0) ?: null;
    $cat       = (int)($_POST['category_id'] ?? 0) ?: null;
    $recurring = $_POST['recurring'] ?? 'none';
    if (empty($due)) $due = null;
    $stmt = $conn->prepare("UPDATE tasks SET title=?,description=?,due_date=?,priority=?,progress=?,progress_note=?,done=?,assigned_to=?,category_id=?,recurring=? WHERE id=?");
    $stmt->bind_param('ssssiisisssi', $title,$desc,$due,$priority,$progress,$note,$done,$assigned,$cat,$recurring,$id);
    $stmt->execute();
    echo json_encode(['ok'=>true]); exit;
}

if ($action === 'delete_task') {
    $id = (int)($_POST['id'] ?? 0);
    // Only creator or admin can delete
    $row = $conn->query("SELECT created_by FROM tasks WHERE id=$id")->fetch_assoc();
    if (!$row) { echo json_encode(['error'=>'Not found']); exit; }
    if ($row['created_by'] != $uid && $role !== 'admin') { echo json_encode(['error'=>'Not allowed']); exit; }
    $conn->query("DELETE FROM tasks WHERE id=$id");
    echo json_encode(['ok'=>true]); exit;
}

if ($action === 'toggle_done') {
    $id = (int)($_POST['id'] ?? 0);
    $conn->query("UPDATE tasks SET done=1-done WHERE id=$id");
    // Handle recurring — if done, create next occurrence
    $task = $conn->query("SELECT * FROM tasks WHERE id=$id")->fetch_assoc();
    if ($task && $task['done'] == 1 && $task['recurring'] !== 'none' && $task['due_date']) {
        $next = new DateTime($task['due_date']);
        if ($task['recurring'] === 'daily')   $next->modify('+1 day');
        if ($task['recurring'] === 'weekly')  $next->modify('+1 week');
        if ($task['recurring'] === 'monthly') $next->modify('+1 month');
        $nd = $next->format('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO tasks (created_by,assigned_to,category_id,title,description,due_date,priority,recurring) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('iiisssss', $task['created_by'],$task['assigned_to'],$task['category_id'],$task['title'],$task['description'],$nd,$task['priority'],$task['recurring']);
        $stmt->execute();
    }
    echo json_encode(['ok'=>true]); exit;
}

// ── COMMENTS ─────────────────────────────────────────────────────────
if ($action === 'get_comments') {
    $tid  = (int)($_GET['task_id'] ?? 0);
    $stmt = $conn->prepare("SELECT c.*,u.username FROM comments c JOIN users u ON c.user_id=u.id WHERE c.task_id=? ORDER BY c.created_at ASC");
    $stmt->bind_param('i', $tid);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC)); exit;
}
if ($action === 'add_comment') {
    $tid  = (int)($_POST['task_id'] ?? 0);
    $body = trim($_POST['body'] ?? '');
    if (!$body) { echo json_encode(['error'=>'Empty comment']); exit; }
    $stmt = $conn->prepare("INSERT INTO comments (task_id,user_id,body) VALUES (?,?,?)");
    $stmt->bind_param('iis', $tid, $uid, $body);
    $stmt->execute();
    echo json_encode(['ok'=>true,'id'=>$conn->insert_id]); exit;
}
if ($action === 'delete_comment') {
    $id  = (int)($_POST['id'] ?? 0);
    $row = $conn->query("SELECT user_id FROM comments WHERE id=$id")->fetch_assoc();
    if ($row && ($row['user_id'] == $uid || $role === 'admin')) {
        $conn->query("DELETE FROM comments WHERE id=$id");
        echo json_encode(['ok'=>true]);
    } else { echo json_encode(['error'=>'Not allowed']); }
    exit;
}

// ── EMAIL HELPER ─────────────────────────────────────────────────────
function sendAssignEmail($conn, $userId, $taskTitle, $dueDate) {
    $row = $conn->query("SELECT email,username FROM users WHERE id=$userId")->fetch_assoc();
    if (!$row || !$row['email']) return;
    $to      = $row['email'];
    $name    = $row['username'];
    $due     = $dueDate ? "Due: $dueDate" : 'No due date';
    $subject = "Task assigned to you: $taskTitle";
    $message = "Hi $name,\n\nA new task has been assigned to you:\n\nTask: $taskTitle\n$due\n\nLog in to view details.\n\nTask Calendar";
    $headers = "From: noreply@taskcalendar.app";
    @mail($to, $subject, $message, $headers);
}

echo json_encode(['error'=>'Unknown action']);
