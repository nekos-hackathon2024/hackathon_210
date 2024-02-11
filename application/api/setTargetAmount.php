<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku_DAO();

try{
    $dbm->set_target_amount($_POST["user_id"],$_POST["targetAmount"]);
}catch(Exception $e){

}
?>