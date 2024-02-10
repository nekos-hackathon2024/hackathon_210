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
            $pdo = new PDO('mysql:host='. Config::get('DB_HOST') .';dbname='. Config::get('DB_NAME') .';charset=utf8',Config::get('DB_USER'),Config::get('DB_PASS'),
                    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

            // $pdo = new PDO('mysql:host=localhost;dbname=hackathon_210;charset=utf8', 'yuto', '1234',
                        //    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            // $pdo = new PDO('mysql:host=localhost;dbname=hackathon_210;charset=utf8', 'webuser', 'abccsd2',
                    // [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
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


        //既にメールアドレスが存在していた場合は登録処理を中止する
        if(strcmp($check_mail['user_mail'],$mail)==0){
            print json_encode(0);
        //メールアドレスが存在していなかったらそのまま登録
        }else{
            $sql = 'INSERT INTO users(user_mail, user_pass, user_name) VALUES (?,?,?)';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $mail, PDO::PARAM_STR);
            $ps->bindValue(2, $pass, PDO::PARAM_STR);
            $ps->bindValue(3, $name, PDO::PARAM_STR);
            $ps->execute();
            print json_encode(1);
        }
    }

    //ログイン
    function user_login($mail,$pass){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM users WHERE user_mail = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $mail, PDO::PARAM_STR);
        $ps->execute();

        //fetch成功（データ取得時のみ実行）
        if($user_data = $ps->fetch(PDO::FETCH_ASSOC)){
            //今回はハッシュ化無し（危険）でパスワード突き合わせ
            if($user_data['user_pass'] == $pass){
                //データ取得成功
                print json_encode($user_data);
            }else{
                //データ取得失敗
                print json_encode(0);
            }
        }else{
            //データ取得失敗
            print json_encode(0);
        }
    }

        
}
?>