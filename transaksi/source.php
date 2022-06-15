<?php
require("../init.php");

// data paket dr outlet
if(isset($_GET["id_outlet"])){
  $id_outlet = $_GET["id_outlet"];
  $result = mysqli_query($conDB, "SELECT * FROM tb_paket WHERE id_outlet = '$id_outlet' ");
  $row = [];
  while($r = mysqli_fetch_assoc($result)){
    $row[] = $r;
  }
  $opt ="<option value='' data-harga='0'>--Pilih Paket--</pilih>";
  $status = false;
  if(!empty($row)){
    foreach($row as $d){
      $opt .= "<option value='" . $d["id_paket"] . "' data-harga='" . $d['harga'] . "'>" . $d["jenis"] . " | " . $d["harga"] . "/kg</option>";
    }
    $status = true;
  }
  
  $outpt = [
    "opt" => $opt,
    "sts" => $status
  ];
  echo json_encode($outpt);
}


// get data outlet dr pelanggan
if(isset($_GET["id_pelanggan"])){
  $id_pelanggan = $_GET["id_pelanggan"];
  $result = mysqli_query($conDB, "SELECT * FROM tb_pelanggan WHERE id_pelanggan = '$id_pelanggan' ");
  $hasil = mysqli_fetch_assoc($result);
  echo json_encode($hasil);
}
