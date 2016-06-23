<?php
//文件式计数器，读取counter.txt的值 
function get_hit($counter_file){
    $count=0;
    if(file_exists($counter_file)){
        $fp=fopen($counter_file,"r");
        $count=0+fgets($fp,20);
        fclose($fp);
    }
    return($count);
}
$hit=get_hit("counter.txt");

//输出JSON
header('Content-Type: application/json;charset=utf-8');
$cache = @include './cache_hitokoto.php';
$cacheuid = @include './cache_hitokotouid.php';

$out = '{
"hit":"'.$hit.'",
"index":"'.date("Y.m.d",filemtime("index.html")).'",
"api":"'.date("Y.m.d",filemtime("index.php")).'",
"data":"'.date('Y.m.d',$cache['time']).'",
"number":"'.($cache['num'] + 1).'",
"data_uid":"'.date('Y.m.d',$cacheuid['time']).'",
"number_uid":"'.($cacheuid['num'] + 1).'"
}';
echo $out;
?>