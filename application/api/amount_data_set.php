<?php
require_once "../dao/DAO.php";
$dbm = new setuyaku_DAO();

try{
    $dbm->amount_data_set($_POST["dow"],$_POST["Amount"],$_POST["user_id"],$_POST["food_ex"],$_POST["trans_ex"],$_POST["enterme_ex"],$_POST["others"],$_POST["today"]);
}catch(Exception $e){

}
?>