<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once '../../../sw-library/sw-config.php';
    require_once '../../../sw-library/sw-function.php';

    if(!empty($_POST['lokasi']) && !empty($_POST['pegawai']) && !empty($_POST['bulan']) && !empty($_POST['tahun'])){
        $lokasi = strip_tags($_POST['lokasi']);
        $pegawai = strip_tags($_POST['pegawai']);
        $bulan = strip_tags($_POST['bulan']);
        $tahun = strip_tags($_POST['tahun']);
        $filter ="WHERE lokasi_id='$lokasi' AND user_id='$pegawai' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
        
    }elseif(!empty($_POST['lokasi']) && !empty($_POST['bulan']) && !empty($_POST['tahun'])){
        $lokasi = strip_tags($_POST['lokasi']);
        $bulan = strip_tags($_POST['bulan']);
        $tahun = strip_tags($_POST['tahun']);
        $filter ="WHERE lokasi_id='$lokasi' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
    } else{
        $bulan = strip_tags($_POST['bulan']);
        $tahun = strip_tags($_POST['tahun']);
        $filter = "WHERE MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
    }

    $aColumns = ['uraian_kerja_id', 'user_id', 'nama', 'tanggal', 'keterangan', 'files','date'];
    $sIndexColumn = "uraian_kerja_id";
    $sTable = "uraian_kerja";
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

    $sOrder = "ORDER BY uraian_kerja_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY uraian_kerja_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY uraian_kerja_id DESC")
        {
            $sOrder = "ORDER BY uraian_kerja_id DESC";
        }
    }

    $sWhere ="".$filter."";
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
            
            $query_pegawai ="SELECT nip,nama_lengkap,posisi_nama FROM user 
            INNER JOIN posisi ON user.posisi_id = posisi.posisi_id WHERE user.user_id='$aRow[user_id]'";
            $result_pegawai = $connection->query($query_pegawai);
            $data_pegawai = $result_pegawai->fetch_assoc();

            if($aRow['files'] ==''){
                $foto ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
            }else{
                if(!file_exists('../../../sw-content/laporan-kerja/'.$aRow['files'].'')){
                    $foto ='<img src="../sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
                }else{
                    $foto ='<a class="open-popup-link" href="../sw-content/laporan-kerja/'.strip_tags($aRow['files']).'" target="_blank">
                    <img src="../sw-content/laporan-kerja/'.strip_tags($aRow['files']).'" class="imaged w100 rounded" height="50">
                    </a>';
                }
            }

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = strip_tags($data_pegawai['nama_lengkap']);
            $row[] = strip_tags($data_pegawai['posisi_nama']);
            $row[] = tanggal_ind($aRow['tanggal']);
            $row[] = strip_tags($aRow['nama']);
            $row[] = strip_tags($aRow['keterangan']);
            $row[] = '<div class="text-center">'.$foto.'</div>';
            $row[] = '<div class="text-center">
                    <a href="javascript:void(0)" class="table-action table-action-delete btn-delete" data-id="'.strip_tags(epm_encode($aRow['uraian_kerja_id'])).'" title="Hapus"><i class="fas fa-trash"></i></a>
                </div>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}