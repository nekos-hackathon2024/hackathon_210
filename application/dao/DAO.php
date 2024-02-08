<?php
//JSON形式で返却すること、文字形式がUTF-8だということの宣言

// config.php
require_once 'DAO_config.php';
Config::setConfigDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'config');

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=UTF-8');
class setuyaku_DAO extends Config
{
    
    //DB接続
    function dbconnect()
    {
        try {
            $pdo = new PDO('mysql:host='. Config::get('DB_HOST') .';dbname='. Config::get('DB_NAME') .';charset=utf8',Config::get('DB_USER'),Config::get('DB_PASS'));
        } catch (PDOException $e) {
            exit('Error connecting ' . $e);
        }
        return $pdo;
    }

    //新規登録
    function user_create($mail,$pass,$name){
        $pdo = $this->dbconnect();
        //既に登録されているメールアドレスかどうかを確認する
        $sql = "SELECT * FROM users WHERE user_mail = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $mail, PDO::PARAM_STR);
        $ps->execute();
        $check_mail = $ps->fetch();

        var_dump($check_mail['user_mail']);

        //既にメールアドレスが存在していた場合は登録処理を中止する
        if($check_mail['user_mail'] == $mail){
            return json_encode(0);
        //メールアドレスが存在していなかったらそのまま登録
        }else{
            $sql = 'INSERT INTO users(user_mail, user_pass, user_name) VALUES (?,?,?)';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $mail, PDO::PARAM_STR);
            $ps->bindValue(2, $pass, PDO::PARAM_STR);
            $ps->bindValue(3, $name, PDO::PARAM_STR);
            $ps->execute();
        }
    }

    //ログイン
    function user_login($mail,$pass){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM users WHERE user_mail = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $mail, PDO::PARAM_STR);
        $ps->execute();
        $user_data = $ps->fetch();

        return json_encode($user_data);

        // print_r($check_user_data);
        // if($check_user_data['user_pass'] === $pass){
        //     return json_encode("あうあう");
        // }else{
        //     return json_encode(0);
        // }
    }
}
?>