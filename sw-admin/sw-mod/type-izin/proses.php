<?php
session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
} else {
  require_once'../../../sw-library/sw-config.php';
  include('../../../sw-library/sw-function.php');
  require_once'../../login/user.php';

  // DataTable Server Side Processing
  if(!isset($_GET['action'])){
    $columns = array( 
      0 => 'lain_lain_id',
      1 => 'nama', 
      2 => 'tipe'
    );

    $querycount = $connection->query("SELECT COUNT(lain_lain_id) AS jumlah FROM lain_lain");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];
    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if(empty($_POST['search']['value'])) {            
      $query = $connection->query("SELECT lain_lain_id,nama,tipe FROM lain_lain ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
      $search = $_POST['search']['value']; 
      $query = $connection->query("SELECT lain_lain_id,nama,tipe FROM lain_lain WHERE 
                                   nama LIKE '%$search%' OR 
                                   tipe LIKE '%$search%' 
                                   ORDER BY $order $dir LIMIT $limit OFFSET $start");

      $querycount = $connection->query("SELECT COUNT(lain_lain_id) AS jumlah FROM lain_lain WHERE 
                                       nama LIKE '%$search%' OR 
                                       tipe LIKE '%$search%'");
      $datacount = $querycount->fetch_array();
      $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if(!empty($query)) {
      $no = $start + 1;
      while ($r = $query->fetch_array()) {
        $nestedData['no'] = $no;
        $nestedData['nama'] = strip_tags($r['nama']);
        $nestedData['tipe'] = strip_tags($r['tipe']);
        
        $nestedData['aksi'] = "<div class='btn-group btn-group-sm' role='group'>";
        
        // Check permissions
        $query_role = "SELECT modifikasi,hapus FROM role WHERE modul_id='16' AND level_id='$current_user[level]'";
        $result_role = $connection->query($query_role);
        $data_role = $result_role->fetch_assoc();
        
        if($data_role['modifikasi'] == 'Y') {
          $nestedData['aksi'] .= "<button class='btn btn-warning btn-edit' data-id='".epm_encode($r['lain_lain_id'])."' title='Edit'><i class='fas fa-edit'></i></button>";
        }
        
        if($data_role['hapus'] == 'Y') {
          $nestedData['aksi'] .= "<button class='btn btn-danger btn-delete' data-id='".epm_encode($r['lain_lain_id'])."' title='Hapus'><i class='fas fa-trash'></i></button>";
        }
        
        $nestedData['aksi'] .= "</div>";
        
        $data[] = $nestedData;
        $no++;
      }
    }

    $json_data = array(
      "draw"            => intval($_POST['draw']),  
      "recordsTotal"    => intval($totalData),  
      "recordsFiltered" => intval($totalFiltered), 
      "data"            => $data   
    );

    echo json_encode($json_data);
  }
}
?>