<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once '../../../sw-library/sw-config.php';
    require_once '../../../sw-library/sw-function.php';

    if(!empty($_POST['lokasi']) && !empty($_POST['pegawai']) && !empty($_POST['bulan']) && !empty($_POST['tahun'])){
        $lokasi     = htmlspecialchars($_POST['lokasi']);
        $pegawai    = strip_tags($_POST['pegawai']);
        $bulan      = strip_tags($_POST['bulan']);
        $tahun      = strip_tags($_POST['tahun']);
        $filter     = "WHERE lokasi_id='$lokasi' AND user_id='$pegawai' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";

    }elseif(!empty($_POST['pegawai']) && !empty($_POST['bulan']) && !empty($_POST['tahun'])){
        $pegawai    = strip_tags($_POST['pegawai']);
        $bulan      = strip_tags($_POST['bulan']);
        $tahun      = strip_tags($_POST['tahun']);
        $filter     = "WHERE lokasi_id='$lokasi' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";

    }elseif(!empty($_POST['pegawai']) && !empty($_POST['bulan']) && !empty($_POST['tahun'])){
        $pegawai    = strip_tags($_POST['pegawai']);
        $bulan      = strip_tags($_POST['bulan']);
        $tahun      = strip_tags($_POST['tahun']);
        $filter     = "WHERE user_id='$pegawai' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
    
    } else{
        $bulan = strip_tags($_POST['bulan']);
        $tahun = strip_tags($_POST['tahun']);
        $filter = "WHERE MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
    }

    $aColumns = ['kunjungan_id', 'user_id', 'lokasi', 'keterangan', 'foto','date'];
    $sIndexColumn = "kunjungan_id";
    $sTable = "kunjungan";
    $gaSql['user'] = DB_USER;
    $gaSql['password'] = DB_PASSWD;
    $gaSql['db'] = DB_NAME;
    $gaSql['server'] = DB_HOST;

    $gaSql['link'] =  new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']);

    $sLimit = "";
    if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1')
    {
        $sLimit = "LIMIT ".mysqli_real_escape_string($gaSql['link'], $_POST['iDisplayStart']).", ".
            mysqli_real_escape_string($gaSql['link'], $_POST['iDisplayLength']);
    }

    $sOrder = "ORDER BY kunjungan_id DESC";
    if (isset($_POST['iSortCol_0']))
    {
        $sOrder = "ORDER BY kunjungan_id DESC";
        for ($i=0; $i<intval($_POST['iSortingCols']) ; $i++)
        {
            if ($_POST['bSortable_'.intval($_POST['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_POST['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_POST['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY kunjungan_id DESC")
        {
            $sOrder = "ORDER BY kunjungan_id DESC";
        }
    }

    $sWhere ="".$filter."";
    if (isset($_POST['sSearch']) && $_POST['sSearch'] != "")
    {
        $sWhere = "WHERE (";
        for ($i=0; $i<count($aColumns); $i++)
        {
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_POST['sSearch'])."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    for ($i=0 ; $i<count($aColumns); $i++)
    {
        if (isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '')
        {
            if ($sWhere == "")
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_POST['sSearch_'.$i])."%' ";
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
       // "sEcho" => intval($_POST['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
    while ($aRow = mysqli_fetch_array($rResult)){$no++;
      extract($aRow);
        $row = array();
        for ($i=1 ; $i<count($aColumns) ; $i++){
            
            $query_pegawai ="SELECT nip,nama_lengkap FROM user WHERE user_id='$aRow[user_id]'";
            $result_pegawai = $connection->query($query_pegawai);
            if($result_pegawai->num_rows > 0){
                $data_pegawai = $result_pegawai->fetch_assoc();
                $nama_pegawai = strip_tags($data_pegawai['nama_lengkap']);
            }else{
                $nama_pegawai = '-';
            }
            

            if($aRow['foto']==''){
                $foto ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
            }else{
                if(!file_exists('../../../sw-content/kunjungan/'.$aRow['foto'].'')){
                $foto ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
                }else{
                $foto ='<a class="open-popup-link" href="../sw-content/kunjungan/'.strip_tags($aRow['foto']).'" target="_blank">
                <img src="../sw-content/kunjungan/'.strip_tags($aRow['foto']).'" class="imaged w100 rounded" height="50">
                </a>';
                }
            }

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = $nama_pegawai;
            $row[] = tanggal_ind($aRow['date']);
            $row[] = strip_tags($aRow['lokasi']);
            $row[] = strip_tags($aRow['keterangan']);
            $row[] = '<div class="text-center">'.$foto.'</div>';
            $row[] = '<div class="text-center">
                        <a href="javascript:void(0)" class="table-action table-action-delete btn-delete" data-id="'.strip_tags(epm_encode($aRow['kunjungan_id'])).'" title="Hapus"><i class="fas fa-trash"></i></a>
                    </div>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}