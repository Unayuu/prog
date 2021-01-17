<?php
//message save.
define( 'FILENAME', './message.txt');

date_default_timezone_set('Asia/Tokyo');

$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;

if( !empty($_POST['btn_submit']) ) {
    if( $file_handle = fopen( FILENAME, "a") ) {
        $now_date = date("Y-m-d H:i:s");
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
		fwrite( $file_handle, $data);
        fclose( $file_handle);
        $success_message = 'メッセージを書き込みました。';
	}		
}

if( $file_handle = fopen( FILENAME,'r') ) {
    while( $data = fgets($file_handle) ){
        $split_data = preg_split( '/\'/', $data);

        $message = array(
            'view_name' => $split_data[1],
            'message' => $split_data[3],
            'post_date' => $split_data[5]
        );
        array_unshift( $message_array, $message);
    }

    // ファイルを閉じる
    fclose( $file_handle);
    
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<title>Kyoto Travel Map</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<meta http-equiv="content-type" charset="utf-8">
<link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(window).load(function() {
		$('#drawer-navigation').fadeIn(0); 
    });
   $(function() {
      const hum = $('#hamburger, .close')
      const nav = $('.sp-nav')
      hum.on('click', function(){
         nav.toggleClass('toggle');
      });
   });
   </script>
</head>

<!-- header[START] -->
<header>
<body>
<h1><a href="index.html">Kyoto Travel Map</a></h1>
<nav class="pc-nav">
    <ul>
        <li><a href="otsumap_jf.html">Japanese</a></li>
        <li><a href="otsumap_cafe.html">Sweets</a></li>
        <li><a href="otsumap_other.html">Other</a></li>
        <li><a href="otsumap_post.html">Post</a></li>
        <li><a href="index.php"><span style="color:#647f46">Chat</span></a></li>
    </ul>
</nav>
<nav class="sp-nav">
    <ul>
        <li><a href="index.html">Top</a></li>
        <li><a href="otsumap_jf.html">Japanese</a></li>
        <li><a href="otsumap_cafe.html">Sweets</a></li>
        <li><a href="otsumap_other.html">Other</a></li>
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
<style>

/*------------------------------
 Reset Style
 
------------------------------*/
*{
  box-sizing: border-box;
}
#drawer-navigation{ 
	display:none;
}
body{
  margin: 0;
  padding: 0;
  font-family: 'Abril Fatface'; 
  background-color: #bdbf92;
  color: aliceblue;
}
header{
  width:100%;
  padding: 30px 4% 10px;
  background-color: #bdbf92;
  top: 0;
  display: flex;
  align-items: center;
}
h1{
  margin: 0;
  font-size: 20px;
}
a{
  text-decoration: none;
  color: aliceblue;
}
ul{
  list-style: none;
  margin: 0;
  display: flex;
}
li{
  margin: 0 0 0 15px;
  font-size: 14px;
}
nav {
  margin: 0 0 0 auto;
}
li :hover{
  color: #647f46;
}
.sp-nav{
  display: none;
}


@media screen and (max-width: 640px) {
  .pc-nav {
     display: none;
  }
  .sp-nav {
     z-index: 1;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100vh;
     display: block;
     width: 100%;
     background: rgba(0, 0, 0, .8);
     opacity: 0;
     transform: translateY(-100%);
     transition: all .2s ease-in-out;
  }
  #hamburger {
     position: relative;
     display: block;
     width: 30px;
     height: 25px;
     margin: 0 0 0 auto;
  }
  #hamburger span {
     position: absolute;
     top: 50%;
     left: 0;
     display: block;
     width: 100%;
     height: 2px;
     background-color: aliceblue;
     transform: translateY(-50%);
  }
  #hamburger::before {
     content: '';
     display: block;
     position: absolute;
     top: 0;
     left: 0;
     width: 100%;
     height: 2px;
     background-color: aliceblue;
  }
  #hamburger::after {
     content: '';
     display: block;
     position: absolute;
     bottom: 0;
     left: 0;
     width: 70%;
     height: 2px;
     background-color: aliceblue;
  }
  /*スマホメニュー*/
  .sp-nav ul {
     padding: 0;
     display: flex;
     flex-direction: column;
     justify-content: center;
     align-items: center;
     height: 100%;
  }
  .sp-nav li {
     margin: 0;
     padding: 0;
  }
  .sp-nav li span {
     font-size: 15px;
     color: aliceblue;
  }
  .sp-nav li a, .sp-nav li span {
     display: block;
     padding: 20px 0;
  }
  /*-閉じるアイコンー*/
  .sp-nav .close {
     position: relative;
     padding-left: 20px;
  }
  .sp-nav .close::before {
     content: '';
     position: absolute;
     top: 50%;
     left: 0;
     display: block;
     width: 16px;
     height: 1px;
     background: aliceblue;
     transform: rotate( 45deg );
  }
  .sp-nav .close::after {
     content: '';
     position: absolute;
     top: 50%;
     left: 0;
     display: block;
     width: 16px;
     height: 1px;
     background: aliceblue;
     transform: rotate( -45deg );
  }
  .toggle {
     transform: translateY( 0 );
     opacity: 1;
  }
}

