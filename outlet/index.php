<?php
require("../init.php");
helper(["flasher", "basicCrud", "auth"]);
adminOnly();

// tambah data
if(isset($_POST["tambah"])){
  $id_outlets = $_POST["id_outlet"];
  $nama_outlet = htmlspecialchars($_POST["nama_outlet"]);
  $alamat_outlet = htmlspecialchars($_POST["alamat_outlet"]);
  $telp = htmlspecialchars($_POST["telp"]);
  if(!is_numeric($telp)){
    setFlasher("Oupss", "error", "data-gagal-ditambahkan");
    redirect("/outlet/index.php");
    return false;
  }
  if(insertData("tb_outlet", [$id_outlets, $nama_outlet, $alamat_outlet, $telp]) > 0){
    setFlasher("Selamat", "success", "data-berhasil-ditambahkan");
  }else{
    setFlasher("Oupse", "error", "data-gagal-ditambahkan");
  }
}

// hapus data
if(isset($_GET["delete"]) && $_GET["delete"] == "true"){
  $id_outlet = $_GET["id"];
  if(deleteData("tb_outlet", "id_outlet", $id_outlet) > 0){
    setFlasher("Selamat", "success", "Data-berhasil-dihapus");
  }else{
    setFlasher("Oupss", "error", "Data-gagal-dihapus");
  }
  //var_dump($_SESSION); die;
  redirect("/outlet/index.php");
  return false;
  
}

// get next_id_outlet 
$result = mysqli_query($conDB, "SELECT * FROM tb_outlet ORDER BY id_outlet DESC");
$id_terakhir = mysqli_fetch_assoc($result);
if($id_terakhir == null){
  $id_terakhir = 0;
}else{
  $id_terakhir = $id_terakhir["id_outlet"];
  $id_terakhir = (int) substr($id_terakhir, 3);
}
$id_terakhir = $id_terakhir + 1;
$itr = "OTL000";
if($id_terakhir >= 1000){
  $itr = "OTL";
}elseif ($id_terakhir >= 100) {
  $itr = "OTL0";
}elseif($id_terakhir >= 10){
  $itr = "OTL00";
} 
$next_id = $itr . $id_terakhir;


// get data outlet
$dt = getAllData("tb_outlet");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>OUTLET</title>
  
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
            <h1>Data Outlet</h1>
            <?= showFlasher(); ?>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Outlet</a></div>
              <div class="breadcrumb-item">Data Outlet</div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Data Outlet
                  </h4>
                </div>
                <div class="card-body pt-0">
                  <div class="table-responsive ">
                    <table class="table-striped table" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>ID Outlet</th>
                          <th>Nama Outlet</th>
                          <th>Alamat</th>
                          <th>No Telp</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($dt as $d) : ?>
                         <tr>
                           <td class="text-center">
                             <?= $no++; ?>
                           </td>
                           <td class="text-center">
                             <?= $d["id_outlet"]; ?>
                           </td>
                           <td>
                             <?= $d["nama_outlet"]; ?>
                           </td>
                           <td>
                             <?= $d["alamat_outlet"]; ?>
                           </td>
                           <td>
                             <?= $d["telp"]; ?>
                           </td>
                           <td>
                            <div class="d-flex">
                              <a href="<?= base_url(); ?>/outlet/edit.php?id=<?= $d['id_outlet']; ?>" class="btn btn-icon btn-info mr-1 btn-edit" >
                                <i class="far fa-edit "></i>
                              </a>
                              <!--
                              <a href="<?= base_url(); ?>/outlet/index.php?delete=true&id=<?= $d['id_outlet']; ?>" class="btn btn-icon btn-danger mr-1 " onclick="return confirm('pastikan data ini sedang tdk di pakai di tabel lain atau Operasi anda akan gagal!!')" >
                                <i class="fas fa-trash "></i>
                              </a>
                              -->
                              <button type="button" class="btn btn-icon btn-danger mr-1 " onclick="confirmHapus(this)" data-href="<?= base_url(); ?>/outlet/index.php?delete=true&id=<?= $d['id_outlet']; ?>" data-text="pastikan data ini sedang tdk di pakai di tabel lain atau Operasi anda akan gagal!!">
                                <i class="fas fa-trash "></i>
                              </button>
                             
                            </div>
                           </td>
                         </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Tambah Data
                  </h4>
                </div>
                <div class="card-body pt-0">
                  <form action="" method="post">
                    <div class="form-group">
                      <label>
                        ID Outlet
                      </label>
                      <input type="text" name="id_outlet" value="<?= $next_id; ?>" readonly class="form-control" readonly>
                    </div>
                    <div class="form-group">
                      <label>
                        Nama Outlet
                      </label>
                      <input type="text" class="form-control" name="nama_outlet" required placeholder="Nama Outlet">
                    </div>
                    <div class="form-group">
                      <label>
                        No Telp
                      </label>
                      <input type="text" class="form-control" name="telp" required placeholder="08xxx">
                    </div>
                    <div class="form-group">
                      <label>
                        Alamat
                      </label>
                      <textarea name="alamat_outlet" id="alamat" cols="30" rows="10"  class="form-control" required placeholder="Alamat"></textarea>
                    </div>
                    <button class="btn btn-primary mr-2" type="submit" name="tambah">
                      Tambah
                    </button>
                    <button class="btn btn-danger" type="reset">
                      Reset
                    </button>
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
  <script src="<?= base_url(); ?>/asset/js/flasher.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>