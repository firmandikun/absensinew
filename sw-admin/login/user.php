<?php 
session_start();

if(empty($_COOKIE['ADMIN_KEY'])){
    setcookie("ADMIN_KEY", "", time()-3600);
    setcookie('ADMIN_KEY', '', 0, '/');
} else {
    if(isset($_COOKIE['ADMIN_KEY']) && isset($_COOKIE['KEY'])){
        $ADMIN_KEY = htmlentities(epm_decode($_COOKIE['ADMIN_KEY']));
        $KEY = htmlentities($_COOKIE['KEY']);
        $query_login = "SELECT * FROM admin WHERE admin_id='$ADMIN_KEY' AND active='Y'";
        $result_login = $connection->query($query_login);
        
        if($result_login->num_rows > 0){
            $current_user = $result_login->fetch_assoc();
            $admin_id = htmlentities($current_user['admin_id']);

            if($KEY === hash('sha256', $current_user['username'])){
                // Login Successful
                
                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['username'] = $current_user['username'];
                $_SESSION['level'] = $current_user['level'];
                $_SESSION['posisi'] = $current_user['posisi_id']; 
                $time_online = time();
                $update_admin = "UPDATE admin SET tanggal_login='$date $time_sekarang', time='$date $time_sekarang', status='Online' WHERE admin_id='$admin_id'";
                $connection->query($update_admin);
                
                /** Check who's online */
                $query_online = "SELECT tanggal_login,time FROM admin WHERE status='Online' AND active='Y' AND TIMESTAMPDIFF(MINUTE, time, NOW()) > 2";
                $result_online = $connection->query($query_online);
                
                if($result_online->num_rows > 0){
                    $data_online = $result_online->fetch_assoc();    
                    
                    $update_online = "UPDATE admin SET status='Offline' WHERE status='Online' AND TIMESTAMPDIFF(MINUTE, time, NOW()) > 2";
                    $connection->query($update_online);
                }
                
            } else {
                // Login doesn't match
                session_destroy();
                setcookie("ADMIN_KEY", "", time()-3600);
                setcookie('ADMIN_KEY', '', 0, '/');
                header('location:./login');
                exit();
            }
        } else {
            echo 'User not found during login';
            session_destroy();
            setcookie("ADMIN_KEY", "", time()-3600);
            setcookie('ADMIN_KEY', '', 0, '/');
            setcookie("KEY", "", time()-3600);
            setcookie('KEY', '', 0, '/');
            header('location:./login');
            exit();
        }
    }
}