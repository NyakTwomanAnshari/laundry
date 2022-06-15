<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminKasir();

// get id pelanggan
$edit_id_pelanggan = $_GET["id"];
if(!isset($edit_id_pelanggan) || $edit_id_pelanggan == ""){
  redirect("/pelanggan/index.php");
  die;
}

// get data pelanggan
$dt = getOneData("tb_pelanggan", "id_pelanggan", $edit_id_pelanggan);

// cek data pelanggan
if($dt == null || $dt == false){
  redirect("/pelanggan/index.php");
  die;
} 

// tambah data 
if(isset($_POST["ubah"])){
  $id_pelanggan = $_POST["id_pelanggan"];
  $nama_pelanggan = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_pelanggan"]));
  $alamat = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["alamat"]));
  $telp = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["telp"]));
  $id_outlet = $_POST["id_outlet"];
  $jenis_langganan = $_POST["jenis_langganan"];
  // cek no telp
  if(!is_numeric($telp)){
    setBasicFlasher("danger", "<b>Mohon isi No Telp dengan Benar!!</b>");
    redirect("/pelanggan/edit.php?id=" . $edit_id_pelanggan);
    return false;
  }
  $data_update = [
    "nama_pelanggan" => $nama_pelanggan,
    "alamat" => $alamat,
    "telp" => $telp, 
    "id_outlet" => $id_outlet,
    "jenis_langganan" => $jenis_langganan
  ];
  if(editData("tb_pelanggan", "id_pelanggan", $id_pelanggan, $data_update) >= 0){
    setBasicFlasher("success", "<b> Data BERHASIL Diubah!! </b>");
    return redirect("/pelanggan/index.php");
  }else{
    setBasicFlasher("danger", "<b> Data GAGAL Diubah!! </b>");
  }
  
}

// get data Outlet
$outlet = getAllData("tb_outlet");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PELANGGAN | EDIT DATA</title>
  
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
                          <input type="text" class="form-control" name="id_pelanggan" required value="<?= $dt['id_pelanggan']; ?>" readonly>
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
                             <option value="<?= $o['id_outlet']; ?>"
                             <?= ($o['id_outlet'] == $dt['id_outlet']) ? 'selected' : ''; ?>
                             >
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
                          <input type="text" class="form-control" name="nama_pelanggan" required placeholder="Masukkan Nama Pelanggan" value="<?= $dt['nama_pelanggan']; ?>">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Jenis Langganan
                          </label>
                          <select name="jenis_langganan" required class="form-control">
                            <option value="reguler"
                            <?= ($dt["jenis_langganan"] == "reguler") ? 'selected' : ''; ?>
                            >
                              Reguler
                            </option>
                            <option value="member"
                            <?= ($dt["jenis_langganan"] == "member") ? 'selected' : ''; ?>
                            >
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
                          <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control" placeholder="Masukkan Alamat Pelanggan" required><?= $dt["alamat"]; ?></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                          <label>
                            No Telp
                          </label>
                          <input type="text" class="form-control" name="telp" required placeholder="0845xxx" value="<?= $dt['telp']; ?>">
                        </div>
                        <button type="submit" name="ubah" class="btn btn-info btn-icon icon-left float-right w-100 mt-0">
                          <i class="fas fa-save mr-1"></i>Simpan Perubahan
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