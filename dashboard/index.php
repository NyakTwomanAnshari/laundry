<?php
require("../init.php");
helper(["auth", "basicCrud"]);
cekLogin();

// ambil jmlh pelanggan
$t_pelanggan = getAllData("tb_pelanggan", false, NULL, NULL, "INNER", "COUNT(*) AS t_pelanggan")[0]["t_pelanggan"];

// ambil jumlah pesanan proses
$p_proses = getOneData("tb_transaksi", "status_transaksi" , "proses", false, null, null, "inner", "COUNT(*) AS p_proses")["p_proses"];
/*
metode store procedure
include("../core/koneksi.php");
$p_proses = mysqli_query($conDB, "CALL p_proses()");
$p_proses = mysqli_fetch_assoc($p_proses)["p_proses"];
*/

// ambil jumlah pesanan selesai
$p_selesai = getOneData("tb_transaksi", "status_transaksi" , "selesai", false, null, null, "inner", "COUNT(*) AS p_selesai")["p_selesai"];
/*
metode store procedure
include("../core/koneksi.php");
$p_selesai = mysqli_query($conDB, "CALL p_selesai()");
$p_selesai = mysqli_fetch_assoc($p_selesai)["p_selesai"];
*/

// ambil jumlah pesanan dianbil
$p_diambil = getOneData("tb_transaksi", "status_transaksi" , "diambil", false, null, null, "inner", "COUNT(*) AS p_diambil")["p_diambil"];
/*
metode store procedure 
include("../core/koneksi.php");
$p_diambil = mysqli_query($conDB, "SELECT COUNT(*) AS p_diambil FROM tb_transaksi WHERE status_transaksi = 'diambil' ") or die(mysqli_error($conDB));
$p_diambil = mysqli_fetch_array($p_diambil)["p_diambil"];
*/

// format variabel
$t_pelanggan = number_format($t_pelanggan, 0, ".", ".");
$p_proses = number_format($p_proses, 0, ".", ".");
$p_selesai = number_format($p_selesai, 0, ".", ".");
$p_diambil = number_format($p_diambil, 0, ".", ".");

// ambil data statistik
$arrBln = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$query_pemasukkan = mysqli_query($conDB, "SELECT SUM(total_bayar) AS jmlh, MONTHNAME(tgl_bayar) AS bln FROM tb_transaksi WHERE status_bayar = 'lunas' AND YEAR(tgl_bayar) = YEAR(NOW()) GROUP BY bln ") or die(mysqli_error($conDB));

$data_pemasukkan = [];
while($r = mysqli_fetch_assoc($query_pemasukkan)){
  $data_pemasukkan[] = $r;
}
$data_chart_value = [];
for($i = 0; $i < 12; $i++){
  $valBln = 0;
  foreach($data_pemasukkan as $dtpmsk){
    if($dtpmsk["bln"] == $arrBln[$i]){
      $valBln = $dtpmsk["jmlh"];
    }
  }
  $data_chart_value[] = $valBln;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Dashboard</title>
  
  <!-- basic css -->
  <?php include("../template/header.php"); ?>
  
  <!-- css tambahan -->
  
  
</head>
<body>
  
  <div id="app">
    <div class="main-wrapper">
      
      <!-- navbar -->
      <?php include("../template/navbar.php"); ?>
      
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard </a></div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Pelanggan</h4>
                  </div>
                  <div class="card-body">
                    <?= $t_pelanggan; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="fas fa-tshirt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Cucian Dalam Proses</h4>
                  </div>
                  <div class="card-body">
                    <?= $p_proses; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-tshirt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Cucian Siap Ambil</h4>
                  </div>
                  <div class="card-body">
                    <?= $p_selesai; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="fas fa-tshirt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Cucian telah dianbil</h4>
                  </div>
                  <div class="card-body">
                   <?= $p_diambil; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
              <div class="col-12  ">
                <div class="card">
                  <div class="card-header">
                    <h4>
                      Statistik Pemasukan Thn <?= date("Y"); ?>
                    </h4>
                  </div>
                  <div class="card-body">
                    <canvas id="myChart"></canvas>
                  </div>
                </div>
              </div>
          </div>
        </section>
      </div>
      
      <!-- copyright -->
      <?php include("../template/copyright.php"); ?>
    </div>
  </div>
  
  
  
  
  
  
  <!-- basic javascript -->
  <?php include("../template/footer.php"); ?>
  
  <!-- javascript tambahan -->
  <script src="../asset/js/chart.min.js" type="text/javascript" charset="utf-8"></script>
  
  <script type="text/javascript" charset="utf-8">
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels : <?= json_encode($arrBln); ?>,
        datasets: [{
            label: 'Jumlah Pemasukkan',
            data: <?= json_encode($data_chart_value); ?>,
            borderColor: 'rgba(103, 119, 238, 0.9)',
            tension: 0.2
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
  </script>
  
</body>
</html>