<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
cekLogin();
// grt id user
$getIdUser = getDataUser("id_user");

// get data user
$dt = getOneData("tb_user", "id_user", $getIdUser, true, "tb_outlet", "id_outlet", "LEFT", "tb_user.*, tb_outlet.nama_outlet");

// ubah data
if(isset($_POST["ubah"])){
  $id_user = $getIdUser;
  $nama_user = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_user"]));
  $username = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["username"]));
  $password = $_POST["password"];
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
        setFlasher("Opuss", "warning", "Ekstensi-file-tidak-sesuai!!");
        return redirect("/profile/index.php");
      }
      
      // cek ukuran gambar
      if($ukuran > 2000000){
        setFlasher("Opuss", "warning", "Ukuran-file-terlalu-besar!!");
        return redirect("/profile/index.php");
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
    $data_update = [
      "nama_user" => $nama_user,
      "username" => $username,
      "foto_profile" => $foto_profile
    ];
    if($password != "" || $password != null){
      $password = password_hash($password, PASSWORD_DEFAULT);
      $data_update["password"] = $password;
    }
    if(editData("tb_user", "id_user", $id_user, $data_update) >= 0){
      setFlasher("Selamat", "success", "Berhasil-update-data!!");
      return redirect("/profile/index.php");
    }else{
      setFlasher("Opuss", "error", "Gagal-update-data!!");
      return redirect("/profile/index.php");
    }
  }else{
    setFlasher("Opuss", "error", "Gagal-update-data!!");
    return redirect("/profile/index.php");
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
  <title>Dashboard</title>
  
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
            <h1>My Profile</h1>
            <?= showFlasher(); ?>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">My Profile</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-5 col-sm-12 ">
              <div class="card author-box card-primary">
                <div class="card-body">
                  <div class="author-box-left">
                    <img alt="image" src="../asset/img/<?= $dt['foto_profile']; ?>" class="rounded-circle author-box-picture mb-2">
                    <div class="clearfix"></div>
                    <b class="">
                      <?= $dt["username"]; ?>
                    </b>
                  </div>
                  <div class="author-box-details">
                    <div class="author-box-name text-center">
                      <a href="#">
                      <?= $dt["nama_user"]; ?>
                      </a>
                    </div>
                    <div class="author-box-job text-center">
                      <?= $dt["level"]; ?>
                    </div>
                    <div class="author-box-job text-center">
                      <?php if($dt["nama_outlet"] == null) : ?>
                        -
                      <?php else : ?>
                        <?= $dt["nama_outlet"]; ?>
                      <?php endif; ?>

                    </div>

                  </div>
                </div>
              </div>
        </div>
        
            <div class="col-md-7 col-sm-12">
              <div class="card card-info">
                <div class="card-header ">
                  <h4 class="text-info">Edit Data</h4>
                </div>
                
                <div class="card-body pt-0 ">
                  <?= showBasicFlasher(); ?>
                  <form action="" method="post" enctype="multipart/form-data" >
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
                            Username
                          </label>
                          <input type="text" name="username" class="form-control" required placeholder="Masukan Username" value="<?= $dt['username']; ?>">
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
  <script src="<?= base_url(); ?>/asset/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/img_preview.js"></script>
  <script src="<?= base_url(); ?>/asset/js/flasher.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>