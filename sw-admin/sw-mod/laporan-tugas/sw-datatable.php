<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    
    require_once'../../../sw-library/sw-config.php';
    require_once'../../../sw-library/sw-function.php';

    if(!empty($_GET['pegawai']) && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
        $pegawai = strip_tags($_GET['pegawai']);
        $bulan = strip_tags($_GET['bulan']);
        $tahun = strip_tags($_GET['tahun']);
        $filter ="WHERE user_id='$pegawai' AND MONTH(tanggal) ='$bulan' AND YEAR(tanggal) ='$tahun'";
    } else{
        $bulan = strip_tags($_GET['bulan']);
        $tahun = strip_tags($_GET['tahun']);
        $filter = "WHERE MONTH(tanggal) ='$bulan' AND YEAR(tanggal) ='$tahun'";
    }


    $aColumns = ['tugas_id', 'user_id','tanggal','keterangan'];
    $sIndexColumn = "tugas_id";
    $sTable = "tugas";
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

    $sOrder = "ORDER BY tugas_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY tugas_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY tugas_id DESC")
        {
            $sOrder = "ORDER BY tugas_id DESC";
        }
    }

    $sWhere = "".$filter."";
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
        for ($i=1 ; $i<count($aColumns); $i++){
            $onlick = "','";
            $onlick = explode(",",$onlick);

            $query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE user_id='$aRow[user_id]'";
            $result_pegawai = $connection->query($query_pegawai);
            if($result_pegawai->num_rows > 0) {
                $data_pegawai = $result_pegawai->fetch_assoc();
                $nama_lengkap = $data_pegawai['nama_lengkap'];
            }else{
                $nama_lengkap ='';
            }

            $query_tugas = "SELECT uraian_kerja_id,tanggal,keterangan,files FROM uraian_kerja WHERE tugas_id='$aRow[tugas_id]' AND user_id='$aRow[user_id]'";
            $result_tugas = $connection->query($query_tugas);
            if($result_tugas->num_rows > 0) { 
                $data_tugas = $result_tugas->fetch_assoc();
                $jawaban_tugas = strip_tags($data_tugas['keterangan']);
                $tanggal_tugas = tanggal_ind($data_tugas['tanggal']);

                if(file_exists('../../../sw-content/tugas/'.$data_tugas['files'].'')){
                $foto = '<a class="open-popup-link" href="data:image/gif;base64,'.base64_encode(file_get_contents('../../../sw-content/tugas/'.$data_tugas['files'].'')).'">
                    <img src="data:image/gif;base64,'.base64_encode(file_get_contents('../../../sw-content/tugas/'.$data_tugas['files'].'')).'" height="40">
                </a>';
                }else{
                    $foto='<img src="../sw-content/thumbnail.jpg" height="30">';
                }

                $hapus ='<a href="javascript:void(0)" class="table-action table-action-delete btn-tooltip btn-delete" data-toggle="tooltip" data-placement="right" title="Hapus" data-id="'.epm_encode($data_tugas['uraian_kerja_id']).'">
                    <i class="fas fa-trash"></i>
                </a>';
            }else{
                $jawaban_tugas = '<span class="text-danger"><i class="fas fa-times"></i></span>';
                $foto = '<span class="text-danger"><i class="fas fa-times"></i></span>';
                $tanggal_tugas ='<span class="text-danger"><i class="fas fa-times"></i></span>';
                $hapus = '';
            }

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = strip_tags($nama_lengkap);
            $row[] = strip_tags($aRow['keterangan']);
            $row[] = '<div class="text-center">'.$foto.'</div>';
            $row[] = $jawaban_tugas;
            $row[] = $tanggal_tugas;
            $row[] = '<div class="text-center">'.$hapus.'</div>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}