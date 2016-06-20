<?php
$hitokoto = @include './cache_hitokoto.php';
$hitokotouid = @include './cache_hitokotouid.php';
$c = mt_rand(0,1);//从系统收录和随机收录中随机选择
//判断来源
if ($_GET['source']=='0') $c = 0;
if ($_GET['source']=='1') $c = 1;

if($c){
    $max = $hitokotouid['num'];
    $num = mt_rand(0,$max);
    $content = $hitokotouid[$num];
  }else{
    $max = $hitokoto['num'];
    $num = mt_rand(0,$max);
    $content = $hitokoto[$num];
  }

//判断输出编码
$charset = 'utf-8';
if ($_GET['charset']=='gbk'){
    $charset = 'gbk';
}
//判断输出长度
$text=$content['text'];
$text_length = mb_strlen($text,'utf8');
if (ctype_digit($_GET['length']) && $_GET['length'] < $text_length){
    $text = mb_substr ($text, 0 , $_GET['length'],'utf-8').'…';
}
//判断输出格式
if (!empty($_GET['encode'])){
    switch ($_GET['encode']) {
        case 'json':
            //{"hitokoto":"我一直以为人是慢慢变老的，其实不是，人是一瞬间变老的。","author":"優しさの猫","source":"《舞！舞！舞！》","like":10,"date":"2014.07.23 16:41:45","catname":"Novel - 小说","id":1406104905880}
            header('Content-Type: application/json;charset='.$charset);
            if ($_GET['iframe']=='true'){
                header('Content-Type: text/plain;charset='.$charset);
            }
            $out = '{"hitokoto":"'.$text.'","author":"'.$content['author'].'","source":"'.$content['source'].'","date":"'.$content['date'].'","catname":"'.$content['catname'].'","id":'.$content['id'].'}';
            if ($charset=='gbk'){
                $out = iconv("UTF-8", "GBK", $out);
            }
            echo $out;
            break;
        
        case 'js':
            //function hitokoto(){document.write("<span class='hitokoto' title='分类：收藏 出自：原创 投稿：走在时空里不回来 @ 2015.08.27 23:43:07'>梦+想=梦想，这个等式是不成立的。</span>");}
            header('Content-Type: text/javascript;charset='.$charset);
            if ($_GET['iframe']=='true'){
                header('Content-Type: text/plain;charset='.$charset);
            }
            $out = 'function hitokoto(){document.write("<span class=\'hitokoto\' title=\'分类：'.$content['catname'].' 出自：'.$content['source'].' 投稿：'.$content['author'].' @ '.$content['date'].'\'>'.$text.'</span>");}';
            if ($charset=='gbk'){
                $out = iconv("UTF-8", "GBK", $out);
            }
            echo $out;
            break;
        
        case 'jsc':
            //fun({"hitokoto":"明明只是活着，哀伤却无处不在⋯⋯","author":"wyxoi","source":"秒速五厘米","date":"2016.03.15 21:09:51","catname":"收藏","id":4025/7169});
            header('Content-Type: text/javascript;charset='.$charset);
            if ($_GET['iframe']=='true'){
                header('Content-Type: text/plain;charset='.$charset);
            }
            if (!empty($_GET['fun'])){
                $fun = $_GET[fun];
            } else {
                $fun = "hitokoto";
            }
            $out = $fun.'({"hitokoto":"'.$text.'","author":"'.$content['author'].'","source":"'.$content['source'].'","date":"'.$content['date'].'","catname":"'.$content['catname'].'","id":'.$content['id'].'});';
            if ($charset=='gbk'){
                $out = iconv("UTF-8", "GBK", $out);
            }
            echo $out;
            break;

        default:
            //{"hitokoto":"我一直以为人是慢慢变老的，其实不是，人是一瞬间变老的。","author":"優しさの猫","source":"《舞！舞！舞！》","like":10,"date":"2014.07.23 16:41:45","catname":"Novel - 小说","id":1406104905880}
            header('Content-Type: application/json;charset='.$charset);
            if ($_GET['iframe']=='true'){
                header('Content-Type: text/plain;charset='.$charset);
            }
            $out = '{"hitokoto":"'.$text.'","author":"'.$content['author'].'","source":"'.$content['source'].'","date":"'.$content['date'].'","catname":"'.$content['catname'].'","id":'.$content['id'].'}';
            if ($charset=='gbk'){
                $out = iconv("UTF-8", "GBK", $out);
            }
            echo $out;
            break;
    }
} else {//默认输出文本
    header('Content-Type: text/plain;charset='.$charset);
    $out = $text;
    if ($charset=='gbk'){
        $out = iconv("UTF-8", "GBK", $out);
    }
    echo $out;
}
//文件式计数器
$count = 0;
$counter_file = "counter.txt";
if(file_exists($counter_file)){
    $fp=fopen($counter_file,"r");
    $count=0+fgets($fp,20);
    fclose($fp);
}
$count++;
$fp=fopen($counter_file,"w");
fputs($fp,$count);
fclose($fp);
?>