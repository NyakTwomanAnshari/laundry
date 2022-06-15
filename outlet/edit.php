<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminOnly();

// get Data
$id_outlet = $_GET["id"];
$dt = getOneData("tb_outlet", "id_outlet", $id_outlet);

// validasi
if(!isset($id_outlet) || $id_outlet == "" || $id_outlet == null || $dt == null){
  redirect("/outlet/index.php");
}


// cek tombol submit 
if(isset($_POST["ubah"])){
  // ambil data
  $id_outlet = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["id_outlet"]));
  $nama_outlet = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["nama_outlet"]));
  $alanat_outlet = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["alamat_outlet"]));
  $telp = mysqli_real_escape_string($conDB, htmlspecialchars($_POST["telp"]));
  $data_edit = [
    "nama_outlet" => $nama_outlet,
    "alamat_outlet" => $alanat_outlet,
    "telp" => $telp
  ];
  //var_dump(is_numeric($telp)); die;
  if(!is_numeric($telp)){
    setBasicFlasher("danger", "No Telp harus berupa angka!!");
    return redirect("/outlet/edit.php?id=" . $id_outlet);
  }
  
  if(editData("tb_outlet", "id_outlet", $id_outlet, $data_edit) >= 0){
    setFlasher("Selamat", "success", "Data-berhasil-diubah");
    return redirect("/outlet/index.php");
  }else{
    setBasicFlasher("danger", "Oupss maaf nampaknya terjadi kesalahan");
    return redirect("/outlet/edit.php?id=" . $id_outlet);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>OUTLET | EDIT DATA</title>
  
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
            <h1>Edit Data</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Outlet</a></div>
              <div class="breadcrumb-item">Edit Data</div>
            </div>
          </div>
          
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>
                      Edit Data 
                    </h4>
                  </div>
                  <div class="card-body pt-0">
                    <?= showBasicFlasher(); ?>
                    <form action="" method="post">
                      <div class="row">
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group">
                            <label>
                              ID Outlet
                            </label>
                            <input type="text" readonly class="form-control" name="id_outlet" required value="<?= $dt['id_outlet']; ?>">
                          </div>
                          <div class="form-group">
                            <label>
                              Nama Outlet
                            </label>
                            <input type="text" required class="form-control" name="nama_outlet" value="<?= $dt['nama_outlet']; ?>">
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group">
                            <label>
                              No Telp
                            </label>
                            <input type="text" required class="form-control" name="telp" value="<?= $dt['telp']; ?>">
                          </div>
                          <div class="form-group">
                            <label>
                              Alamat
                            </label>
                            <textarea name="alamat_outlet" id="alamat" cols="30" rows="10" class="form-control"><?= $dt["alamat_outlet"]; ?></textarea>
                          </div>
                          <button class="btn btn-primary float-right" type="submit" name="ubah">
                            Simpan
                          </button>
                          <a href="<?= base_url(); ?>/outlet/index.php" class="btn btn-danger float-right mr-2">
                            Kembali
                          </a>
                        </div>
                      </div>
                    </form>
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