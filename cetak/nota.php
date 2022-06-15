<?php 
require("../init.php");
require("../asset/dompdf/autoload.inc.php");
helper(["basicCrud", "auth"]);
adminKasir();

// reference the Dompdf namespace
use Dompdf\Dompdf;

$id_transaksi = $_GET["id"];
if(isset($id_transaksi)){
  
  // get Data transaksi
  $result = mysqli_query($conDB, "SELECT tb_transaksi.*, tb_outlet.nama_outlet, tb_pelanggan.nama_pelanggan, tb_paket.jenis FROM tb_transaksi JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id_outlet JOIN tb_pelanggan ON tb_transaksi.id_pelanggan = tb_pelanggan.id_pelanggan JOIN tb_paket ON tb_transaksi.id_paket = tb_paket.id_paket WHERE id_transaksi = '$id_transaksi' ");
  $dt = mysqli_fetch_assoc($result);
  
  // get data pakaian
  $result2 = mysqli_query($conDB, "SELECT jenis_pakaian, jumlah FROM tb_pakaian WHERE id_transaksi = '$id_transaksi' ");
  $dt_pakaian = [];
  while($r = mysqli_fetch_assoc($result2)){
    $dt_pakaian[] = $r;
  }
  
  // get data general (nama aplikasi)
  $nama_laundry = $nama_apilkasi_global;
  
  $html = '
<html>
<head>
	<meta http-equiv="X-UA-Compatible"  content="text/html" charset="utf-8">
	<title></title>
	<style type="text/css">
	  body{
	    padding: 0 15px;
	  }
		th{
			text-align: left;
		}
	</style>
</head>
<body>

	<div  style="font-size: 12px;text-align: center">
		
		<br>
		 <h2>'
		  . $nama_laundry .
		 '</h2>
		******************************************************

	</div>

	<div  style="font-size: 12px;text-align: left">

		<br/>
		Kode Transaksi : ' . $dt["id_transaksi"] . '<br>
		Nama Pelanggan : ' . $dt["nama_pelanggan"] .  '<br>
		Outlet         : ' . $dt["nama_outlet"] . '<br>
		Jenis Paket    : '. $dt["jenis"] . ' <br>
		Tgl Selesai    : ' . date("d M Y", strtotime($dt["tgl_selesai"])) . '
	</div>

	<div  style="font-size: 10px;text-align: left">
		<br>
		
		<table style="width: 100%;">
			<!-- header nota -->
			<tr>
				<th style="width:10%; padding: 5px; text-align: center">No</th>
				<th style="width:50%; padding: 5px">Jenis Pakaian</th>
				<th style="width:40%; padding:5px; text-align: center">Jumlah</th>
			</tr>

			<!-- isi nota contooh :  nama produk harga qty  -->
			';
			$no = 1;
			foreach($dt_pakaian as $dtp) {
			  $html .= '
          <tr style="padding: 5px">
            <td style="padding: 5px; text-align: center;">'
              . $no++ .
            '</td>
            <td style="padding: 5px">'
              . $dtp["jenis_pakaian"] .
            '</td>
            <td style="padding: 5px; text-align: center">'
              . $dtp["jumlah"] .
            '</td>
          </tr>
			  ';
			}
      
      $html .= '
			<!-- extra nota : total , kembalain , bayar  -->
			<tr style="text-align: left;">
				<td colspan="2"></td>
				<td><hr style="width:100%"></td>
			</tr>
			<tr style="text-align: right;">
				<td colspan="2">Total Berat Pakaian</td>
				<td style="font-weight: bold">'
				  . $dt["berat"] . 
				'kg</td>
			</tr>
			<tr style="text-align: right;">
				<td colspan="2">Total Harga</td>
				<td style="font-weight: bold"> Rp. '
				  . number_format($dt["total_bayar"], 0, '.', '.') .
				'</td>
			</tr>

		</table>

	</div>
	
	<!-- footer  -->
	<div  style="font-size: 11px;text-align: center">
	
		<br>
		<small>Terimakasih Sudah Mengunakan Jasa Laundry Kami</small>
		<br>
	______________________________
	</div>

</body>
</html>
  ';
  
  
  
  
  // instantiate and use the dompdf class
  $dompdf = new Dompdf();
  
  
  $dompdf->loadHtml($html);
  
  // (Optional) Setup the paper size and orientation
  $dompdf->setPaper('A6', 'potrait');
  
  // Render the HTML as PDF
  $dompdf->render();
  // generate nama file
  $nama_file = "nota_" . $dt["id_transaksi"] . ".pdf";
  // Output the generated PDF to Browser
  $dompdf->stream($nama_file, ["Attachment" => false]);
}



?>
