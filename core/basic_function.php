<?php
// ini untuk function" basic

function base_url($url = ""){
  if($url == ""){
    return BASE_URL;
  }else {
    return BASE_URL . $url;
  }
}

function helper($param = ""){
  if(is_array($param)){
    foreach ($param as $p){
      include("../helper/" . $p . "_helper.php");
    }
  }else{
    include("../helper/" . $param . "_helper.php");
  }
}

function goBack(){
  echo "<script>
        window.location = history.go(-1);
       </script>";
}

function redirect($url){
  echo "<script>
        document.location = '" .base_url($url) . "';
       </script>";
}

