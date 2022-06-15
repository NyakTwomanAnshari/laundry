<?php

function getSisaHari($selisih)
{
  $hari = 0;
  $jam = 0;
  $hari = floor($selisih / (60 * 60 * 24));
  $selisih = $selisih - ($hari * 60 * 60 * 24);
  $jam = floor($selisih / (60 * 60));
  return "$hari hari $jam jam";
}

function cetakKeterangan($tgl_selesai, $status_cucian) 
{
  $dtkTglSelesai = strtotime($tgl_selesai);
  $dtkSaatIni = strtotime(date("Y-m-d"));
  $selisih = $dtkTglSelesai - $dtkSaatIni;
  $outpt = "";
  
  if($status_cucian == "proses") {
    $sisaWaktu = getSisaHari(abs($selisih));
    if($selisih < 0 ) {
      $outpt = "<span class='text-danger'>Telah Lewat <b>$sisaWaktu</b><span>";
    } else {
      $outpt = "<span class='text-primary'>Kurang $sisaWaktu lagi</span>";
    }
    
  } elseif ($status_cucian == "selesai") {
    $outpt = "<span class='text-success'> Cucian Telah Selesai Dan siap Diambil</span>";
  } else {
    $outpt = "<span class='text-warning'> Cucian Telah Diambil</span>";
  }
  
  return $outpt;
}