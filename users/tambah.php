<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminOnly();

// tambah data
if(isset($_POST["tambah"])){
  $id_user = uniqid();
  $nama_user = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_user"]));
  $username = strtolower(mysqli_real_escape_string($conDB, htmlspecialchars($_POST["username"])));
  $password = mysqli_real_escape_string($conDB, password_hash($_POST["password"], PASSWORD_DEFAULT));
  $id_outlet = $_POST["id_outlet"];
  $level = $_POST["level"];
  
  // upload gambar
  $file = $_FILES["foto_profile"];
  
  $namaGambar = $file["name"];
  $ukuran = $file["size"];
  $error = $file["error"];
  $tmpName = $file["tmp_name"];

  // cek gambar ada atau tdk
  if($error == 0){
      // cek ekstensi gambar
      $ekstensiValid = ["jpg", "jpeg", "png"];
      $ekstensiGambar = strtolower(end(explode(".", $namaGambar)));
      if(!in_array($ekstensiGambar, $ekstensiValid)){
        setBasicFlasher("warning", "Ekstensi file tidak sesuai!!");
        return redirect("/users/tambah.php");
      }
      
      // cek ukuran gambar
      if($ukuran > 2000000){
        setBasicFlasher("warning", "ukuran file terlalu besar");
        return redirect("/users/tambah.php");
      }
      
      // genereate nama file gambarnya
      $namaGambar = uniqid() . ".$ekstensiGambar";
      
      // upload gambarnya
      if(move_uploaded_file($tmpName, "../asset/img/" . $namaGambar)){
        $foto_profile = $namaGambar;
        $uploadFoto = true;
      }else{
        $uploadFoto = false;
      }
  }else{
    $foto_profile =  "profile.jpg";
    $uploadFoto = true;
  }
  
  if($uploadFoto){
    if($id_outlet == ""){
      $id_outlet = 'NULL';
    }
    $data_insert = [
      $id_user,
      $nama_user,
      $username,
      $password,
      $id_outlet,
      $foto_profile,
      $level
    ];
   // var_dump($data_insert); die;
    if(insertData("tb_user", $data_insert) > 0){
      setBasicFlasher("success", "Berhasil Insert Data!!");
      return redirect("/users/index.php");
    }else{
      setBasicFlasher("danger", "GAGAL Insert Data!!");
    }
  }else{
    setBasicFlasher("danger", "GAGAL Insert Data!!");
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
  <title>USER | TAMBAH DATA</title>
  
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
              <div class="breadcrumb-item"><a href="#">User</a></div>
              <div class="breadcrumb-item">Tambah Data</div>
            </div>
          </div>
         
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Tambah Data</h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/users/index.php" class="btn btn-icon btn-warning icon-left float-right btnTambah"><i class="fas fa-long-arrow-alt-left mr-1"></i>Kembali</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showBasicFlasher(); ?>
                  <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama User
                          </label>
                          
                          <input type="text" name="nama_user" class="form-control" required placeholder="Masukan Nama User">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Outlet
                          </label>
                          <select class="form-control selectpicker" name="id_outlet"  data-live-search="true">
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
                            Username
                          </label>
                          <input type="text" name="username" class="form-control" required placeholder="Masukan Username">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Level User
                          </label>
                          <select class="form-control" name="level" required >
                            <option value="kasir">
                              Kasir
                            </option>
                            <option value="admin">
                              Admin
                            </option>
                            <option value="owner">
                              Owner
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Password
                          </label>
                          <input type="text" name="password" class="form-control" required placeholder="Masukkan Password">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-0">
                          <label>
                            Foto Profil
                          </label>
                          <input type="file" class="form-control" id="foto_profile" name="foto_profile" onchange="preview()">
                          <small class="form-text text-muted">
                            Kosongkan jika tidak ingin menggunakan foto
                          </small>
                        </div>
                        <div class="form-group">
                          <img src="<?= base_url(); ?>/asset/img/profile.jpg" alt="foto user" class="img-thumbnail" id="img_preview">
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
  <script src="<?= base_url(); ?>/asset/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/img_preview.js"></script>
  
</body>
</html>