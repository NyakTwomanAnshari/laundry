<?php
require("../init.php");
helper(["flasher", "auth"]);
cekLogout();

if(isset($_POST["login"])){
  $username = mysqli_real_escape_string($conDB, $_POST["username"]);
  $password = mysqli_real_escape_string($conDB, $_POST["password"]);
  
  // cek username
  // metode query biasa
  //$result = mysqli_query($conDB, "SELECT * FROM tb_user WHERE username = '$username' ");
  // metode dengan store procedure
  $result = mysqli_query($conDB, "CALL cekLogin('$username')"); // or die(mysqli_error($conDB));
  $cek = mysqli_num_rows($result);
  
  if($cek != 1){
    setBasicFlasher("danger", "Username / password salah!!");
    return redirect("/login/index.php"); 
  }
  
  // cek password
  $dt = mysqli_fetch_assoc($result);
  $pwSistem = $dt["password"];
  if(!password_verify($password, $pwSistem)){
    setBasicFlasher("danger", "Username / password salah!!");
    return redirect("/login/index.php"); 
  }
  
  // set session login
  $_SESSION["login"] = [
    "status" => true,
    "id" => $dt["id_user"],
    "level" => $dt["level"],
  ];
  
  return redirect("/dashboard/index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/css/stisla.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/css/components.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/asset/css/style.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <?= $nama_apilkasi_global; ?>
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body pt-0">
                <?= showBasicFlasher(); ?>
                <form method="post" action="">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                  </div>

                  <div class="form-group">
                   <label for="password" class="control-label">Password</label>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="login" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
              
                <div class="simple-footer">
                  Copyright &copy; 2021 By PunyaSaya
                  <p class="mt-0">
                    Template By <a href="https://getstisla.com/">Stisla</a>
                  </p>
                </div>
              </div>
        </div>
         </div>
       </div>
     </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="<?= base_url(); ?>/asset/js/jquery.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="<?= base_url(); ?>/asset/js/stisla.js"></script>
  <script src="<?= base_url(); ?>/asset/js/scripts_stisla.js"></script>
  <script src="<?= base_url(); ?>/asset/js/custom.js"></script>
  
  <script>
   //alert("ok");
   
   const abc = "abcokhsja";
    console.log("test")
  </script>
  <!-- Page Specific JS File -->
</body>
</html>
