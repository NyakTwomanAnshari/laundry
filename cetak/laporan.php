<?php
require("../init.php");
require("../asset/dompdf/autoload.inc.php");
helper("auth");
cekLogin();

// reference the Dompdf namespace
use Dompdf\Dompdf;


if(isset($_POST["cetak"])){

$id_outlet = $_POST["id_outlet"];
$tgl_mulai = $_POST["tgl_masuk"];
$tgl_selesai = $_POST["tgl_selesai"];

$nm_apk = $nama_apilkasi_global;
if($id_outlet == ""){
  $nm_otl = "Semua Outlet";
}else{
  $rslt = mysqli_query($conDB, "SELECT nama_outlet FROM tb_outlet WHERE id_outlet = '$id_outlet' ");
  $nm_otl = mysqli_fetch_assoc($rslt)["nama_outlet"];
}

if($id_outlet != ""){
  $sql = "SELECT tb_transaksi.*, tb_outlet.nama_outlet, tb_pelanggan.nama_pelanggan FROM tb_transaksi JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id_outlet JOIN tb_pelanggan ON tb_transaksi.id_pelanggan = tb_pelanggan.id_pelanggan WHERE tb_transaksi.id_outlet = '$id_outlet' AND tb_transaksi.tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql2 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE id_outlet = '$id_outlet' AND tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql3 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE id_outlet = '$id_outlet' AND status_bayar = 'lunas' AND tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql4 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE id_outlet = '$id_outlet' AND status_bayar = 'belum' AND tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  
}else{
  $sql = "SELECT tb_transaksi.*, tb_outlet.nama_outlet, tb_pelanggan.nama_pelanggan FROM tb_transaksi JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id_outlet JOIN tb_pelanggan ON tb_transaksi.id_pelanggan = tb_pelanggan.id_pelanggan WHERE tb_transaksi.tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql2 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql3 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE status_bayar = 'lunas' AND tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
  $sql4 = "SELECT SUM(total_bayar) as ttl_byr FROM tb_transaksi WHERE status_bayar = 'belum' AND tgl_masuk BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}
$result = mysqli_query($conDB, $sql);
$result2 = mysqli_query($conDB, $sql2);
$result3 = mysqli_query($conDB, $sql3);
$result4 = mysqli_query($conDB, $sql4);
$dt = [];
while($r = mysqli_fetch_assoc($result)){
  $dt[] = $r;
}

$total_bayar = mysqli_fetch_assoc($result2)["ttl_byr"];
$total_lunas = mysqli_fetch_assoc($result3)["ttl_byr"];
$total_blm_lunas = mysqli_fetch_assoc($result4)["ttl_byr"];
//var_dump($total_blm_lunas); die;



$html = '
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    *{
      margin: 0;
      padding: 0;
    }
    body{
      padding: 10px 15px;
    }
    table#tbl{
      width: 100%;
    }
    #tbl tr{
      padding: 5px;
    }
    #tbl tr th{
      padding: 5px;
    }
    #tbl tr td{
      padding: 5px;
    }
  </style>
</head>
<body>
  
  <div style="text-align: center; font-size: 16px; margin-bottom: 15px">
    <h2 style="margin-bottom: 5px">' 
      .  $nm_apk  . 
    '</h2>
    <p style="margin-bottom: 10px">'
      . $nm_otl .
    '</p>
    *************************************
  </div>
  
  <div>
    <table cellpadding="0" cellspacing="0" border="1" id="tbl">
      <thead>
        <tr style="background: #d9d9d9">
          <th style="width:5%">
            No
          </th>
          <th style="width: 20%">
            Nama Pelanggan
          </th>
          <th style="width: 15%">
            Nama Outlet
          </th>
          <th style="width: 13%">
            Tgl Cuci
          </th>
          <th style="width: 13%">
            Tgl Selesai
          </th>
          <th style="7%">
            Berat
          </th>
          <th style="7%">
            Status
          </th>
          <th style="width: 20%">
            Total Biaya
          </th>
        </tr>
      </thead>
      
      <tbody>'; 
      
        $no = 1;
        foreach($dt as $d){
          $html .= '
           <tr>
              <td style="text-align: center">'
                . $no++ .
              '</td>
              <td>'
                . $d["nama_pelanggan"] . 
              '</td>
              <td>'
                . $d["nama_outlet"] .
              '</td>
              <td>'
                . date("d M Y", strtotime($d["tgl_masuk"])) .
              '</td>
              <td>'
                . date("d M Y", strtotime($d["tgl_selesai"])) .
              '</td>
              <td style="text-align: center">'
                . $d["berat"] . 'kg
              </td>
              <td style="text-align: center">'
                . $d["status_bayar"] .
              '</td>
              <td style="text-align: right">
                <b>'
                  . number_format($d["total_bayar"], 0, '.', '.') .
                '</b>
              </td>
            </tr>
          ';
        }

 
$html .= '</tbody>
      
      <tfoot>
        <tr style="background: #d2d2d2">
          <td colspan="7" style="text-align: right">
            <b>Total Biaya</b>
          </td>
          <td style="text-align: right">
            <b>
              Rp. '; 
              if($total_bayar != null){
                $html .= number_format($total_bayar, 0, '.', '.');
              }else{
                $html .= '0';
              }
$html .=    '</b>
          </td>
        </tr>
        <tr style="">
          <td colspan="7" style="text-align: right">
            <b>Total Lunas</b>
          </td>
          <td style="text-align: right">
            <b>
              Rp. ';
              if($total_lunas != null){
                $html .= number_format($total_lunas, 0, '.', '.');
              }else{
                $html .= '0';
              }
$html .=    '</b>
          </td>
        </tr>
        <tr style="background: #d2d2d2">
          <td colspan="7" style="text-align: right">
            <b>Total Belum Lunas</b>
          </td>
          <td style="text-align: right">
            <b>
              Rp. ';
              if($total_blm_lunas != null){
                $html .= number_format($total_blm_lunas, 0, '.', '.');
              }else{
                $html .= '0';
              }
$html .=    '</b>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  
  
  
</body>
</html>
';



  // instantiate and use the dompdf class
  $dompdf = new Dompdf();
  
  
  $dompdf->loadHtml($html);
  
  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper('A4', 'potrait');
  
  // Render the HTML as PDF
  $dompdf->render();
  
  // Output the generated PDF to Browser
  $dompdf->stream("laporan.pdf");


}
