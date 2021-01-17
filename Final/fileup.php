<?php
//$_POSTが存在するか？（POST送信されていればnot Empty です）
if(!empty($_POST)) {
    //************************************************************************************
    // filter_inputとは？
    // DocumentURL=[http://php.net/manual/ja/function.filter-input.php]
    //************************************************************************************
    
    //1．POST値取得（POST数に合わせて増やす）
    $name = $_POST["name"];
    $lat  = $_POST["lat"];
    $lon  = $_POST["lon"];
    $comment = $_POST["comment"];

    //2. 未入力チェック
    if (!$lat ) {
        $error['lat'] = '緯度の値がありません';
    }
    if (!$lon ) {
        $error['lon'] = '緯度の値がありません';
    }

}else{
    echo "Error:1";
    exit();
}


//************************************************************************************
// FileUpload
//************************************************************************************
if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
   $file_name = $_FILES["upfile"]["name"];  //"1.jpg"ファイル名取得
   $tmp_path  = $_FILES["upfile"]["tmp_name"]; //"/usr/www/tmp/1.jpg"アップロード先のTempフォルダ
   $file_dir_path = "upload/";  //画像ファイル保管先

   $extension = pathinfo($file_name, PATHINFO_EXTENSION);
   $uniq_name = date("YmdHis").md5(session_id()) . "." . $extension;
   $file_name = $uniq_name;

   $img=""; 
   // FileUpload [--Start--]
   if ( is_uploaded_file( $tmp_path ) ) {
       if ( move_uploaded_file( $tmp_path, $file_dir_path . $file_name ) ) {
               chmod( $file_dir_path . $file_name, 0644 );
               //echo $file_name . "をアップロードしました。";
               $img = '<img src="'. $file_dir_path . $file_name . '" >';
       } else {
               $img = "Error:アップロードできませんでした。";
       }
   }
 // FileUpload [--End--]
 }else{
     $img = "画像が送信されていません";
 }




//************************************************************************************
// DB
//************************************************************************************
//１．DB接続
try {
    //dbname=gs_db
    //host=localhost
    //Password:MAMP='root', XAMPP=''
    $pdo = new PDO('mysql:dbname=map_db;charset=utf8;host=localhost','root','root');
} catch (PDOException $e) {
    exit('DBConnectError:'.$e->getMessage());
}


//３．SQL文作成 //*の箇所とテーブル名を変更！！
$sql = "INSERT INTO map_tables(name,lat,lon,img,comment,input_date)VALUES(:name,:lat,:lon,:img,:comment,sysdate())";
$stmt = $pdo->prepare($sql);

//４．SQL文の値へPOST値を渡す//*の箇所を変更！！
//（POST数に合わせて増やす）
$stmt->bindValue(":name", $name);
$stmt->bindValue(":lat", $lat);
$stmt->bindValue(":lon", $lon);
$stmt->bindValue(":comment", $comment);
$stmt->bindValue(":img", $file_name);

//5. SQL実行
$status = $stmt->execute();

//6. 画面遷移(select.php)
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}else{
    //何もしない
}
?>





<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kyoto Travel Map</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Kyoto Travel Map</title>
    <style>
body{width:100%;height:100%;margin:0;padding:0;color:#647f46;}
div{
    margin-left: 45px;
}
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
<body>
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

    
    <!-- 緯度・経度 -->
    <div>
        <?=$lat?>
        <?=$lon?>
    </div>

    <div>
        <?=$name?>
        <?=$comment?>
    </div>

    <!-- Upload画像 -->
    <div>
        <?=$img?>
    </div>
    
<script src="js/jquery-2.1.3.min.js"></script>
<script>
    
 
</script>
</body>
</html>
<footer>
    <p>&copy;Kyoto Travel Map.2021.</p>
</footer>