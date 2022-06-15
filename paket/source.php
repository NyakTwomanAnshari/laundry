<?php
require("../init.php");
helper("basicCrud");

if(isset($_GET["id"])){
  $data = getOneData("tb_paket", "id_paket", $_GET["id"]);
  echo json_encode($data);
}else{
  echo null;
}