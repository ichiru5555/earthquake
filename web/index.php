<?php
$time_start = microtime(true);
include_once(__DIR__.'/db.php');
$pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$passwd);
$sql = "SELECT * FROM contents WHERE NOT(observatory = 'null' or earthquake_intensity = 'null' or earthquake_intensity = '震度情報なし') ORDER BY `contents`.`Occurrence_time` DESC";
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
<p>地震発生時間は表示できません。</p>
<p>地震情報が複数表示があるかもしれません。</p>
<p>地震の情報は<a href="https://www.p2pquake.net/json_api_v2/">ここから</a>取得しています。</p>
<table border align="center">
<tr>
<th>発表時間</th>
<th>震度</th>
<th>都道府県</th>
<th>観測都道府県</th>
<th>マグニチュード</th>
<th>震源の深さ</th>
<th>津波</th>
</tr>
<?php
while($row = $result->fetch()){
    if($row['magnitude'] === null){
        $row['magnitude'] = 'NULL';
    }
    if($row['depth'] === null){
        $row['depth'] = 'NULL';
    }
    if($row['tsunami'] === null){
        $row['tsunami'] = 'NULL';
    }
    if($row['prefectures'] === null){
        $row['prefectures'] = 'NULL';
    }
    if($row['magnitude'] === '-1' or $row['depth'] === '-1Km' or $row['tsunami'] === '調査中'){
        continue;
    }
    echo '<tr>';
    echo '<th>'.$row['Occurrence_time'].'</th>';
    echo '<th>'.$row['earthquake_intensity'].'</th>';
    echo '<th>'.$row['prefectures'].'</th>';
    echo '<th>'.$row['observatory'].'</th>';
    echo '<th>'.$row['magnitude'].'</th>';
    if($row['depth'] === 'NULL'){
        echo '<th>'.$row['depth'].'</th>';
    }else{
        echo '<th>'.$row['depth'].'Km'.'</th>';
    }
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
