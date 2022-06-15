<?php
require("../init.php");
helper(["basicCrud", "flasher", "auth"]);
adminOnly();

// hapus data
if(isset($_GET["delete"]) && $_GET["delete"] == true){
  $delete_id_user = $_GET["id"];
  if(isset($delete_id_user) && $delete_id_user != ""){
    $foto_lama = getOneData("tb_user", "id_user", $delete_id_user)["foto_profile"];
    if(deleteData("tb_user", "id_user", $delete_id_user) > 0){
      if($foto_lama != "profile.jpg"){
          if(file_exists("../asset/img/" . $foto_lama)){
            unlink("../asset/img/" . $foto_lama);
          }
      }
      setBasicFlasher("success", "Data BERHASIL Di hapus!!");
    }else{
      setBasicFlasher("danger", "Data GAGAL Di hapus!!");
    }
  }
  return redirect("/users/index.php");
}

// get data user
$dt = getAllData("tb_user", true, "tb_outlet", "id_outlet", "LEFT", "tb_user.*, tb_outlet.nama_outlet");


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>USER</title>
  
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
            <h1>User</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item"><a href="#">User</a></div>
              <div class="breadcrumb-item">Data User</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Data User
                  </h4>
                  <div class="card-header-form d-md-inline d-sm-block">
                    <a href="<?= base_url(); ?>/users/tambah.php" class="btn btn-icon btn-primary icon-left float-right btnTambah"><i class="fas fa-plus mr-1"></i>Tambah Data</a>
                  </div>
                </div>
                
                <div class="card-body pt-0">
                  <?= showBasicFlasher(); ?>
                  <div class="table-responsive">
                    <table class="table table-striped" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>foto</th>
                          <th>Nama</th>
                          <th>Username</th>
                          <th>Outlet</th>
                          <th>Level</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      
                      <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($dt as $d) : ?>
                         <tr>
                           <td class="text-center align-middle">
                             <?= $no++; ?>
                           </td>
                           <td class="align-middle">
                             <img src="<?= base_url(); ?>/asset/img/<?= $d['foto_profile']; ?>" alt="Foto profil" id="imgDisplayUser" class="rounded-circle border border-dark" >
                           </td>
                           <td class="align-middle">
                             <?= $d["nama_user"]; ?>
                           </td>
                           <td class="align-middle">
                             <?= $d["username"]; ?>
                           </td>
                           <td class="align-middle text-center">
                             <?php if($d["id_outlet"] != null) : ?>
                               <?= $d["nama_outlet"]; ?>
                             <?php else : ?>
                               <b>-</b>
                             <?php endif; ?>
                           </td>
                           <td class="align-middle">
                             <?php if($d["level"] == "admin") : ?>
                               <b class="text-primary">
                                 <?= $d["level"]; ?>
                               </b>
                             <?php elseif($d["level"] == "kasir") : ?>
                               <b class="text-warning">
                                 <?= $d["level"]; ?>
                               </b>
                             <?php else : ?>
                               <b class="text-success">
                                 <?= $d["level"]; ?>
                               </b>
                             <?php endif; ?>
                           </td>
                           <td class="align-middle">
                             <div class="d-flex">
                               <a href="<?= base_url(); ?>/users/edit.php?id=<?= $d['id_user']; ?>" class="btn btn-icon btn-info mr-1">
                                 <i class="fas fa-edit">      </i>
                               </a>
                               
                               <button type="button" data-href="<?= base_url(); ?>/users/index.php?delete=true&id=<?= $d['id_user']; ?>" class="btn btn-icon btn-danger mr-1" data-text="pastikan data ini tidak sedang dipakai di tabel lain atau operasi anda akan gagal!! " onclick="confirmHapus(this)">
                                 <i class="fas fa-trash">      </i>
                               </a>
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