footer{
  width: 100%;
  height: 100px;
  color: aliceblue;
  text-align: center;
  font-size: 10px;
}
.f-menu ul{
  padding: 0;
  display:flex;
  justify-content: center;
}



.main-visual {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60vh;
}
h2 {
  margin: 0;
  font-size: 60px;
  font-weight: normal;
  color: aliceblue;
}

p{
  margin: 10px;
  color: aliceblue;
}
p1{
  color: #647f46;
}
/*-----------------------------------
入力エリア
-----------------------------------*/

label {
    display: block;
    margin-bottom: 7px;
    margin-left: 40px;
    font-size: 86%;
}

input[type="text"],
textarea {
    margin-bottom: 20px;
    margin-left: 40px;
	padding: 10px;
	font-size: 86%;
    border: 1px solid #ddd;
    border-radius: 3px;
    background: #fff;
}

input[type="text"] {
    width: 200px;
    margin-left: 40px;
}
textarea {
	width: 50%;
    max-width: 50%;
    margin-left: 40px;
	height: 70px;
}
input[type="submit"] {
	appearance: none;
    -webkit-appearance: none;
    padding: 10px 20px;
    margin-left: 40px;
    color: #fff;
    font-size: 86%;
    line-height: 1.0em;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #87ab60;
}
input[type=submit]:hover,
button:hover {
    background-color: #647f46;
}

hr {
	margin: 20px 0;
	padding: 0;
}

.success_message {
    margin-bottom: 20px;
    padding: 10px;
    margin-right: 40px;
    margin-left: 40px;
    color: #fff;
    border-radius: 10px;
    border: 1px solid #fff;
}

.error_message {
    margin-bottom: 20px;
    padding: 10px;
    color: #ef072d;
    list-style-type: none;
    border-radius: 10px;
    border: 1px solid #ff5f79;
}

.success_message,
.error_message li {
    font-size: 86%;
    line-height: 1.6em;
}


/*-----------------------------------
掲示板エリア
-----------------------------------*/

article {
    margin-top: 20px;
    margin-left: 40px;
    margin-right: 40px;
    margin-bottom: 30px;
	padding: 20px;
	border-radius: 10px;
	background: #fff;
}
article.reply {
    position: relative;
    margin-top: 15px;
    margin-left: 30px;
}
article.reply::before {
    position: absolute;
    top: -10px;
    left: 20px;
    display: block;
    content: "";
    border-top: none;
    border-left: 7px solid #f7f7f7;
    border-right: 7px solid #f7f7f7;
    border-bottom: 10px solid #fff;
}
	.info {
		margin-bottom: 10px;
	}
	.info h2 {
		display: inline-block;
		margin-right: 10px;
		color: #222;
		line-height: 1.6em;
		font-size: 86%;
	}
	.info time {
		color: #999;
		line-height: 1.6em;
		font-size: 72%;
	}
    article p {
        color: #555;
        font-size: 86%;
        line-height: 1.6em;
    }

@media only screen and (max-width: 1000px) {

    body {
        padding: 30px 5%;
    }

    input[type="text"] {
        width: 100%;
    }
    textarea {
        width: 100%;
        max-width: 100%;
        height: 70px;
    }
}
</style>
</head>
<body>
<br>
<?php if( !empty($success_message) ): ?>
    <p class="success_message"><?php echo $success_message; ?></p>
<?php endif; ?>
<form method="post">
	<div>
		<label for="view_name">name</label>
		<input id="view_name" type="text" name="view_name" value="">
	</div>
	<div>
		<label for="message">message</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>
<section>
<?php if( !empty($message_array) ): ?>
<?php foreach( $message_array as $value ): ?>
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
    </div>
    <p><?php echo $value['message']; ?></p>
</article>
<?php endforeach; ?>
<?php endif; ?>
</section>
</body>
</html>

<!-- footer[START] -->
<footer>
    <div class="f-menu">
        <ul>
            <li><a href="index.html">Top</a></li>
            <li><a href="otsumap_jf.html">Japanese</a></li>
            <li><a href="otsumap_cafe.html">Sweets</a></li>
            <li><a href="otsumap_other.html">Other</a></li>
            <li><a href="otsumap_post.html">Post</a></li>
            <li><a href="index.php">Chat</a></li>
        </ul>
    </div>
    <p>&copy;Kyoto Travel Map.2021.</p>
</footer>
<!-- footer[END] -->