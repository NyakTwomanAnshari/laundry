<?php

function cekLogin(){
  global $conDB;
  /*
  $dt = $_SESSION["login"];
  
  if(!isset($dt) || $dt["status"] != true ){
    return redirect("/login/index.php");
  }
  */
  if(!isset($_SESSION["login"])){
    return redirect("/login/index.php");
  }
  
  $id_user = $_SESSION["login"]["id"];

  $result = mysqli_query($conDB, "SELECT * FROM tb_user WHERE id_user = '$id_user' ");
  $cek = mysqli_fetch_assoc($result);
 
  if($cek == null || $cek == false){
    return redirect("/login/index.php");
  }
  
}

function cekLogout(){
  
  if(isset($_SESSION["login"]) && $_SESSION["login"] == "true"){
    return redirect("/dashboard/index.php");
  }
}


function getDataUser($param = null){
  global $conDB;
  $id_user = $_SESSION["login"]["id"];
  $result = mysqli_query($conDB, "SELECT * FROM tb_user WHERE id_user = '$id_user' ");
  $dt = mysqli_fetch_assoc($result);
  
  if($param == null){
    return $dt;
  }else{
    return $dt[$param];
  }
}

function adminOnly(){
  cekLogin();
  if($_SESSION["login"]["level"] != "admin"){
    return redirect("/login/index.php");
  }
}

function adminKasir(){
  cekLogin();
  if($_SESSION["login"]["level"] != "admin" && $_SESSION["login"]["level"] != "kasir"){
    return redirect("/login/index.php");
  }
}
  
  
  