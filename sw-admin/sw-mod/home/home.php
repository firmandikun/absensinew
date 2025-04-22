<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}else{

$query_user  ="SELECT user_id FROM user";
$result_user =$connection->query($query_user);

$query_absen ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Hadir'";
$result_absen = $connection->query($query_absen);
$jumlah_absen = $result_absen->num_rows;

$query_artikel = "SELECT artikel_id FROM artikel";
$result_artikel = $connection->query($query_artikel);

$query_izin = "SELECT izin_id FROM izin";
$result_izin = $connection->query($query_izin);

$query_cuti = "SELECT cuti_id FROM cuti";
$result_cuti = $connection->query($query_cuti);

$jumlah_izin = $result_cuti->num_rows + $result_izin->num_rows;
echo'
<!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-12 col-12">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active">Dashboards</li>
                </ol>
              </nav>
            </div>
          </div>
          <!-- Card stats -->
          <div class="row">
          
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Absen hari ini</h5>
                      <span class="h2 font-weight-bold mb-0">'.$jumlah_absen.'</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="far fa-list-alt"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2">'.number_format($jumlah_absen/$result_user->num_rows*100,0).'%</span>
                    <span class="text-nowrap">Since today</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">User/Pegawai</h5>
                      <span class="h2 font-weight-bold mb-0">'.$result_user->num_rows.'</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="ni ni-circle-08"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap"><span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Artikel</h5>
                      <span class="h2 font-weight-bold mb-0">'.$result_artikel->num_rows.'</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                          <i class="fas fa-rss"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Izin & Cuti</h5>
                      <span class="h2 font-weight-bold mb-0">'.$jumlah_izin.'</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="fas fa-clipboard-list"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
    
  </div>';

        $date = date("d-m-Y",strtotime("-6 days"));
        $D = substr($date,0,2);
        $M = substr($date,3,2)-1;
        $Y = substr($date,6,4);
        $tgl_skrg = date("Y-m-d");
        $seminggu = strtotime("-1 week +1 day",strtotime($tgl_skrg));
        $hasilnya = date('Y-m-d', $seminggu);
        //visitor
        for ($i=0; $i<=6; $i++){
          $tgl_pengujung   = strtotime("+$i day",strtotime($hasilnya));
          $hasil_pengujung = date("Y-m-d", $tgl_pengujung);
          $tanggal_visitor []= tgl_ind($hasil_pengujung);
          
          $query_absensi ="SELECT absen_id FROM absen WHERE tanggal='$hasil_pengujung'";
          $result_absensi = $connection->query($query_absensi);
          $absensi [] = $result_absensi->num_rows;

          $query_overtime ="SELECT overtime_id FROM overtime WHERE tanggal_in='$hasil_pengujung'";
          $result_overtime = $connection->query($query_overtime);
          $overtime [] = $result_overtime->num_rows;

        }
        $tanggal_visitor = implode('","',$tanggal_visitor);?>

        <script src="sw-assets/vendor/chart.js/dist/Chart.min.js"></script>
        <script type="text/javascript">
          var ctx = document.getElementById("linechart").getContext("2d");
            var data = {
              labels :["<?php echo $tanggal_visitor;?>"],
                datasets: [
                {
                    label: "Absensi",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "#29B0D0",
                    borderColor: "#29B0D0",
                    pointHoverBackgroundColor: "#29B0D0",
                    pointHoverBorderColor: "#29B0D0",
                    data:<?php echo json_encode($absensi);?>
                    },

                    {
                    label: "Overtime",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "#f56036",
                    borderColor: "#f56036",
                    pointHoverBackgroundColor: "#f56036",
                    pointHoverBorderColor: "#f56036",
                    data:<?php echo json_encode($overtime);?>
                    },
                ]
            };
            
            var myBarChart = new Chart(ctx, {
              type: 'line',
              data: data,
              options: {
              legend: {
              display: true
            },
          
              scales: {
              yAxes: [{
              ticks: {
              min: 0,
            }
            }],
              xAxes: [{
              gridLines: {
              color: "rgba(0, 0, 0, 0)",
              }
            }]
            }}
        });
      </script>
      
<?php }
