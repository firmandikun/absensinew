<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
    require_once'../../../sw-library/sw-config.php';
    include('../../../sw-library/sw-function.php');
    $aColumns = ['posisi_id', 'posisi_nama'];
    $sIndexColumn = "posisi_id";
    $sTable = "posisi";
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

    $sOrder = "ORDER BY posisi_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY posisi_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY posisi_id DESC")
        {
            $sOrder = "ORDER BY posisi_id DESC";
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

    if ($sWhere == "") {
        $sWhere = "WHERE posisi_nama != 'admin'";
    } else {
        $sWhere .= " AND posisi_nama != 'admin'";
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
            $onlick = "','";
            $onlick = explode(",",$onlick);

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = strip_tags($aRow['posisi_nama']);
            $row[] = '<div class="text-center">
                        <a href="javascript:void(0)" class="table-action table-action-primary btn-update btn-tooltip" data-toggle="tooltip"  data-placement="right" title="Edit" data-id="'.$aRow['posisi_id'].'" data-name="'.strip_tags($aRow['posisi_nama']).'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="javascript:void(0)" class="table-action table-action-delete btn-tooltip btn-delete" data-toggle="tooltip" data-placement="right" title="Hapus" data-id="'.epm_encode($aRow['posisi_id']).'" data-name="'.strip_tags($aRow['posisi_nama']).'">
                            <i class="fas fa-trash"></i>
                        </a>
                  </div>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}