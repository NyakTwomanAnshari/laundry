<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminKasir();

$get_id_transaksi = $_GET["id"];
$dt_trx = getOneData("tb_transaksi", "id_transaksi", $get_id_transaksi);
if(!isset($get_id_transaksi) || $get_id_transaksi == "" || $dt_trx == null ){
  return redirect("/pesanan/index.php");
}

// ubah data 
if(isset($_POST["ubah"])){
  // ambil semua variabel
  $id_transaksi = $_POST["id_transaksi"];
  $id_user = $_POST["id_user"];
  $id_outlet = $_POST["id_outlet"];
  $id_pelanggan = $_POST["id_pelanggan"];
  $id_paket = $_POST["id_paket"];
  $tgl_masuk = $_POST["tgl_masuk"];
  $tgl_selesai = $_POST["tgl_selesai"];
  $berat = $_POST["berat"];
  $total_bayar;
  $status_bayar = $_POST["status_bayar"];
  $status_transaksi = $_POST["status_transaksi"];
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
  
  // update data
  $data_update = [
    "id_user" => $id_user,
    "id_outlet" => $id_outlet,
    "id_pelanggan" => $id_pelanggan,
    "id_paket" => $id_paket,
    "tgl_masuk" => $tgl_masuk,
    "tgl_selesai" => $tgl_selesai,
    "berat" => $berat,
    "total_bayar" => $total_bayar,
    "status_bayar" => $status_bayar,
    "status_transaksi" => $status_transaksi
  ];
  
  // cek jika status bayar di ubah jadi blm lunas
  if($status_bayar == "belum"){
    $data_update["tgl_bayar"] = "NULL";
  }
  
  if(editData("tb_transaksi", "id_transaksi", $id_transaksi, $data_update) >= 0){
    // hapus semua data pakaian 
    mysqli_query($conDB, "DELETE FROM tb_pakaian WHERE id_transaksi = '" . $id_transaksi . "'");
    // tambah data pakaian baru
    foreach($jenis_pakaian as $j){
      $jmlh = $jumlah[$index];
      insertData("tb_pakaian", ['', $id_transaksi, $j, $jmlh]);
      $index++;
    }
    setFlasher("Selamat", "success", "Data-berhasil-diubah");
    return redirect("/pesanan/index.php");
  }else{
     setBasicFlasher("danger", "Data gagal Ditambahkan");
    return redirect("/pesanan/edit.php?id=" . $id_transaksi);
  }
  
}


// get Data pelanggan
$data_pelanggan = getAllData("tb_pelanggan");
// get data outlet
$data_outlet = getAllData("tb_outlet");

// get data User
$data_user = getAllData("tb_user");
// get data paket
$rstl = mysqli_query($conDB, "SELECT * FROM tb_paket WHERE id_outlet = '" . $dt_trx["id_outlet"] . "'");
$dt_pkt = [];
while($r = mysqli_fetch_assoc($rstl)){
  $dt_pkt[] = $r;
}

