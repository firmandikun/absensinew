<?PHP require_once'../sw-library/sw-config.php';
require_once'../sw-library/sw-function.php';
require_once'../sw-library/phpqrcode/qrlib.php';

switch (@$_GET['action']){
case 'qrcode':

$id = anti_injection($_POST['id']);

$query_lokasi ="SELECT lokasi_id,lokasi_qrcode FROM lokasi WHERE lokasi_id='$id'";
$result_lokasi = $connection->query($query_lokasi);
if($result_lokasi->num_rows > 0){
    $data_lokasi = $result_lokasi->fetch_assoc();
    $qrcode = seo_title($data_lokasi['lokasi_qrcode']);
        if(file_exists('../sw-content/lokasi/'.seo_title($data_lokasi['lokasi_qrcode']).'.jpg')){
            $qrcode ='../sw-content/lokasi/'.$qrcode.'.jpg';
            unlink ($qrcode);
        }
        /* --  End Random Karakter ---- */
        $codeContents = time();
        $tempdir = '../sw-content/lokasi/';
        $namafile = ''.seo_title($codeContents).'.jpg';
        $quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
        $ukuran = 10; //batasan 1 paling kecil, 10 paling besar
        $padding = 1;
        QRCode::png($codeContents,$tempdir.$namafile,$quality,$ukuran,$padding);

        $update="UPDATE lokasi SET lokasi_qrcode='$codeContents' WHERE lokasi_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo $namafile;
        }

}else{
    echo'Data tidak ditemukan';
}


break;
}