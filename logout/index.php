<?php
require("../init.php");
helper("auth");

cekLogin();

unset($_SESSION["login"]);
redirect("/login/index.php");
