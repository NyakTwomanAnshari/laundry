<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminKasir();

// tambah data 
if(isset($_POST["tambah"])){
  // ambil semua variabel
  $id_transaksi = $_POST["id_transaksi"];
  $id_user = getDataUser("id_user");
  $id_outlet = $_POST["id_outlet"];
  $id_pelanggan = $_POST["id_pelanggan"];
  $id_paket = $_POST["id_paket"];
  $tgl_masuk = $_POST["tgl_masuk"];
  $tgl_selesai = $_POST["tgl_selesai"];
  $berat = $_POST["berat"];
  $total_bayar;
  $status_bayar = $_POST["status_bayar"];
  $status_transaksi = "proses";
  $jenis_pakaian = $_POST["jenis_pakaian"];
  $jumlah = $_POST["jumlah"];
  $harga_paket = getOneData("tb_paket", "id_paket", $id_paket)["harga"];
  $index = 0;
  $total_bayar = $harga_paket * $berat;
  
  // cek tabel pakaian
  if(empty($jumlah) || empty($jenis_pakaian)){
    setBasicFlasher("warning", "tabel pakaian harus diisi setidaknya satu data!!");
    return redirect("/transaksi/index.php");
  }
  
  // cek apakah langsung lunas atau blm
  $tgl_bayar = ($status_bayar == "lunas") ? date("Y-m-d") : 'NULL';
  
  // insert data
  $data_insert = [$id_transaksi, $id_user, $id_outlet, $id_pelanggan, $id_paket, $tgl_masuk, $tgl_selesai, $tgl_bayar, $berat, $total_bayar, $status_bayar, $status_transaksi];
  if(insertData("tb_transaksi", $data_insert) > 0){
    foreach($jenis_pakaian as $j){
      $jmlh = $jumlah[$index];
      insertData("tb_pakaian", ['NULL', $id_transaksi, $j, $jmlh]);
      $index++;
    }
    $nm_plg = getOneData("tb_pelanggan", "id_pelanggan", $id_pelanggan)["nama_pelanggan"];
    $_SESSION["transaksi"] = [
      "id_transaksi" => $id_transaksi,
      "nama_pelanggan" => $nm_plg,
      "total_biaya" => $total_bayar,
      "tgl_selesai" => $tgl_selesai
    ];
    return redirect("/transaksi/index.php");
  }else{
    setBasicFlasher("danger", "Data gagal Ditambahkan");
    return redirect("/transaksi/index.php");
  }
  
}
// create id transaksi
$value_id_transaksi = "TRX" . date("YmdHis");

// get Data pelanggan
//$data_pelanggan = getAllData("tb_pelanggan");
$idOutletUser = getDataUser("id_outlet");
$sql = "SELECT * FROM tb_pelanggan WHERE id_outlet = '$idOutletUser' ";
$data_pelanggan = query($sql);

