<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth", "cucian"]);
adminKasir();


// ubah sratus transaksi
//$ubah_sts_trx = $_GET["status_transaksi"];
if(isset($_GET["status_transaksi"])){
  $ubah_sts_id_trx = $_GET["id"];
  $ubah_sts_trx = $_GET["status_transaksi"];
  if(editData("tb_transaksi", "id_transaksi", $ubah_sts_id_trx, ["status_transaksi" => $ubah_sts_trx]) > 0){
    if($ubah_sts_trx == "diambil"){
      $get_dt_trx_ubh = getOneData("tb_transaksi", "id_transaksi", $ubah_sts_id_trx);
      // update status bayar dan tgl bayar
      $dt_updt = [
        "status_bayar" => "lunas",
        "tgl_bayar" => date("Y-m-d")
      ];
      if($get_dt_trx_ubh["tgl_bayar"] != null){
        $dt_updt["tgl_bayar"] = $get_dt_trx_ubh["tgl_bayar"];
      }
      editData("tb_transaksi", "id_transaksi", $ubah_sts_id_trx, $dt_updt);
      
    }
    setFlasher("Selamat", "success", "Data-berhasil-diubah");
  }else{
    setFlasher("Oupss", "error", "Data-gagal-diubah");
  }
  return redirect("/pesanan/index.php");
}

// hapus data
//$hps = $_GET["hapus"];
if(isset($_GET["hapus"])){
  $hps_id_trx = $_GET["id"];
  if(deleteData("tb_transaksi", "id_transaksi", $hps_id_trx) > 0){
    setFlasher("Selamat", "success", "Data-berhasil-dihapus");
  }else{
    setFlasher("Oupss", "error", "Data-gagal-dihapus");
  }
  return redirect("/pesanan/index.php");
}

// ubah sratus bayar
//$ubah_byr_trx = $_GET["status_bayar"];
if(isset($_GET["status_bayar"])){
  $ubah_byr_id_trx = $_GET["id"];
  $ubah_byr_trx = $_GET["status_bayar"];
  if(editData("tb_transaksi", "id_transaksi", $ubah_byr_id_trx, ["status_bayar" => $ubah_byr_trx, "tgl_bayar" => date("Y-m-d")]) > 0){
    setFlasher("Selamat", "success", "Pembayaran-berhasil");
  }else{
    setFlasher("Oupss", "error", "Pembayaran-gagal");
  }
  return redirect("/pesanan/index.php");
}


