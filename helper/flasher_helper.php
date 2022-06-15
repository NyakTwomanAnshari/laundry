<?php

function showFlasher()
{
  if(isset($_SESSION["CRUD"])){
    $data = $_SESSION["CRUD"];
    unset($_SESSION["CRUD"]);
    return "
    <div id='flash-crud' class='alert' data-title='". $data['title'] . "' data-text='" . $data['text'] . "' data-icon='" . $data['icon'] . "' data-flash=true>
    </div>
    ";
  }
}

function setFlasher($title, $icon, $text){
  $_SESSION["CRUD"] = [
                       'title' => $title,
                       'icon' => $icon,
                       'text' => $text
                      ]; 
}

function showBasicFlasher()
{
  if(isset($_SESSION["CRUDS"])){
    $data = $_SESSION["CRUDS"];  
    unset($_SESSION["CRUDS"]);
    return "
      <div class='alert alert-" . $data["type"] . " alert-dismissible fade show' role='alert'><b>" .
        $data["msg"] . 
      "</b><button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
    ";
  }
}

function setBasicFlasher($type, $msg)
{
  $_SESSION["CRUDS"] = [
                        "type" => $type,
                        "msg" => $msg
                      ];
}
