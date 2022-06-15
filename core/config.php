<?php

// ini file untuk menaruh configurasi" dan konstanta"
session_start();
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "tgs_laundry_2");
// jangan gunakan tanda / pada akhir url
define("BASE_URL", "http://localhost/laundry");



date_default_timezone_set('Asia/Jakarta');

$nama_apilkasi_global = "GREA Laundry";
//var_dump(BASE_URL); die();

