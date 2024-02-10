<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku_DAO();

try{
    $dbm->get_examount($_POST['user_id'],$_POST['dow']);
}catch(Exception $e){

}
?>