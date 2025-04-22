<?php session_start();
include_once '../sw-library/sw-config.php';
require_once '../sw-library/sw-function.php';
include_once '../google/google-config.php';


if(isset($_GET['code'])){
	$gclient->authenticate($_GET['code']);
	$_SESSION['token'] = $gclient->getAccessToken();
	header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gclient->setAccessToken($_SESSION['token']);
}

if ($gclient->getAccessToken()) {
    

	// Get user profile data from google
	$gpuserprofile 	    = $google_oauthv2->userinfo->get();
	$nama_lengkap       = $gpuserprofile['given_name']." ".$gpuserprofile['family_name']; // Ambil nama dari Akun Google
	$email 			    = $gpuserprofile['email']; // Ambil email Akun Google nya
	// Buat query untuk mengecek apakah data user dengan email tersebut sudah ada atau belum
	// Jika ada, ambil id, username, dan nama dari user tersebut
        $query_user ="SELECT user_id,email FROM user WHERE email='$email'";
        $result_user = $connection->query($query_user);
        $data_user   = $result_user->fetch_assoc();
       
		    if(empty($data_user)){
				// Jika User dengan email tersebut belum ada
				// Ambil username dari kata sebelum simbol @ pada email
				//$ex = explode('@', $email); // Pisahkan berdasarkan "@"
				//$username = $ex[0]; // Ambil kata pertama
                $random_karakter = md5($nama_lengkap);
                $shuffle  = substr(str_shuffle($random_karakter),0,5);
                $qrcode   = ''.strtoupper($shuffle).'-'.$user_id.'';

				// Lakukan insert data user baru tanpa password
                $add ="INSERT INTO user(email,
                    password,
                    nip,
                    nama_lengkap,
                    tempat_lahir,
                    tanggal_lahir,
                    jenis_kelamin,
                    telp,
                    alamat,
                    lokasi_id,
                    posisi_id,
                    qrcode,
                    avatar,
                    tanggal_registrasi,
                    tanggal_login,
                    time,
                    ip,
                    browser,
                    status,
                    active) values('$email',
                    '',
                    '$qrcode',
                    '$nama_lengkap',
                    '', /** Tempat Lahir */
                    '$date', /** Tanggal Lahir */
                    '', /** Jenis Kelamin */
                    '', /** No. Telp */
                    '', /** Alamat */
                    '', /** Lokasi */
                    '', /** Posisi */
                    '$qrcode', 
                    'avatar.jpg',
                    '$date $time',
                    '$date $time',
                    '$date $time',
                    '$ip',
                    '$browser',
                    'Offline',
                    'Y')";

		        $connection->query($add);
				$id = mysqli_insert_id($connection); // Ambil id user yang baru saja di insert
			}else{
				$id 				= convert("encrypt", strip_tags($data_user['user_id'])); 
                
			}

			setcookie('USER_KEY', $id, $expired_cookie, '/');
            setcookie('USER_KEY', $id, $expired_cookie, '/');
            setcookie('TOKEN_KEY', $id, $expired_cookie, '/');
    	    header("location:../");
    }else {
        $authUrl = $gclient->createAuthUrl();
        header("location: ".$authUrl);
    }
?>
