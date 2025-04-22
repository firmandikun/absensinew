<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';
  if(htmlspecialchars($_GET['id']) == 1){
    $query_libur ="SELECT * FROM libur ORDER BY libur_id ASC";
    $result_libur = $connection->query($query_libur);
    echo'
    <h4 class="mt-1 mb-2">LIBUR KANTOR</h4>
      <table class="table align-items-center table-striped" style="width:100%">
        <thead class="thead-light">
          <tr>
            <th>Hari</th>
            <th class="text-center">Aktif</th>
          </tr>
        </thead>
        <tbody>';
        while ($data_libur = $result_libur->fetch_assoc()){
          if($data_libur['active'] =='Y'){
            $status = '<label class="custom-toggle" style="display:inline-block">
            <input type="checkbox" class="btn-active active'.$data_libur['libur_id'].'" data-id="'.$data_libur['libur_id'].'" data-active="Y" checked>
                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';
          }else{
              $status = '<label class="custom-toggle" style="display:inline-block">
              <input type="checkbox" class="btn-active active'.$data_libur['libur_id'].'"  data-id="'.$data_libur['libur_id'].'"  data-active="N">
              <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';
          }

        echo'
          <tr>
            <td>'.strip_tags($data_libur['libur_hari']).'</td>
            <td class="text-center">'.$status.'</td>
          </tr>';
        }
        echo'
        </tbody>
      </table>';
  }


  else if(htmlspecialchars($_GET['id']) == 2){
    $no =0;
    $query_libur="SELECT * FROM libur_nasional ORDER BY libur_nasional_id DESC";
    $result_libur = $connection->query($query_libur);

    echo'
      <div class="mb-2" style="display:flow-root">
        <h4 class="mt-1 mb-2 float-left">LIBUR NASIONAL</h4>
        <div class="float-right">
          <button class="btn btn-primary btn-add"><i class="fas fa-plus"></i> Tambah</button>
        </div>
      </div>
      <hr class="mb-3 mt-1">
     
      <table class="table align-items-center table-striped datatable" id="datatable-basic" style="width:100%">
          <thead class="thead-light">
            <tr>
              <th class="text-center">No</th>
              <th>Hari</th>
              <th>Keterangan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>';
          if($result_libur->num_rows > 0){
            while($data_libur = $result_libur->fetch_assoc()){$no++;
              echo'
              <tr>
                <td class="text-center">'.$no.'</td>
                <td>'.format_hari_tanggal($data_libur['libur_tanggal']).'</td>
                <td>'.strip_tags($data_libur['keterangan']).'</td>
                <td class="text-center">
                    <a href="javascript:void(0)" class="table-action table-action-primary btn-update btn-tooltip" data-toggle="tooltip"  data-placement="right" title="Edit" data-id="'.$data_libur['libur_nasional_id'].'" data-tanggal="'.tanggal_ind($data_libur['libur_tanggal']).'" data-keterangan="'.strip_tags($data_libur['keterangan']).'">
                    <i class="fas fa-edit"></i>
                    </a>
                  <a href="javascript:void(0)" class="table-action table-action-delete btn-tooltip btn-delete" data-toggle="tooltip" data-placement="right" title="Hapus" data-id="'.epm_encode($data_libur['libur_nasional_id']).'">
                      <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>';
            }
          
          }else{
            echo'
            <tr>
              <td colspan="4" class="text-center">Belum ada data libur nasional</td>
            </tr>';
          }
          echo'
          </tbody>
      </table>';?>

      <script type="text/javascript">
       
       $('.datatable').DataTable({
          language: {
            paginate: {
              previous: "<i class='fas fa-angle-left'>",
              next: "<i class='fas fa-angle-right'>"
            }
          },
       });
      </script>
   
      <?php
  }

}