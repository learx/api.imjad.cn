<?php
$hitokoto = @include './cache_hitokoto.php';
$hitokotouid = @include './cache_hitokotouid.php';
$c = mt_rand(0,1);//从系统收录和随机收录中随机选择

if($c){
    $max = $hitokotouid['num'];
    $num = mt_rand(0,$max);
    $hiko = $hitokotouid[$num];
  }else{
    $max = $hitokoto['num'];
    $num = mt_rand(0,$max);
    $hiko = $hitokoto[$num];
  }

$out = '{"hitokoto":"'.$hiko['text'].'","author":"'.$hiko['author'].'","source":"'.$hiko['source'].'","date":"'.$hiko['date'].'","catname":"'.$hiko['catname'].'","id":'.$hiko['id'].'}';
# Results...
if ($_GET["encode"]==='js'){
        if (isset($_GET["name"])){
            header('Content-Type: application/javascript');
            echo 'function '.$_GET["name"].'(){document.write("'.$hiko['text'].'");}';
        } else if (isset($_GET["name"])){
            header('Content-Type: application/javascript');
            echo $_GET["name"]."(".$out.");";
        } else {
        echo $hiko['text'];
        }
}  else if (isset($_GET["name"])){
            header('Content-Type: application/javascript');
            echo $_GET["name"]."(".$out.");";
        } else {
        header('Content-Type: text/html; charset=utf-8');
        echo $hiko['text'];
        }
?>