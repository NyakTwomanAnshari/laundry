<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminOnly();

// get data user 
$getIdUser = $_GET["id"];
$dt = getOneData("tb_user", "id_user", $getIdUser);
if(!isset($getIdUser) || $getIdUser == "" || $dt == NULL){
  return redirect("/users/index.php");
}

// ubah data
if(isset($_POST["ubah"])){
  $id_user = $getIdUser;
  $nama_user = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_user"]));
  $username = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["username"]));
  $password = $_POST["password"];
  $id_outlet = $_POST["id_outlet"];
  $level = $_POST["level"];
  $foto_lama = $_POST["foto_lama"];
  
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
      //$ekstensiGambar = strtolower(end(explode(".", $namaGambar)));
      $ekstensiGambar = explode(".", $namaGambar);
      $ekstensiGambar = end($ekstensiGambar);
      $ekstensiGambar = strtolower($ekstensiGambar);
      if(!in_array($ekstensiGambar, $ekstensiValid)){
        setBasicFlasher("warning", "Ekstensi file tidak sesuai!!");
        return redirect("/users/edit.php?id=" . $id_user);
      }
      
      // cek ukuran gambar
      if($ukuran > 2000000){
        setBasicFlasher("warning", "ukuran file terlalu besar");
        return redirect("/users/edit.php?id=" . $id_user);
      }
      
      // genereate nama file gambarnya
      $namaGambar = uniqid() . ".$ekstensiGambar";
      
      // upload gambarnya
      if(move_uploaded_file($tmpName, "../asset/img/" . $namaGambar)){
        $foto_profile = $namaGambar;
        if($foto_lama != "profile.jpg"){
          if(file_exists("../asset/img/" . $foto_lama)){
            unlink("../asset/img/" . $foto_lama);
          }
        }
        $uploadFoto = true;
      }else{
        $uploadFoto = false;
      }
  }else{
    $foto_profile =  $foto_lama;
    $uploadFoto = true;
  }
  
  if($uploadFoto){
    if($id_outlet == ""){
      $id_outlet = 'NULL';
    }
    $data_update = [
      "nama_user" => $nama_user,
      "username" => $username,
      "id_outlet" => $id_outlet,
      "foto_profile" => $foto_profile,
      "level" => $level
    ];
    if($password != "" || $password != null){
      $password = password_hash($password, PASSWORD_DEFAULT);
      $data_update["password"] = $password;
    }
    if(editData("tb_user", "id_user", $id_user, $data_update) >= 0){
      setBasicFlasher("success", "Berhasil UPDATE Data!!");
      return redirect("/users/index.php");
    }else{
      setBasicFlasher("danger", "GAGAL UPDATE Data!!");
    }
  }else{
    setBasicFlasher("danger", "GAGAL UPDATE Data!!");
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
  <title>USER | UBAH DATA</title>
  
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
            <h1>EDIT Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">User</a></div>
              <div class="breadcrumb-item">Edit Data</div>
            </div>
          </div>
         
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Edit Data</h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/users/index.php" class="btn btn-icon btn-warning icon-left float-right btnTambah"><i class="fas fa-long-arrow-alt-left mr-1"></i>Kembali</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showBasicFlasher(); ?>
                  <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="foto_lama" value="<?= $dt['foto_profile']; ?>" required>
                    <div class="row">
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama User
                          </label>
                          
                          <input type="text" name="nama_user" class="form-control" required placeholder="Masukan Nama User" value="<?= $dt['nama_user']; ?>">
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
                            Username
                          </label>
                          <input type="text" name="username" class="form-control" required placeholder="Masukan Username" value="<?= $dt['username']; ?>">
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>
                            Level User
                          </label>
                          <select class="form-control" name="level" required >
                            <option value="kasir" <?= ($dt['level'] == "kasir") ? 'selected' : ''; ?>>
                              Kasir
                            </option>
                            <option value="admin" <?= ($dt['level'] == "admin") ? 'selected' : ''; ?>>
                              Admin
                            </option>
                            <option value="owner" <?= ($dt['level'] == "owner") ? 'selected' : ''; ?>>
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
                          <input type="text" name="password" class="form-control" placeholder="Masukkan Password">
                          <small class="text-muted form-text">
                            Kosongkan Jika tidak mau mengubah password
                          </small>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-0">
                          <label>
                            Foto Profil
                          </label>
                          <input type="file" class="form-control" id="foto_profile" name="foto_profile" onchange="preview()">
                          <small class="form-text text-muted">
                            Kosongkan jika tidak ingin mengubah foto
                          </small>
                        </div>
                        <div class="form-group">
                          <img src="<?= base_url(); ?>/asset/img/<?= $dt['foto_profile']; ?>" alt="foto user" class="img-thumbnail" id="img_preview">
                        </div>
                        <button type="submit" name="ubah" class="btn btn-primary btn-icon icon-left float-right w-100 mt-0">
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
  <script src="<?= base_url(); ?>/asset/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/img_preview.js"></script>
  
</body>
</html>