// get data pakaian
$result2 = mysqli_query($conDB, "SELECT jenis_pakaian, jumlah FROM tb_pakaian WHERE id_transaksi = '" . $dt_trx["id_transaksi"] . "'");
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
  <title>TRANSAKSI | EDIT</title>
  
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
            <h1>Edit Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Transaksi</a></div>
              <div class="breadcrumb-item">Edit Transaksi</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card card-info">
                <div class="card-header">
                  <h4>
                    Edit Data
                  </h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/pesanan/index.php" class="btn btn-icon btn-warning icon-left float-right btnTambah"><i class="fas fa-long-arrow-alt-left mr-1"></i>Kembali</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  
                  <?= showBasicFlasher(); ?>
                  
                  <form action="" method="post">
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            ID Transaksi
                          </label>
                          <input type="text" class="form-control" name="id_transaksi" required readonly value="<?= $dt_trx['id_transaksi']; ?>">
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Tgl Masuk
                          </label>
                          <input type="date" class="form-control" name="tgl_masuk" required value="<?= $dt_trx['tgl_masuk']; ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Pelanggan
                          </label>
                          <select name="id_pelanggan" data-live-search="true" id="" class="form-control selectpicker" required onchange="getDataOutlet(this)" data-href="<?= base_url(); ?>/transaksi/source.php?id_pelanggan=">
                            <option value="">
                              -- Pilih Pelanggan --
                            </option>
                            <?php foreach($data_pelanggan as $plg ) : ?>
                              <option value="<?= $plg['id_pelanggan']; ?>" <?= ($dt_trx['id_pelanggan'] == $plg['id_pelanggan']) ? 'selected' : ''; ?>>
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
                          <input type="date" class="form-control" name="tgl_selesai" required value="<?= $dt_trx['tgl_selesai']; ?>">
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Outlet
                          </label>
                          <select name="id_outlet" id="select_outlet" class="form-control" required onchange="getDataPaket(this)" data-href="<?= base_url(); ?>/transaksi/source.php?id_outlet=">
                            <option value="">
                              -- Pilih Outlet --
                            </option>
                            <?php foreach($data_outlet as $otl ) : ?>
                              <option value="<?= $otl['id_outlet']; ?>" class="opt-outlet" <?= ($dt_trx['id_outlet'] == $otl['id_outlet']) ? 'selected' : ''; ?>>
                                <?= $otl['id_outlet']; ?> | <?= $otl['nama_outlet']; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                      </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Berat Pakaian (dlm kg)
                          </label>
                          <input type="text" class="form-control" name="berat" required onkeyup="setTotalHarga()" id="berat" value="<?= $dt_trx['berat']; ?>">
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
                            <?php foreach($dt_pkt as $dpkt) : ?>
                              <option value="<?= $dpkt['id_paket']; ?>" data-harga="<?= $dpkt['harga']; ?>" <?= ($dt_trx["id_paket"] == $dpkt["id_paket"]) ? "selected" : ""; ?>>
                                <?= $dpkt["jenis"]; ?> | <?= $dpkt["harga"]; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <small class="text-muted form-text text-danger" id="InfoKetersediaanPaket">
                            
                          </small>
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Total Harga
                          </label>
                          <input type="text" class="form-control" name="total_bayar" required readonly="on" id="total_bayar" value="Rp. <?= $dt_trx['total_bayar']; ?>">
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
                            <option value="belum" <?= ($dt_trx['status_bayar'] == "belum") ? "selected" : ""; ?>>
                              Belum Bayar
                            </option>
                            <option value="lunas"  <?= ($dt_trx['status_bayar'] == "lunas") ? "selected" : ""; ?>>
                              Lunas
                            </option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            User
                          </label>
                          <select name="id_user" id="" class="form-control selectpicker" data-live-search="true" required name="id_user">
                            <option value="">
                              -- Pilih User --
                            </option>
                              <?php foreach($data_user as $dtusr ) : ?>
                               <option value="<?= $dtusr['id_user']; ?>" <?= ($dt_trx['id_user'] == $dtusr['id_user']) ? 'selected' : ''; ?>>
                                 <?= $dtusr["nama_user"]; ?>
                               </option>
                              <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Status
                          </label>
                          <select name="status_transaksi" id="" class="form-control" required>
                            <option value="">
                              -- Pilih Status --
                            </option>
                            <option value="proses" <?= ($dt_trx["status_transaksi"] == "proses") ? "selected" : ""; ?>>
                              Proses
                            </option>
                            <option value="selesai" <?= ($dt_trx["status_transaksi"] == "selesai") ? "selected" : ""; ?>>
                              Selesai
                            </option>
                            <option value="diambil" <?= ($dt_trx["status_transaksi"] == "diambil") ? "selected" : ""; ?>>
                              Sudah Diambil
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
                              <?php foreach($dt_pakaian as $dtpkn) : ?>
                              <tr>
                                <td>
                                  <input type="text" class="form-control" name="jenis_pakaian[]" required placeholder="Masukan Jenis Pakaian" value="<?= $dtpkn['jenis_pakaian']; ?>">
                                </td>
                                <td>
                                  <input type="number" class="form-control" name="jumlah[]" required placeholder="Masukan Jumlah Pakaian" value="<?= $dtpkn['jumlah']; ?>">
                                </td>
                                <td class="text-center">
                                  <button class="btn-danger btn btn-hapus-list" type="button">
                                    Hapus
                                  </button>
                                </td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>

                      </div>
                    </div>
                    
                    <div class="row mt-3">
                      <div class="col-12">
                        <button type="submit" class="btn btn-icon btn-primary icon-left float-right" name="ubah">
                          <i class="fas fa-edit"></i>
                          Simpan Perubahan
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