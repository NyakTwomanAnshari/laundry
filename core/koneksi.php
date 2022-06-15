<?php

// ini adalah konseksi
$conDB = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// cek koneksi
if (mysqli_connect_error()) {
  echo("koneksi gagal : " . mysqli_connect_error()); die;
}