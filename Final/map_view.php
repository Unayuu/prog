<?php
 //**********************************************************
 // *  fileupload2.php
 // *  FileList（画像ファイル一覧表示）
 //**********************************************************
//１．DB接続
try {
    //dbname=gs_db
    //host=localhost
    //Password:MAMP='root', XAMPP=''
    $pdo = new PDO('mysql:dbname=map_db;charset=utf8;host=localhost','root','root');
} catch (PDOException $e) {
    exit('DB_Connect Error:'.$e->getMessage()); //DB接続：Error表示:サニタイジング(無効化)
}

//SELECT文を作る
$sort  = "input_date"; //SQL:SORT用
$sql = "SELECT * FROM map_tables ORDER BY :sort DESC"; //SQL文
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":sort",  $sort);//SQL:SORT用
$status = $stmt->execute();//実行

//JS用 配列文字列を作成
$img=""; //画像名の配列文字列
$lat=""; //lat:緯度の配列数値
$lon=""; //lon:経度の配列数値
//let a = ['a.lpg','b.jpg','c.jpg'];

$i=0;
if($status==false){
    //SQLエラーの文字列を作成   
    $view = "SQLError"; //SQLエラーの文字列を作成
}else{    
    //配列のインデックスで使用する変数iを作成

    //取得したレコード数ループでデータ取得
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    
        if($i==0){
            //1回目のみ実行
            $img .= '"'.$row["img"].'"';
            $lat .= $row["lat"];
            $lon .= $row["lon"];
            $name .= '"'.$row["name"].'"';
            $comment .= '"'.$row["comment"].'"';
        }else{
            //2回目以降はこちら（2回目から先頭にカンマを付与）
            $img .= ',"'.$row["img"].'"';
            $lat .= ",".$row["lat"];
            $lon .= ",".$row["lon"];
            $name .= ',"'.$row["name"].'"';
            $comment .= ',"'.$row["comment"].'"';
        }
        //$iをインクリメント
        $i++;

    }
    // echo $lon;
    // exit;
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<title>Kyoto Travel Map</title>
<style>
body{width:100%;height:100%;margin:0;padding:0;color:#647f46;}
#photarea{padding:5%;width:100%;background:black;}
img{height:100px;border-radius: 10px;}
#select_btn{
	appearance: none;
    -webkit-appearance: none;
    text-decoration: none;
    padding: 10px 20px;
    margin-left: 0px;
    color: #fff;
    font-size: 86%;
    line-height: 1.0em;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #87ab60;
}
#select_btn:hover {
    background-color: #647f46;
}
h1{
            font-family: 'Abril Fatface';
        }
</style>
</head>
<body id="main">

<!-- header[START] -->
<header>
    <body>
    <h1><a href="index.html">Kyoto Travel Map</a></h1>　<p2><a href="otsumap_post.html" id="select_btn">←POST</a></p2>
</h1>
    <nav class="pc-nav">
        <ul>
            <li><a href="otsumap_jf.html">Japanese</a></li>
            <li><a href="otsumap_cafe.html"><span style="color:#647f46">Sweets</span></a></li>
            <li><a href="otsumap_other.html">Other</a></li>
            <li><a href="otsumap_htu.html">How to</a></li>
            <li><a href="otsumap_post.html">Post</a></li>
            <li><a href="index.php">Chat</a></li>
        </ul>
    </nav>
    <nav class="sp-nav">
        <ul>
            <li><a href="index.html">Top</a></li>
            <li><a href="otsumap_jf.html">Japanese</a></li>
            <li><a href="otsumap_cafe.html">Sweets</a></li>
            <li><a href="otsumap_other.html">Other</a></li>
            <li><a href="otsumap_htu.html">How to</a></li>
            <li><a href="otsumap_post.html">Post</a></li>
            <li><a href="index.php">Chat</a></li>
            <li class="close"><span>Close</span></li>
        </ul>
    </nav>
    <div id="hamburger">
        <span></span>
    </div>
    </header>
    <!-- header[END] -->

<!-- IMG_LIST[Start] -->
<div id="myMap" style="width:100%;height:500px;"></div>
<!-- IMG_LIST[END] -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AlWrsxZq0AWU7KFEPiNZRY-Roa_DFsN8dLfZKrGKcYZuAP_cXX6mrC8-06NLcb64' async defer></script>
<script src="js/BmapQuery.js"></script>
<script>
//1.配列
let img  = [<?=$img?>];
let lat  = [<?=$lat?>];
let lon  = [<?=$lon?>];
let name = [<?=$name?>];
let comment = [<?=$comment?>];

//2.BingMapライブラリを読み込んだらGetmap関数を実行！

//* MapObjectをグローバルで保持
let map;
//* MapZoom値
let zoom = 12;

function GetMap(){
    //BingMapスタート
    map = new Bmap("#myMap");
    map.startMap(35.003674,135.76059, "load", 14); //The place is Bellevue.

    // pin&InfoBoxを挿入
    //* 配列の長さを取得
    let len = lat.length
    //* forループで配列の数だけ処理をする
    for(let i=0; i<len; i++){
        //* 最初にpin,次にinfoboxHtml
        map.pin(lat[i], lon[i],"#647f46");
        let h ='<div>'+comment[i]+'<br>'+name[i]+'<br><img src="upload/'+img[i]+'"></div>';
        map.infoboxHtml(lat[i],lon[i],h);
    }
    //* map.changeMapを使って最後の座標を中心に表示する！
    // map.changeMap(lat[len-1],lon[len-1],"load",zoom);
}
</script>
</body>
</html>

<footer>
    <p>&copy;Kyoto Travel Map.2021.</p>
</footer>
<!-- footer[END] -->


