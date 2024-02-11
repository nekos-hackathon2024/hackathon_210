<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku_DAO();

try{
    $dbm->set_target_name($_POST["user_id"],$_POST["targetName"]);
}catch(Exception $e){

}
?>