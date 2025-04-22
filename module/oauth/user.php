<?PHP $USER_KEY =''; $TOKEN_KEY ='';
$expired_cookie = time() + 60 * 60 * 24 * 30;
if(empty($_COOKIE['USER_KEY'])){
	echo'Tidak ada Cokies dari user';
	setcookie("USER_KEY", "", time()-3600);
    setcookie("TOKENR_KEY", "", time()-3600);
    setcookie('USER_KEY', '', 0, '/');
    setcookie('TOKEN_KEY', '', 0, '/');
	exit();
}
else{
    if(!empty($_COOKIE['USER_KEY'])){ $USER_KEY	=  convert("decrypt", $_COOKIE['TOKEN_KEY']);}
    if(!empty($_COOKIE['TOKEN_KEY'])){ $TOKEN_KEY  =  convert("decrypt", $_COOKIE['TOKEN_KEY']);}
	$query_user = "SELECT user.*,posisi.posisi_nama FROM user
	LEFT JOIN posisi ON posisi.posisi_id = user.posisi_id WHERE user.active='Y' AND user.user_id='$USER_KEY'";
    $result_user = $connection->query($query_user);
	if($result_user->num_rows > 0){
		$data_user     = $result_user->fetch_assoc();
		extract($data_user);

		$update_online = "UPDATE user SET time='$date $time_sekarang', status='Online' WHERE user_id='$data_user[user_id]'";
		$connection->query($update_online);
		
		$query_online  = "SELECT tanggal_login,time FROM user WHERE status='Online' AND active='Y' AND TIMESTAMPDIFF(MINUTE, time, NOW()) > 2";
		$result_online = $connection->query($query_online);
		if($result_online->num_rows > 0){
			$data_online = $result_online->fetch_assoc();	

			$update_online = "UPDATE user SET status='Offline' WHERE status='Online' AND TIMESTAMPDIFF(MINUTE, time, NOW()) > 2";
			$connection->query($update_online);
		}else{}

	}else{
		echo 'Tidak ada user yang Login';
		setcookie('USER_KEY', '', 0, '/');
		setcookie('TOKEN_KEY', '', 0, '/');
		setcookie("USER_KEY", "", time()-$expired_cookie);
    	setcookie("TOKEN_KEY", "", time()-$expired_cookie);
		exit();
	}

}