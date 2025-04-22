<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once '../../../sw-library/sw-config.php';
    require_once '../../../sw-library/sw-function.php';

    if(isset($_POST['modifikasi'])){
        $modifikasi = anti_injection($_POST['modifikasi']);
    }else{
        $modifikasi ='N';
    }

    if(isset($_POST['modifikasi'])){
        $hapus = anti_injection($_POST['hapus']);
    }else{
        $hapus ='N';
    }

    $aColumns = ['cuti_id', 'user_id', 'jenis','tanggal_mulai','tanggal_selesai','jumlah','keterangan', 'atasan','files','time', 'date','status'];
    $sIndexColumn = "cuti_id";
    $sTable = "cuti";
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

    $sOrder = "ORDER BY cuti_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY cuti_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY cuti_id DESC")
        {
            $sOrder = "ORDER BY cuti_id DESC";
        }
    }

    $sWhere = "";
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

    $sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM $sTable
        $sWhere
        $sOrder
        $sLimit ";
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
       // "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

$no = 0;
while ($aRow = mysqli_fetch_array($rResult)){$no++;
    extract($aRow);
    $row = array();

    for ($i=1 ; $i<count($aColumns) ; $i++){
        
        $query_pegawai = "SELECT user_id,nama_lengkap,posisi_id FROM user WHERE user_id='$aRow[user_id]'";
        $result_pegawai = $connection->query($query_pegawai);
        if($result_pegawai->num_rows > 0) {
            $data_pegawai = $result_pegawai->fetch_assoc();
            $nama_lengkap = $data_pegawai['nama_lengkap'];

            $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_pegawai[posisi_id]' AND active='Y'";
            $result_hak_cuti = $connection->query($query_hak_cuti);
            if($result_hak_cuti->num_rows > 0){
                $data_cuti = $result_hak_cuti->fetch_assoc();
                $jumlah_hak_cuti = $data_cuti['jumlah'];

                $query_cuti ="SELECT cuti_id FROM cuti WHERE user_id='$aRow[user_id]' AND status='Y'";
                $result_cuti = $connection->query($query_cuti);

                $sisa_cuti = $jumlah_hak_cuti - $result_cuti->num_rows;
            }else{
                $sisa_cuti = $jumlah_hak_cuti;
            }

        }else{
            $nama_lengkap ='';
        }

        if($aRow['files']==''){
            $files ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="40">';
        }else{
            if(!file_exists('../../../sw-content/cuti/'.$aRow['files'].'')){
            $files ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="40">';
            }else{
            $files ='<a class="open-popup-link" href="../sw-content/cuti/'.strip_tags($aRow['files']).'" target="_blank">
            <img src="../sw-content/cuti/'.strip_tags($aRow['files']).'" class="imaged w100 rounded" height="40">
            </a>';
            }
        }

        if($aRow['status'] == 'Y'){
            $status ='<span class="text-info">Disetujui</span>';
        }elseif($aRow['status'] == 'N'){
            $status ='<span class="text-danger">Ditolak</span>';
        }else{
            $status ='<span class="text-warning">Panding</span>';
        }

        if($aRow['status']=='N' OR $aRow['status']=='-'){
            $btn_update ='<a href="javascript:void(0)" class="table-action table-action-primary btn-update btn-dropdown-update btn-tooltip" data-toggle="tooltip" data-placement="right" title="Edit" data-id="'.epm_encode($aRow['cuti_id']).'">
            <i class="fas fa-edit"></i></a>';
        }else{
            $btn_update ='<a href="javascript:void(0)" class="table-action table-action-primary btn-tooltip btn-error" data-toggle="tooltip"  data-placement="right" title="Edit">
            <i class="fas fa-edit"></i>';
        }

        if($hapus =='Y'){
            $btn_hapus ='<a href="javascript:void(0)" class="table-action table-action-delete btn-tooltip btn-delete" data-toggle="tooltip" data-placement="right" title="Hapus" data-id="'.epm_encode($aRow['cuti_id']).'">
            <i class="fas fa-trash"></i>
            </a>';
           }else{
           $btn_hapus ='<a href="javascript:void(0)" class="table-action table-action-delete btn-tooltip btn-error" data-toggle="tooltip" data-placement="right" title="Hapus">
            <i class="fas fa-trash"></i>
           </a>';
           }

        $row[] = '<div class="text-center">'.$no.'</div>';
        $row[] = $nama_lengkap;
        $row[] = ''.tanggal_ind($aRow['tanggal_mulai']).' s/d '.tanggal_ind($aRow['tanggal_selesai']).'';
        $row[] = strip_tags($aRow['jenis']);
        $row[] = '<div class="text-center"><span class="badge badge-warning">'.$aRow['jumlah'].'</span></div>';
        $row[] = '<div class="text-center"><span class="badge badge-info">'.$sisa_cuti.'</span></div>';
        $row[] = strip_tags($aRow['keterangan']);
        $row[] = '<div class="text-center">'.$files.'</div>';
        $row[] = tanggal_ind($aRow['date']);
        $row[] = '<div class="text-center">
                    <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdown'.$aRow['cuti_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        '.$status.'
                    </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown'.$aRow['cuti_id'].'">
                            <a class="dropdown-item btn-status" href="#" data-id="'.epm_encode($aRow['cuti_id']).'" data-status="Y">Setujui</a>
                            <a class="dropdown-item btn-status-tolak" href="#" data-id="'.epm_encode($aRow['cuti_id']).'" data-status="N">Tolak</a>
                        </div>
                    </div>
                </div>';
        $row[] = '<div class="text-center">
                    <a href="javacript:void(0);" class="table-action table-action-primary text-warning btn-print" data-id="'.epm_encode($aRow['cuti_id']).'" data-toggle="tooltip" data-placement="left" title="Print"><i class="fas fa-print"></i></a>
                   '.$btn_update.'
                    '.$btn_hapus.'
                </div>';
    }
    $output['aaData'][] = $row;   
}
    echo json_encode($output);
  
}