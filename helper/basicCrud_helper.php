<?php

function query($sql, $all = true)
{
  global $conDB;
  if ($all == true) {
    $result = mysqli_query($conDB, $sql);
    $row = [];
    while ($r = mysqli_fetch_assoc($result)) {
      $row[] = $r;
    }
    return $row;
  } else {
    $result = mysqli_query($conDB, $sql);
    return mysqli_fetch_assoc($result);
  }
}

function getAllData($tbl, $join = false, $tbl_reference = null, $id_join = null, $typeJoin = "INNER" ,$data = "*")
{
  global $conDB;
  if($join != false){
    $sql =  "SELECT $data FROM $tbl $typeJoin JOIN $tbl_reference ON $tbl.$id_join = $tbl_reference.$id_join";
  }else {
    $sql = "SELECT $data FROM $tbl";
  }
  $result = mysqli_query($conDB, $sql);
  //var_dump($result); die;
  $row = [];
  while($r = mysqli_fetch_assoc($result)){
    $row[] = $r;
  }
  return $row;
}

function getOneData($tbl, $filed = null, $isi_filed = null, $join = false, $tbl_reference = null, $id_join = null, $typeJoin = "INNER" , $data = "*")
{
  global $conDB;
  if($join != false){
    $sql  = "SELECT $data FROM $tbl $typeJoin JOIN $tbl_reference ON $tbl.$id_join = $tbl_reference.$id_join WHERE $filed = '$isi_filed' ";
  }else{
    $sql = "SELECT $data FROM $tbl WHERE $filed = '$isi_filed' ";
  }
  $result = mysqli_query($conDB, $sql);
  return mysqli_fetch_assoc($result);
}

function insertData($tbl, $data )
{
  global $conDB;
  $sql = "INSERT INTO $tbl VALUES(";
  foreach ($data as $d){
    if($d == "NULL"){
     $sql .= "$d, ";
    }else{
     $sql .= "'$d', ";
    }
  }
  
  $sql = substr(trim($sql), 0, -1);
  
  $sql .= ")";
  //var_dump($sql); die;
  mysqli_query($conDB, $sql) or die(mysqli_error($conDB));
 
  return mysqli_affected_rows($conDB);
}

function editData($tbl, $field_primary, $isi_filed, $data = [])
{
  global $conDB;
  $sql = "UPDATE $tbl SET ";
  foreach ($data as $k => $v){
    if($v == "NULL"){
      $sql .= $k . " = $v, ";
    }else{
      $sql .= $k . " = '$v', ";
    }
  }
  $sql = substr(trim($sql), 0, -1);
  $sql .= " WHERE $field_primary = '$isi_filed' ";
  //var_dump($sql); die;
  mysqli_query($conDB, $sql);
  return mysqli_affected_rows($conDB);
}

function deleteData($tbl, $field_primary, $isi_filed)
{
  global $conDB;
  $sql = "DELETE FROM $tbl WHERE $field_primary = '$isi_filed' ";
  $result = mysqli_query($conDB, $sql);
  return mysqli_affected_rows($conDB);
}