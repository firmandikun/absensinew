<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once '../../../sw-library/sw-config.php';
    require_once '../../../sw-library/sw-function.php';

    if(!empty($_GET['pegawai']) && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
        $pegawai = strip_tags($_GET['pegawai']);
        $bulan = strip_tags($_GET['bulan']);
        $tahun = strip_tags($_GET['tahun']);
        $filter ="WHERE user_id='$pegawai' AND MONTH(tanggal_in) ='$bulan' AND YEAR(tanggal_in) ='$tahun'";
    } else{
        $bulan = strip_tags($_GET['bulan']);
        $tahun = strip_tags($_GET['tahun']);
        $filter = "WHERE MONTH(tanggal_in) ='$bulan' AND YEAR(tanggal_in) ='$tahun'";
    }

    $aColumns = ['overtime_id', 'user_id', 'tanggal_in', 'tanggal_out', 'absen_in', 'absen_out','latitude_in','latitude_out', 'keterangan', 'status'];
    $sIndexColumn = "overtime_id";
    $sTable = "overtime";
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

    $sOrder = "ORDER BY overtime_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY overtime_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY overtime_id DESC")
        {
            $sOrder = "ORDER BY overtime_id DESC";
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

            if(!$aRow['tanggal_out'] =='0000-00-00'){
                $durasi_mulai = new DateTime(''.$aRow['tanggal_in'].' '.$aRow['absen_in'].'');
                $durasi_selesai = new DateTime(''.$aRow['tanggal_out'].' '.$aRow['absen_out'].'');
                $durasi = $durasi_mulai->diff($durasi_selesai);
                $durasi_kerja  = $durasi->format('%H jam %i menit');
            }else{
                $durasi_kerja = '-';
            }

            if($aRow['status']=='1'){
                $status ='<button type="button" id="set'.$aRow['overtime_id'].'" data-id="'.$aRow['overtime_id'].'" class="btn btn-outline-default btn-sm setactive" data-active=""N>Padding</button>';
            }elseif($aRow['status']=='Y'){
                $status ='<button id="set'.$aRow['overtime_id'].'" data-id="'.$aRow['overtime_id'].'" class="btn btn-outline-success btn-sm setactive" data-active="Y">Setujui</button>';
              }else{
                $status ='<button type="button" id="set'.$aRow['overtime_id'].'" data-id="'.$aRow['overtime_id'].'" class="btn btn-outline-danger btn-sm setactive" data-active="N">Tidak Disetujui</button>';
              }

              if(!$aRow['tanggal_out'] =='0000-00-00'){
                $tanggal_pulang ='s/d '.tanggal_ind($aRow['tanggal_out']).'';
              }else{
                $tanggal_pulang = '';
              }

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = strip_tags($data_pegawai['nama_lengkap']);
            $row[] = strip_tags($data_pegawai['posisi_nama']);
            $row[] = ''.tanggal_ind($aRow['tanggal_in']).' '.$tanggal_pulang.'';
            $row[] = ''.$aRow['absen_in'].'';
            $row[] = ''.$aRow['absen_out'].'';
            $row[] = $durasi_kerja;
            $row[] = $status;
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}