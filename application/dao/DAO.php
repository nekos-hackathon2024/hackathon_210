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
            //         [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
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
        if(strcmp($check_mail['user_mail'],$mail) == 0){
            print json_encode(1);
        //メールアドレスが存在していなかったらそのまま登録
        }else{
            $sql = 'INSERT INTO users(user_mail, user_pass, user_name) VALUES (?,?,?)';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $mail, PDO::PARAM_STR);
            $ps->bindValue(2, $pass, PDO::PARAM_STR);
            $ps->bindValue(3, $name, PDO::PARAM_STR);
            $ps->execute();
            print json_encode(0);
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
                print json_encode(1);
            }
        }else{
            //データ取得失敗
            print json_encode(1);
        }
    }

    //支出登録
    function set_amount_data($dow,$amount,$id,$food_ex,$trans_ex,$enterme_ex,$others,$today){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM amounts WHERE user_id = ? && today = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->bindValue(2, $today, PDO::PARAM_STR);
        $ps->execute();
        //既にこの日にデータを登録していたら更新処理、していないなら登録
        if($data = $ps->fetch(PDO::FETCH_ASSOC)){
            $sql = 'UPDATE amounts SET dow = ?, Amount = ?,  user_id = ?, food_ex = ?,trans_ex = ?,enterme_ex = ?, others = ?, today = ? WHERE user_id = ? && today = ?';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $dow, PDO::PARAM_STR);
            $ps->bindValue(2, $amount, PDO::PARAM_INT);
            $ps->bindValue(3, $id, PDO::PARAM_INT);
            $ps->bindValue(4, $food_ex, PDO::PARAM_INT);
            $ps->bindValue(5, $trans_ex, PDO::PARAM_INT);
            $ps->bindValue(6, $enterme_ex, PDO::PARAM_INT);
            $ps->bindValue(7, $others, PDO::PARAM_INT);
            $ps->bindValue(8, $today, PDO::PARAM_STR);
            $ps->bindValue(9, $id, PDO::PARAM_INT);
            $ps->bindValue(10, $today, PDO::PARAM_STR);
            $ps->execute();
            print json_encode("更新成功");
        }else{
            $sql = 'INSERT INTO amounts(dow,Amount,user_id,food_ex,trans_ex,enterme_ex,others,today) VALUES (?,?,?,?,?,?,?,?)';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $dow, PDO::PARAM_STR);
            $ps->bindValue(2, $amount, PDO::PARAM_INT);
            $ps->bindValue(3, $id, PDO::PARAM_INT);
            $ps->bindValue(4, $food_ex, PDO::PARAM_INT);
            $ps->bindValue(5, $trans_ex, PDO::PARAM_INT);
            $ps->bindValue(6, $enterme_ex, PDO::PARAM_INT);
            $ps->bindValue(7, $others, PDO::PARAM_INT);
            $ps->bindValue(8, $today, PDO::PARAM_STR);
            $ps->execute();
            print json_encode("登録成功");
        }

    }
    //予想金額登録
    function set_examount_data($dow,$amount,$id,$food_ex,$trans_ex,$enterme_ex,$others){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM amounts WHERE user_id = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->execute();
        //既にこの日にデータを登録していたら更新処理、していないなら登録
        if($data = $ps->fetch(PDO::FETCH_ASSOC)){
            $sql = 'UPDATE examounts SET dow = ?, exAmount = ?,  user_id = ?, food_ex = ?,trans_ex = ?,enterme_ex = ?, others = ? WHERE user_id = ?';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $dow, PDO::PARAM_STR);
            $ps->bindValue(2, $amount, PDO::PARAM_INT);
            $ps->bindValue(3, $id, PDO::PARAM_INT);
            $ps->bindValue(4, $food_ex, PDO::PARAM_INT);
            $ps->bindValue(5, $trans_ex, PDO::PARAM_INT);
            $ps->bindValue(6, $enterme_ex, PDO::PARAM_INT);
            $ps->bindValue(7, $others, PDO::PARAM_INT);
            $ps->bindValue(8, $id, PDO::PARAM_INT);
            $ps->execute();
            print json_encode("更新成功");
        }else{
            $sql = 'INSERT INTO examounts(dow,exAmount,user_id,food_ex,trans_ex,enterme_ex,others) VALUES (?,?,?,?,?,?,?)';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $dow, PDO::PARAM_STR);
            $ps->bindValue(2, $amount, PDO::PARAM_INT);
            $ps->bindValue(3, $id, PDO::PARAM_INT);
            $ps->bindValue(4, $food_ex, PDO::PARAM_INT);
            $ps->bindValue(5, $trans_ex, PDO::PARAM_INT);
            $ps->bindValue(6, $enterme_ex, PDO::PARAM_INT);
            $ps->bindValue(7, $others, PDO::PARAM_INT);
            $ps->execute();
            print json_encode("登録成功");
        }

    }

    //予想支出情報取得
    function get_examount($id,$dow){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM examounts WHERE user_id = ? && dow = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->bindValue(2, $dow, PDO::PARAM_STR);
        $ps->execute();

        if($examount_data = $ps->fetch(PDO::FETCH_ASSOC)){
            print json_encode($examount_data['exAmount']);
        }else{
            print json_encode("情報の取得に失敗しました。");
        }
    }

    //支出実績取得
    function get_amount($id,$dow){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM amounts WHERE user_id = ? && dow = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->bindValue(2, $dow, PDO::PARAM_STR);
        $ps->execute();

        if($amount_data = $ps->fetch(PDO::FETCH_ASSOC)){
            print json_encode($amount_data['Amount']);
        }else{
            print json_encode("情報の取得に失敗しました。");
        }
    }
        
    //4つの支出予測データをそれぞれ取得する
    function get_examounts($id,$dow){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM examounts WHERE user_id = ? && dow = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->bindValue(2, $dow, PDO::PARAM_STR);
        $ps->execute();

        if($examount_data = $ps->fetch(PDO::FETCH_ASSOC)){
            print json_encode([$examount_data['food_ex'], $examount_data['trans_ex'], $examount_data['enterme_ex'], $examount_data['others']]);
        }else{
            print json_encode("情報の取得に失敗しました。");
        }
    }

    //4つの支出データをそれぞれ取得する
    function get_amounts($id,$dow){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM amounts WHERE user_id = ? && dow = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_INT);
        $ps->bindValue(2, $dow, PDO::PARAM_STR);
        $ps->execute();

        if($amount_data = $ps->fetch(PDO::FETCH_ASSOC)){
            print json_encode([$amount_data['food_ex'], $amount_data['trans_ex'], $amount_data['enterme'], $amount_data['others'], $amount_data['date']]);
        }else{
            print json_encode("情報の取得に失敗しました。");
        }
    }

    //目標金額の設定
    function set_target_amount($id, $ta){
        $pdo = $this->dbconnect();
        //既に登録されているメールアドレスかどうかを確認する
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_STR);
        $ps->execute();
        $record = $ps->fetch();

        if(strcmp($record['id'], $record) == 0){
            print json_encode(1);
        }else{
            $sql = 'UPDATE users SET targetAmount = ?; WHERE user_id = ?';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $ta, PDO::PARAM_STR);
            $ps->bindValue(2, $id, PDO::PARAM_STR);
            $ps->execute();
            print json_encode(0);
        }
    }

    //欲しいものの設定
    function set_target_name($id, $tn){
        $pdo = $this->dbconnect();
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $ps = $pdo->prepare($sql);
        $ps->bindValue(1, $id, PDO::PARAM_STR);
        $ps->execute();
        $record = $ps->fetch();

        if(strcmp($record['id'], $record) == 0){
            print json_encode(1);
        }else{
            $sql = 'UPDATE users SET targetName = ?; WHERE user_id = ?';
            $ps = $pdo->prepare($sql);
            $ps->bindValue(1, $tn, PDO::PARAM_STR);
            $ps->bindValue(2, $id, PDO::PARAM_STR);
            $ps->execute();
            print json_encode(0);
        }
    }
}
?>