<?php 
session_start();
require_once '../../sw-library/sw-config.php';
require_once '../../sw-library/sw-function.php';
$expired_cookie = time() + 60 * 60 * 24 * 30;

// Destroy all session data first
$_SESSION = array(); // Clear all session variables

// If it's desired to kill the session, also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

if(!empty($_COOKIE['USER_KEY'])) { 
    $USER_KEY = convert("decrypt", $_COOKIE['TOKEN_KEY']);
}

$query_user = "SELECT * FROM user WHERE active='Y' AND user_id='$USER_KEY'";
$result_user = $connection->query($query_user);

if($result_user->num_rows > 0) {
    $data_user = $result_user->fetch_assoc();

    /* ---------- Update Status Online --------- */
    $update_user = "UPDATE user SET status='Offline' WHERE user_id='$data_user[user_id]'";
    $connection->query($update_user);
    /* ---------- Update Status Online --------- */

    // Clear cookies
    setcookie('USER_KEY', '', 0, '/');
    setcookie('TOKEN_KEY', '', 0, '/');
    setcookie("USER_KEY", "", time() - $expired_cookie);
    setcookie("TOKEN_KEY", "", time() - $expired_cookie);

    header("location:./");
    exit();
} else {
    echo 'Data tidak ditemukan';
}
?>