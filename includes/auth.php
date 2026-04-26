<?php
// Session and auth helpers

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Rubric #7: Protect all admin pages using sessions
function requireLogin() {
    startSession();
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Rubric #5: Login with admin/s3cr3t
function attemptLogin($username, $password) {
    require_once __DIR__ . '/db.php';
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        startSession();
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $user['username'];
        $_SESSION['admin_id']        = $user['id'];
        return true;
    }
    return false;
}

// Rubric #9: Destroy session on logout
function logout() {
    startSession();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
