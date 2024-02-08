<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku();

try{
    $dbm->user_login($_POST["user_mail"],$_POST["user_pass"]);
}catch(Exception $e){

}
?>