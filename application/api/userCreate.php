<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku();

try{
    $dbm->user_create($_POST["user_mail"],$_POST["user_pass"],$_POST["user_name"]);
}catch(Exception $e){

}
?>