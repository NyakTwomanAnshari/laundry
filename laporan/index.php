<?php
require("../init.php");
helper(["auth", "basicCrud"]);
cekLogin();
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
  
  
</head>
<body>
  
  <div id="app">
    <div class="main-wrapper">
      
      <?php include("../template/navbar.php"); ?>
      
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Cetak Laporan</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Laporan</div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h4>
                    Cetak Laporan
                  </h4>
                </div>
                
                <div class="card-body pt-0">
                  <form action="<?= base_url(); ?>/cetak/laporan.php" method="post">
                    <div class="row">
                      <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                          <label>
                            Dari Tgl
                          </label>
                          <input type="date" name="tgl_masuk" id="" class="form-control" required>
                        </div>
                      </div>
                      
                      <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                          <label>
                            Sampai Tgl
                          </label>
                          <input type="date" name="tgl_selesai" id="" class="form-control" required>
                        </div>
                      </div>
                      
                      <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                          <label>
                            Nama Outlet
                          </label>
                          <select name="id_outlet" id="" class="form-control">
                            <option value="">
                              Semua Outlet
                            </option>
                            <?php foreach($outlet as $o) : ?>
                              <option value="<?= $o['id_outlet']; ?>">
                                <?= $o["id_outlet"]; ?> | <?= $o["nama_outlet"]; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-12 mt-0">
                        <button name="cetak" type="submit" class="btn btn-danger btn-icon icon-left float-right">
                          <i class="fas fa-print mr-2"></i>Cetak
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
  
</body>
</html>