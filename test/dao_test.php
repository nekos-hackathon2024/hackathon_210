<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>title</title>
</head>

<body>
    <?php
    chdir( '..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'dao' . DIRECTORY_SEPARATOR );

    require_once 'DAO.php';
    $dao = new setuyaku_DAO();
    $pdo = $dao->dbconnect();
    $sql = 'SHOW TABLES';
    $result = $pdo->query($sql);
    echo "テーブル一覧:<br>";

    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "<br>";
    }

    ?>
</body>

</html>