// nama outlet 
$namaOutlet = getOneData("tb_outlet", "id_outlet", $idOutletUser);
//var_dump($namaOutlet); die;
$sql_paket = "SELECT * FROM tb_paket WHERE id_outlet = '$idOutletUser' ";
$dataPaket = query($sql_paket);
//var_dump($dataPaket); die;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>TRANSAKSI | CREATE</title>
  
  <!-- basic css -->
  <?php include("../template/header.php"); ?>
  
  <!-- css tambahan -->
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/css/bootstrap-select.min.css">
  
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
            <h1>Tambah Transaksi</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Transaksi</a></div>
              <div class="breadcrumb-item">Tambah Transaksi</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card card-info">
                <div class="card-header">
                  <h4>
                    Tambah Transaksi
                  </h4>
                </div>
                
                <div class="card-body pt-0">
                  <?php if(isset($_SESSION["transaksi"])) : ?>
                  <?php $dtrx = $_SESSION["transaksi"]; ?>
                  <?php unset($_SESSION["transaksi"]); ?>
                    <div class="row mt-0">
                      <div class="col-12">
                        <div class="alert alert-success" role="alert" id="alert_pelanggan">
                          <h4 class="alert-heading">
                            Transaksi Berhasil
                          </h4>
                          <div>
                            Id Transaksi : <b><?= $dtrx["id_transaksi"]; ?></b>
                          </div>
                          <div>
                            Nama Pelangan : <b><?= $dtrx["nama_pelanggan"]; ?></b>
                          </div>
                          <div>
                            Total Harga : <b>Rp. <?= number_format($dtrx["total_biaya"], 0, '.', '.'); ?></b>
                          </div>
                          <div>
                            Tgl Selesai : <b><?= date("d M Y", strtotime($dtrx["tgl_selesai"])); ?></b>
                          </div>
                          
                          <hr>
                          <div class="d-flex flex-row-reverse">
                            <a href="<?= base_url(); ?>/cetak/nota.php?id=<?= $dtrx['id_transaksi']; ?>" class="btn btn-danger btn-icon icon-left" target="_blank">
                              <i class="fas fa-file"></i> Cetak Nota Pembayaran
                            </a>
                            <a href="<?= base_url(); ?>/pesanan/index.php" class="btn btn-primary btn-icon mr-2 icon-left">
                              <i class="fas fa-tshirt"></i> Lihat Pesanan
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  
                  <?= showBasicFlasher(); ?>
                  
                  <form action="" method="post">
                    <input type="hidden" value="<?= $namaOutlet['id_outlet']; ?>" name="id_outlet">
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            ID Transaksi
                          </label>
                          <input type="text" class="form-control" name="id_transaksi" required readonly value="<?= $value_id_transaksi; ?>">
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Tgl Masuk
                          </label>
                          <input type="date" class="form-control" name="tgl_masuk" required value="<?= date('Y-m-d'); ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Pelanggan
                          </label>
                          <select name="id_pelanggan" data-live-search="true" id="" class="form-control selectpicker" required  data-href="<?= base_url(); ?>/transaksi/source.php?id_pelanggan=">
                            <option value="">
                              -- Pilih Pelanggan --
                            </option>
                            <?php foreach($data_pelanggan as $plg ) : ?>
                              <option value="<?= $plg['id_pelanggan']; ?>">
                                <?= $plg['id_pelanggan']; ?> | <?= $plg['nama_pelanggan']; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Tgl Selesai
                          </label>
                          <input type="date" class="form-control" name="tgl_selesai" required>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Outlet
                          </label>

                          <input type="text" class="form-control" readonly value="<?= $namaOutlet['nama_outlet']; ?>" >
                      </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Berat Pakaian (dlm kg)
                          </label>
                          <input type="text" class="form-control" name="berat" required onkeyup="setTotalHarga()" id="berat">
                          <small class="text-muted form-text">
                            Gunakan titk(.) untuk membuat koma
                          </small>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Paket
                          </label>
                          <select name="id_paket" id="select_paket" class="form-control" required onchange="setTotalHarga()">
                            <option value="" data-harga="0">
                              -- Pilih Paket --
                            </option>
                            <?php foreach($dataPaket as $dtp) : ?>
                              <option value="<?= $dtp['id_paket']; ?>" data-harga="<?= $dtp['harga']; ?>">
                                <?= $dtp['jenis']; ?> | <?= $dtp['harga']; ?>/kg
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <?php if(empty($dataPaket)) : ?>
                          <small class="text-muted form-text text-danger" id="InfoKetersediaanPaket">
                             <b class='text-danger'>Tidak Ada PAKET untuk Outlet ini. Mohon untuk menambah data PAKET terlebih dahulu!!</b>
                          </small>
                          <?php endif; ?>
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Total Harga
                          </label>
                          <input type="text" class="form-control" name="total_bayar" required readonly="on" id="total_bayar">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Status Bayar
                          </label>
                          <select name="status_bayar" id="" class="form-control">
                            <option value="belum">
                              Belum Bayar
                            </option>
                            <option value="lunas">
                              Lunas
                            </option>
                          </select>
                        </div>
                      </div>
       
                      <div class="col-md-12 col-sm-12">
                        <hr class="w-100">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th class="text-center">
                                  Jenis Pakaian
                                </th>
                                <th class="text-center">
                                  Jumlah
                                </th>
                                <th class="text-center">
                                  <button type="button" class="btn btn icon btn-info" id="btnTambah">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </th>
                              </tr>
                            </thead>
                            
                            <tbody id="bodyTable">
                              <tr>
                                <td>
                                  <input type="text" class="form-control" name="jenis_pakaian[]" required placeholder="Masukan Jenis Pakaian">
                                </td>
                                <td>
                                  <input type="number" class="form-control" name="jumlah[]" required placeholder="Masukan Jumlah Pakaian">
                                </td>
                                <td class="text-center">
                                  <button class="btn-danger btn btn-hapus-list" type="button">
                                    Hapus
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>

                      </div>
                    </div>
                    
                    <div class="row mt-3">
                      <div class="col-12">
                        <button type="submit" class="btn btn-icon btn-primary icon-left float-right" name="tambah">
                          <i class="fas fa-plus"></i>
                          Tambah Transaksi
                        </button>
                      </div>
                    </div>
                    
                  </form>
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
  <script src="<?= base_url() ?>/asset/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url() ?>/asset/js/tambah_pakaian.js"></script>
  <script src="<?= base_url() ?>/asset/js/script_transaksi.js"></script>
  
  
</body>
</html>