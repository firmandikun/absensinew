<?php 
session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once'../../../sw-library/sw-config.php';
    require_once'../../../sw-library/sw-function.php';
    require_once'../../../sw-library/phpqrcode/qrlib.php'; 

    if(isset($_POST['modifikasi'])){
        $modifikasi = anti_injection($_POST['modifikasi']);
    }else{
        $modifikasi ='N';
    }

    if(isset($_POST['hapus'])){
        $hapus = anti_injection($_POST['hapus']);
    }else{
        $hapus ='N';
    }

    $aColumns = ['user_id', 'nip', 'nama_lengkap', 'email', 'tanggal_lahir', 'jenis_kelamin', 'telp', 'qrcode','avatar','status','active'];
    $sIndexColumn = "user_id";
    $sTable = "user";
    $gaSql['user'] = DB_USER;
    $gaSql['password'] = DB_PASSWD;
    $gaSql['db'] = DB_NAME;
    $gaSql['server'] = DB_HOST;

    $gaSql['link'] =  new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']);

    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
    {
        $sLimit = "LIMIT ".mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayStart']).", ".
            mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength']);
    }

    $sOrder = "ORDER BY user_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY user_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY user_id DESC")
        {
            $sOrder = "ORDER BY user_id DESC";
        }
    }

    $sWhere = "WHERE posisi.posisi_nama != 'admin'"; // Exclude admin positions
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
    {
        $sWhere = "WHERE (";
        for ($i=0; $i<count($aColumns); $i++)
        {
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch'])."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    for ($i=0 ; $i<count($aColumns); $i++)
    {
        if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
        {
            if ($sWhere == "")
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_'.$i])."%' ";
        }
    }

    $sQuery = " SELECT SQL_CALC_FOUND_ROWS user.*, posisi.posisi_nama 
               FROM $sTable 
               LEFT JOIN posisi ON user.posisi_id = posisi.posisi_id 
               $sWhere 
               $sOrder 
               $sLimit";
    $rResult = mysqli_query($gaSql['link'], $sQuery);

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultTotal = mysqli_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array( 
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
   // Inside your while loop where you generate table rows
while ($aRow = mysqli_fetch_array($rResult)) {
    if($aRow['status'] == 'Online'){
        $status = '<small class="badge badge-dot" style="font-size:13px;"><i class="bg-success"></i>Online</small>';
    } else {
        $status = '<small class="badge badge-dot" style="font-size:13px;"><i class="bg-danger"></i>Offline</small>';
    }

    // Initialize buttons
    $buttons = '<div class="text-center">
                <a href="javascript:void(0)" onClick="location.href=\'./user&op=profile&id='.epm_encode($aRow['user_id']).'\';" class="table-action table-action-warning btn-tooltip" data-toggle="tooltip" title="Profil Lengkap">
                    <i class="fas fa-user-check"></i>
                </a>';

    
    // Add delete button if deletion is allowed
    if($hapus == 'Y') {
        $buttons .= '<a href="javascript:void(0)" class="table-action table-action-danger btn-delete btn-tooltip" data-id="'.epm_encode($aRow['user_id']).'" data-name="'.$aRow['nama_lengkap'].'" data-toggle="tooltip" title="Hapus Data">
                        <i class="fas fa-trash-alt"></i>
                    </a>';
    }

    $buttons .= '</div>';

    $row = array();
    // Match columns with table headers
    $row[] = strip_tags($aRow['nip']).'<br>'.$status;  // NIP + status
    $row[] = '<b>'.strip_tags($aRow['nama_lengkap']).'</b>'; // Nama
    $row[] = strip_tags($aRow['email']); // Email
    $row[] = strip_tags($aRow['jenis_kelamin']); // Jenis Kelamin
    $row[] = '<div class="text-center">'.strip_tags($aRow['posisi_nama'] ?: '-').'</div>'; // Posisi/Jabatan
    $row[] = $buttons; // Aksi
    
    $output['aaData'][] = $row;
}
    echo json_encode($output);
  
}