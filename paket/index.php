<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminOnly();

// tambah Data
if(isset($_POST["tambah"])){
  $id_outlet = $_POST["id_outlet"];
  $jenis = $_POST["jenis"];
  $harga = htmlspecialchars($_POST["harga"]);
  // cek apakah paket telah ada atau blm 
  // metode query biasa
  $cek = mysqli_query($conDB, "SELECT COUNT(*) AS hsl FROM tb_paket WHERE id_outlet = '$id_outlet' AND jenis = '$jenis' ");
  // metode menggunakan function database
  //$cek = mysqli_query($conDB, "SELECT cekPaket('$id_outlet', '$jenis') as hsl");
  
  // cek paket
  if(mysqli_fetch_assoc($cek)["hsl"] != 0){
    setFlasher("Oupss", "warning", "data-ini-sudah-ada!!");
    return redirect("/paket/index.php");
  }
  
  if(insertData("tb_paket", ['NULL', $id_outlet, $jenis, $harga]) > 0){
    setFlasher("Selamat", "success", "data-berhasil-ditambahkan");
  }else{
    setFlasher("Oupss", "error", "data-gagal-ditambahkan");
  }
}

// hapus data
if(isset($_GET["delete"]) && $_GET["delete"] == "true"){
  $id_paket = $_GET["id"];
  if(deleteData("tb_paket", "id_paket", $id_paket) > 0){
    setFlasher("Selamat", "success", "Data-berhasil-dihapus");
  }else{
    setFlasher("Oupss", "error", "Data-gagal-dihapus");
  }
  //var_dump($_SESSION); die;
  redirect("/paket/index.php");
  return false;
  
}

// edit data
if(isset($_POST["edit"])){
  $edit_id_paket = $_POST["edit_id_paket"];
  $edit_id_outlet = $_POST["edit_id_outlet"];
  $edit_jenis = $_POST["edit_jenis"];
  $edit_harga = $_POST["edit_harga"];
  $data_edit = [
    "id_outlet" => $edit_id_outlet,
    "jenis" => $edit_jenis,
    "harga" => $edit_harga
  ];
  if(editData("tb_paket", "id_paket", $edit_id_paket, $data_edit) >= 0){
    setFlasher("Selamat", "success", "Data-berhasil-diubah");
  }else{
    setFlasher("Oupss", "error", "Data-gagal-diubah");
  }
}

$dt = getAllData("tb_paket", true, "tb_outlet", "id_outlet");
$dt_outlet = getAllData("tb_outlet");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PAKET</title>
  
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
            <h1>Paket</h1>
            <?= showFlasher(); ?>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Paket</a></div>
              <div class="breadcrumb-item">Data Paket</div>
            </div>
          </div>
          
          <div class="row">
            <!-- tampil data -->
            <div class="col-md-8 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Data Paket
                  </h4>
                </div>
                <div class="card-body pt-0">
                  <div class="table-responsive ">
                    <table class="table-striped table" id="table">
                      <thead>
                        <tr>
                          <th>Id Paket</th>
                          <th>Nama Outlet</th>
                          <th>Jenis</th>
                          <th>Harga</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($dt as $d) : ?>
                         <tr>
                           <td class="text-center">
                             <?= $d["id_paket"]; ?>
                           </td>
                           <td>
                             <?= $d["nama_outlet"]; ?>
                           </td>
                           <td>
                             <?= $d["jenis"]; ?>
                           </td>
                           <td>
                             Rp. <?= number_format($d["harga"], 0 , '.', '.'); ?>
                           </td>
                           <td>
                            <div class="d-flex">
                              <button data-href="<?= base_url(); ?>/paket/source.php?id=<?= $d['id_paket']; ?>" class="btn btn-icon btn-info mr-1 btn-edit" data-toggle="modal" data-target="#modal">
                                <i class="far fa-edit "></i>
                              </button>
                              <!-- 
                              <a href="<?= base_url(); ?>/paket/index.php?delete=true&id=<?= $d['id_paket']; ?>" class="btn btn-icon btn-danger mr-1" onclick="return confirm('pastikan data ini sedang tdk di pakai di tabel lain atau Operasi anda akan gagal!!')">
                                <i class="fas fa-trash "></i>
                              </a>
                              -->
                             <button type="button" data-href="<?= base_url(); ?>/paket/index.php?delete=true&id=<?= $d['id_paket']; ?>" class="btn btn-icon btn-danger mr-1" data-text="pastikan data ini sedang tdk di pakai di tabel lain atau Operasi anda akan gagal!!" onclick="confirmHapus(this)">
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
            
            <!-- tambah data -->
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
                        Nama Outlet
                      </label>
                      <select name="id_outlet" class="form-control custom_select selectpicker" required data-live-search="true">
                        <option value="">-- pilih --</option>
                        <?php foreach($dt_outlet as $dto) : ?>
                         <option value="<?= $dto['id_outlet']; ?>">
                           <?= $dto["id_outlet"]; ?> | <?= $dto["nama_outlet"]; ?>
                         </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>
                        Jenis
                      </label>
                      <select name="jenis" class="form-control" required>
                        <option value="reguler">Reguler</option>
                        <option value="kilat">Kilat</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>
                        Harga
                      </label>
                      <input type="number" class="form-control" required name="harga" placeholder="0">
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
        
        <!-- modal edit -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Edit Data
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <div class="form-group">
            <label>
              ID Paket
            </label>
            <input type="text" name="edit_id_paket" id="edit_id_paket" class="form-control" required readonly>
          </div>
          <div class="form-group">
            <label>
              Nama Outlet
            </label>
            <select name="edit_id_outlet" id="edit_id_outlet" class="form-control ">
              <?php foreach($dt_outlet as $o) : ?>
               <option class="opt_edit_id_outlet" value="<?= $o['id_outlet']; ?>">
                  <?= $o["id_outlet"]; ?> | <?= $o["nama_outlet"]; ?>
               </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>
              Jenis
            </label>
            <select name="edit_jenis" id="edit_jenis" class="form-control">
              <option value="reguler" class="opt_edit_jenis">
                Reguler
              </option>
              <option value="kilat" class="opt_edit_jenis">
                Kilat
              </option>
            </select>
          </div>
          <div class="form-group">
            <label>
              Harga
            </label>
            <input type="number" name="edit_harga" id="edit_harga" required class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="edit">
          Simpan
        </button>
      </div>
        </form>
    </div>
  </div>
</div>
      </div>
      
      <!-- copyright -->
      <?php include("../template/copyright.php"); ?>
    </div>
  </div>
  
  
  
  
  
  
  <!-- basic javascript -->
  <?php include("../template/footer.php"); ?>
  
  <!-- javascript tambahan -->
  <script src="<?= base_url() ?>/asset/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/flasher.js" type="text/javascript" charset="utf-8"></script>
  <script src="<?= base_url(); ?>/asset/js/ajax_paket.js" type="text/javascript" charset="utf-8"></script>
  
</body>
</html>