// get all pesanan
$result = mysqli_query($conDB, "SELECT tb_transaksi.*, tb_outlet.nama_outlet, tb_pelanggan.nama_pelanggan, tb_paket.jenis FROM tb_transaksi JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id_outlet JOIN tb_pelanggan ON tb_transaksi.id_pelanggan = tb_pelanggan.id_pelanggan JOIN tb_paket ON tb_transaksi.id_paket = tb_paket.id_paket ORDER BY id_transaksi DESC");
$dt = [];
while($r = mysqli_fetch_assoc($result)){
  $dt[] = $r;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cucian</title>
  
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
            <h1>Cucian</h1>
            <?= showFlasher(); ?>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Cucian</a></div>
              <div class="breadcrumb-item">Data Cucian</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Data Cucian
                  </h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/transaksi/index.php" class="btn btn-icon btn-primary icon-left float-right"><i class="fas fa-plus mr-1"></i>Tambah Transaksi</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showFlasher(); ?>
                  <div class="table-responsive">
                    <table id="table" class="table table-striped">
                      <thead>
                        <tr>
                          <th>
                            #
                          </th>
                          <th>
                            ID Transaksi
                          </th>
                          <th>
                            Nama Pelanggan
                          </th>
                          <th>
                            Outlet
                          </th>
                          <th>
                            Total Harga
                          </th>
                          <th>
                            Status Bayar
                          </th>
                          <th>
                            Status Pesanan
                          </th>
                          <th>
                            Tanggal Selesai
                          </th>
                          <th>
                            Keterangan
                          </th>
                          <th>
                            Aksi
                          </th>
                        </tr>
                      </thead>
                      
                      <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($dt as $d) : ?>
                         <tr>
                           <td class="align-middle">
                             <?= $no++; ?>
                           </td>
                           <td class="align-middle">
                             <?= $d["id_transaksi"]; ?>
                           </td>
                           <td class="align-middle">
                             <?= $d["nama_pelanggan"]; ?>
                           </td>
                           <td class="align-middle">
                             <?= $d["nama_outlet"]; ?>
                           </td>
                           <td class="align-middle">
                             Rp. <?= number_format($d["total_bayar"], 0, '.', '.'); ?>
                           </td>
                           <td class="align-middle text-center text-uppercase">
                             <?php if($d["status_bayar"] == "belum") : ?>
                               <b class="text-danger">
                                 <?= $d["status_bayar"]; ?>
                               </b>
                             <?php else : ?>
                               <b class="text-success">
                                 <?= $d["status_bayar"]; ?>
                               </b>
                             <?php endif; ?>
                           </td>
                           <td class="align-middle text-center">
                             <?php if($d["status_transaksi"] == "proses") : ?>
                                <span data-href="<?= base_url(); ?>/pesanan/index.php?status_transaksi=selesai&id=<?= $d['id_transaksi']; ?>" class="badge badge-info spanBadge" data-text="yakin ingin mengubah status pesanan jadi selesai??" onclick="confirmHapus(this)">
                                  <?= $d["status_transaksi"]; ?>
                                </span>
                             <?php elseif($d["status_transaksi"] == "selesai") : ?>
                                <span data-href="<?= base_url(); ?>/pesanan/index.php?status_transaksi=diambil&id=<?= $d['id_transaksi']; ?>" class="badge badge-success spanBadge" data-text="yakin ingin mengubah status pesanan jadi telah diambil & telah melakukan pembayaran??;" onclick="confirmHapus(this)">
                                  <?= $d["status_transaksi"]; ?>
                                </span>
                             <?php else : ?>
                                <span class="badge badge-danger">
                                  <?= $d["status_transaksi"]; ?>
                                </span>
                             <?php endif; ?>
                           </td>
                           
                           <td>
                             <?= date("d-M-Y", strtotime($d["tgl_selesai"])); ?>
                           </td>
                           
                           <td>
                             <?= cetakKeterangan($d["tgl_selesai"], $d["status_transaksi"]); ?>
                           </td>
                           
                           <td class="d-flex flex-column justify-content-center align-items-center">
                             <div class="d-flex mb-1">
                               <a href="<?= base_url(); ?>/pesanan/detail.php?id=<?= $d['id_transaksi']; ?>" class="btn btn-icon btn-info mr-2">
                                 <i class="fas fa-eye"></i>
                               </a>
                               <button type="button" data-href="<?= base_url(); ?>/pesanan/index.php?hapus=true&id=<?= $d['id_transaksi']; ?>" class="btn btn-icon btn-danger" data-text="yakin ingin  menghapus data??" onclick="confirmHapus(this)">
                                 <i class="fas fa-trash"></i>
                               </button>
                             </div>
                             <div class="d-flex">
                               <a href="<?= base_url(); ?>/pesanan/edit.php?id=<?= $d['id_transaksi']; ?>" class="btn btn-icon btn-primary mr-2">
                                 <i class="fas fa-edit"></i>
                               </a>
                               <a href="<?= base_url(); ?>/cetak/nota.php?id=<?= $d['id_transaksi']; ?>" target="_blank" class="btn btn-icon btn-warning">
                                 <i class="fas fa-print"></i>
                               </a>
                             </div>
                             <?php if($d["status_bayar"] == "belum") : ?>
                               <button type="button" data-href="<?= base_url(); ?>/pesanan/index.php?status_bayar=lunas&id=<?= $d['id_transaksi']; ?>" class="btn btn-outline-success w-100 mt-2" data-text="yakin ingin melakukan pembayaran??" onclick="confirmHapus(this)">
                                 Bayar
                               </button>
                             <?php endif; ?>
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
        </section>
      </div>
      
      <!-- copyright -->
      <?php include("../template/copyright.php"); ?>
    </div>
  </div>
  
  
  
  
  
  
  <!-- basic javascript -->
  <?php include("../template/footer.php"); ?>
  
  <!-- javascript tambahan -->
  <script src="<?= base_url(); ?>/asset/js/flasher.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>