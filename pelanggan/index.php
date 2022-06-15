<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminKasir();

// hapus data 
if(isset($_GET["delete"]) && $_GET["delete"] == true){
  $delete_id_pelanggan = $_GET["id"];
  if(isset($delete_id_pelanggan) && $delete_id_pelanggan != ""){
    if(deleteData("tb_pelanggan", "id_pelanggan", $delete_id_pelanggan) > 0){
      setBasicFlasher("success", "Data BERHASIL Di hapus!!");
    }else{
      setBasicFlasher("danger", "Data GAGAL Di hapus!!");
    }
  }
  return redirect("/pelanggan/index.php");
}

// get data pelanggan
$dt = getAllData("tb_pelanggan", true, "tb_outlet", "id_outlet", "INNER", "tb_pelanggan.*, tb_outlet.nama_outlet");


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PELANGGAN</title>
  
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
            <h1>Data Pelanggan</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">Panggan</a></div>
              <div class="breadcrumb-item">Data Pelanggan</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Data Pelanggan
                  </h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/pelanggan/tambah.php" class="btn btn-icon btn-primary icon-left float-right btnTambah"><i class="fas fa-plus mr-1"></i>Tambah Data</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showBasicFlasher(); ?>
                  <div class="table-responsive">
                    <table class="table table-striped " id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Aksi</th>
                          <th>ID Pelanggan</th>
                          <th>Nama Pelanggan</th>
                          <th>Alamat</th>
                          <th>No Telp</th>
                          <th>Outlet</th>
                          <th>Jenis</th>
                        </tr>
                      </thead>
                      
                      <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($dt as $d) : ?>
                         <tr>
                           <td class="text-center">
                             <?= $no++; ?>
                           </td>
                           <td>
                             <div class="d-flex">
                               <a href="<?= base_url(); ?>/pelanggan/edit.php?id=<?= $d['id_pelanggan']; ?>" class="btn btn-icon btn-info mr-1">
                                 <i class="fas fa-edit">      </i>
                               </a>
                               
                               <button type="button" data-href="<?= base_url(); ?>/pelanggan/index.php?delete=true&id=<?= $d['id_pelanggan']; ?>" class="btn btn-icon btn-danger mr-1" data-text="pastikan data ini sedang tdk di pakai di tabel lain atau Operasi anda akan gagal!!" onclick="confirmHapus(this)">
                                 <i class="fas fa-trash">      </i>
                               </button>
                             </div>
                           </td>
                           <td class="text-center">
                             <?= $d["id_pelanggan"]; ?>
                           </td>
                           <td>
                             <?= $d["nama_pelanggan"]; ?>
                           </td>
                           <td>
                             <?= $d["alamat"]; ?>
                           </td>
                           <td>
                             <?= $d["telp"]; ?>
                           </td>
                           <td>
                             <?= $d["nama_outlet"]; ?>
                           </td>
                           <td>
                             <?php if($d["jenis_langganan"] == "reguler") : ?>
                              <b class="text-primary">
                                <?= $d["jenis_langganan"];    ?>
                              </b>
                             <?php else: ?>
                              <b class="text-success">
                                <?= $d["jenis_langganan"];
                                ?>
                              </b>
                             <?php endif; ?>
                           </td>
                         </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
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