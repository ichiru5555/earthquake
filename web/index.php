<?php
$database_name = "";
$host = "";
$user = "";
$passwd = "";
$time_start = microtime(true);

$pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';charset=utf8mb4',$user,$passwd);
$sql = "SELECT * FROM earthquake ORDER BY `earthquake`.`time` DESC";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>地震</title>
</head>
<style>
    html{
        margin: auto;
        text-align: center;
    }
</style>
<body>
<p>地震の情報は<a href="https://www.p2pquake.net/json_api_v2/">ここから</a>取得しています。</p>
<table border align="center">
<tr>
<th>発生時間</th>
<th>震度</th>
<th>発生場所</th>
<th>マグニチュード</th>
<th>震源の深さ</th>
<th>津波</th>
</tr>
<?php
while($row = $result->fetch()) {
    echo '<tr>';
    echo '<th>'.$row['time'].'</th>';
    echo '<th>'.$row['intensity'].'</th>';
    echo '<th>'.$row['location'].'</th>';
    echo '<th>'.$row['magnitude'].'</th>';
    echo '<th>'.$row['depth'].'</th>';
    echo '<th>'.$row['tsunami'].'</th>';
    echo '</tr>';
}
$pdo = null;
$time = microtime(true) - $time_start;
?>
</table>
<p>処理時間: <?php echo $time; ?>秒</p>
</body>
</html>
