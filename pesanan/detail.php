<?php
require("../init.php");
helper(["basicCrud", "auth"]);
adminKasir();

$id_transaksi = $_GET["id"];
$result = mysqli_query($conDB, "SELECT tb_transaksi.*, tb_outlet.nama_outlet, tb_pelanggan.nama_pelanggan, tb_paket.jenis, tb_user.nama_user FROM tb_transaksi JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id_outlet JOIN tb_pelanggan ON tb_transaksi.id_pelanggan = tb_pelanggan.id_pelanggan JOIN tb_paket ON tb_transaksi.id_paket = tb_paket.id_paket JOIN tb_user ON tb_transaksi.id_user = tb_user.id_user WHERE id_transaksi = '$id_transaksi' ");
$dt = mysqli_fetch_assoc($result);
 
if(!isset($id_transaksi) || $id_transaksi == "" || $dt == null ){
  return redirect("/pesanan/index.php");
}

// get data pakaian
$result2 = mysqli_query($conDB, "SELECT jenis_pakaian, jumlah FROM tb_pakaian WHERE id_transaksi = '$id_transaksi' ");
$dt_pakaian = [];
while($r = mysqli_fetch_assoc($result2)){
  $dt_pakaian[] = $r;
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>DETAIL CUCIAN</title>
  
  <!-- basic css -->
  <?php include("../template/header.php"); ?>
  
  <!-- css tambahan -->
  
  
</head>
<body>
  
  <div id="app">
    <div class="main-wrapper">
      <!-- navbar & slidebar -->
      <?php include("../template/navbar.php"); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Detail Cucian</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Cucian</a></div>
              <div class="breadcrumb-item">Detail Cucian</div>
            </div>
          </div>
          
          
          <div class="row">
            <div class="col-md-7 .col-sm-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4>
                    Detail Data
                  </h4>
                  <div class="card-header-action">
                    <a href="<?= base_url(); ?>/pesanan/index.php" class="btn btn-warning btn-icon icon-left">
                      <i class="fas fa-long-arrow-alt-left mr-2"></i>Kembali
                    </a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <div class="row mb-1">
                    <div class="col-5">
                      ID Transaksi
                    </div>
                    <div class="col-7">
                      <?= $dt["id_transaksi"]; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Nama Pelanggan
                    </div>
                    <div class="col-7">
                      <?= $dt["nama_pelanggan"]; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Nama Outlet
                    </div>
                    <div class="col-7">
                      <?= $dt["nama_outlet"]; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Nama Kasir
                    </div>
                    <div class="col-7">
                      <?= $dt["nama_user"]; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Jenis Paket
                    </div>
                    <div class="col-7">
                      <?= $dt["jenis"]; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Tgl Masuk
                    </div>
                    <div class="col-7">
                      <?= date("d M Y", strtotime($dt["tgl_masuk"])); ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Tgl Selesai
                    </div>
                    <div class="col-7">
                      <?= date("d M Y", strtotime($dt["tgl_selesai"])); ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Tgl Bayar
                    </div>
                    <div class="col-7">
                      <?php 
                        if($dt["tgl_bayar"] == null){
                          echo("-");
                        }else{
                          echo(date("d M Y", strtotime($dt["tgl_bayar"])));
                        }
                      ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Berat Pakaian
                    </div>
                    <div class="col-7">
                      <?= $dt["berat"]; ?>kg
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Total Harga
                    </div>
                    <div class="col-7">
                      Rp. <?= number_format($dt["total_bayar"], 0, '.', '.'); ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Status Bayar
                    </div>
                    <div class="col-7">
                      <?php if($dt["status_bayar"] == "belum") : ?>
                        <b class="text-danger">
                          <?= $dt["status_bayar"]; ?>
                        </b>
                      <?php else : ?>
                        <b class="text-success">
                          <?= $dt["status_bayar"]; ?>
                        </b>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-5">
                      Status Pesanan
                    </div>
                    <div class="col-7">
                     <?php if($dt["status_transaksi"] == "proses") : ?>
                        <span class="badge badge-info">
                          <?= $dt["status_transaksi"]; ?>
                        </span>
                     <?php elseif($dt["status_transaksi"] == "selesai") : ?>
                        <span class="badge badge-success">
                          <?= $dt["status_transaksi"]; ?>
                        </span>
                     <?php else : ?>
                        <span class="badge badge-danger">
                          <?= $dt["status_transaksi"]; ?>
                        </span>
                     <?php endif; ?>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            
            <div class="col-md-5 col-sm-12">
              <div class="card card-info">
                <div class="card-header">
                  <h4>
                    Detail Pakaian
                  </h4>
                  <div class="card-header-action">
                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                  </div>
                </div>
                <div class="collapse show" id="mycard-collapse">
                  <div class="card-body pt-0">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Jenis Pakaian
                            </th>
                            <th class="text-center">
                              Jumlah
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1; ?>
                          <?php foreach($dt_pakaian as $dtp) : ?>
                           <tr>
                             <td class="text-center">
                               <?= $no++; ?>
                             </td>
                             <td>
                               <?= $dtp["jenis_pakaian"]; ?>
                             </td>
                             <td class="text-center">
                               <?= $dtp["jumlah"]; ?>
                             </td>
                           </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
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
  
</body>
</html>