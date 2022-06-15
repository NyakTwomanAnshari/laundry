<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminKasir();

// tambah data 
if(isset($_POST["tambah"])){
  $id_pelanggan = $_POST["id_pelanggan"];
  $nama_pelanggan = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_pelanggan"]));
  $alamat = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["alamat"]));
  $telp = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["telp"]));
  $id_outlet = $_POST["id_outlet"];
  $jenis_langganan = $_POST["jenis_langganan"];
  // cek no telp
  if(!is_numeric($telp)){
    setBasicFlasher("danger", "Mohon isi No Telp dengan Benar!!");
    redirect("/pelanggan/tambah.php");
    return false;
  }
  $data_insert = [
    "id_pelanggan" => $id_pelanggan,
    "nama_pelanggan" => $nama_pelanggan,
    "alamat" => $alamat,
    "telp" => $telp, 
    "id_outlet" => $id_outlet,
    "jenis_langganan" => $jenis_langganan
  ];
  if(insertData("tb_pelanggan", $data_insert) > 0){
    setBasicFlasher("success", "Data BERHASIL Ditambahkan!!");
  }else{
    setBasicFlasher("danger", "Data GAGAL Ditambahkan!!");
  }
  
}

// get next id
$result = mysqli_query($conDB, "SELECT * FROM tb_pelanggan ORDER BY id_pelanggan DESC");
$id_terakhir = mysqli_fetch_assoc($result);
if($id_terakhir == null){
  $id_terakhir = 0;
}else{
  $id_terakhir = $id_terakhir["id_pelanggan"];
  $id_terakhir = (int) substr($id_terakhir, 3);
}
$id_terakhir = $id_terakhir + 1;
$itr = "CST000";
if($id_terakhir >= 1000){
  $itr = "CST";
}elseif ($id_terakhir >= 100) {
  $itr = "CST0";
}elseif($id_terakhir >= 10){
  $itr = "CST00";
} 
$next_id = $itr . $id_terakhir;

// get data Outlet
$outlet = getAllData("tb_outlet");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PELANGGAN | TAMBAH DATA</title>
  
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
            <h1>Tambah Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Pelanggan</a></div>
              <div class="breadcrumb-item">Tambah Data</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Tambah Data
                  </h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/pelanggan/index.php" class="btn btn-icon btn-warning icon-left float-right btnTambah"><i class="fas fa-long-arrow-alt-left mr-1"></i>Kembali</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showBasicFlasher(); ?>
                  <form action="" method="post">
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            ID Pelanggan
                          </label>
                          <input type="text" class="form-control" name="id_pelanggan" required value="<?= $next_id; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Outlet
                          </label>
                          <select name="id_outlet"  class="form-control selectpicker" data-live-search="true" required>
                            <option value="">
                              -- Pilih Outlet --
                            </option>
                            <?php foreach($outlet as $o) : ?>
                             <option value="<?= $o['id_outlet']; ?>" >
                               <?= $o["id_outlet"]; ?> | <?= $o["nama_outlet"]; ?>
                             </option>
                            <?php endforeach; ?>
                            
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Pelanggan
                          </label>
                          <input type="text" class="form-control" name="nama_pelanggan" required placeholder="Masukkan Nama Pelanggan">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Jenis Langganan
                          </label>
                          <select name="jenis_langganan" required class="form-control">
                            <option value="reguler">
                              Reguler
                            </option>
                            <option value="member">
                              Member
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Alamat
                          </label>
                          <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control" placeholder="Masukkan Alamat Pelanggan" required></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                          <label>
                            No Telp
                          </label>
                          <input type="text" class="form-control" name="telp" required placeholder="0845xxx">
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary btn-icon icon-left float-right w-100 mt-0">
                          <i class="fas fa-plus mr-1"></i>Tambah Data
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
  
</body>
</html>