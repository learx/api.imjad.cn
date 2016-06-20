<?php if ($_GET['aaa'] != 'bbb') header('HTTP/1.1 403 Forbidden'); ?>
<?php

function curl_get_contents($url) { 
    $curlHandle = curl_init(); 
    curl_setopt($curlHandle,CURLOPT_URL,$url); 
    curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($curlHandle,CURLOPT_TIMEOUT,5); 
    $result = curl_exec($curlHandle); 
    curl_close($curlHandle); 
    return $result; 
}

if(function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec') && function_exists('curl_close')) {
    $contents = 'curl_get_contents';
}else{
    $contents = 'file_get_contents';
}

stream_context_set_default(array('http' => array('method' => 'HEAD')));

$hitokototime = get_headers('http://hitokoto.api.bilibibi.me/hitokoto.json',1);
$hitokototime = strtotime($hitokototime['Last-Modified']);
$hitokotouidtime = get_headers('http://hitokoto.api.bilibibi.me/hitokotouid.json',1);
$hitokotouidtime = strtotime($hitokotouidtime['Last-Modified']);

if(isset($_POST['update'])){
    $hitokoto = call_user_func($contents,'http://hitokoto.api.bilibibi.me/hitokoto.json');
    $hitokoto = json_decode($hitokoto,1);
    $hitokotouid = call_user_func($contents,'http://hitokoto.api.bilibibi.me/hitokotouid.json');
    $hitokotouid = json_decode($hitokotouid,1);
    
    if(!$hitokoto || !$hitokotouid){
      die('数据获取失败');
    }
    
    foreach($hitokoto as &$value){
      foreach($value as $k => $v){
        $value[$k] = urldecode($v);
        //$value[$k] = iconv('UTF-8', 'GBK//IGNORE',urldecode($v)); //页面编码不是UTF-8时
      }
}

foreach($hitokotouid as &$value){
  foreach($value as $k => $v){
    $value[$k] = urldecode($v);
    //$value[$k] = iconv('UTF-8', 'GBK//IGNORE',urldecode($v)); //页面编码不是UTF-8时
  }
}

$hitokoto['time'] = $hitokototime;
$hitokotouid['time'] = $hitokotouidtime;

$hitokoto = '<?php return '.var_export($hitokoto,1).";\n";
file_put_contents('./cache_hitokoto.php',$hitokoto);

$hitokotouid = '<?php return '.var_export($hitokotouid,1).";\n";
file_put_contents('./cache_hitokotouid.php',$hitokotouid);
}

$cache = @include './cache_hitokoto.php';
$cacheuid = @include './cache_hitokotouid.php';
  
    echo 'hitokoto系统收录:';echo '<br>';
    echo '本地缓存时间:',$cache['time']?date('Y-m-d H:i:s',$cache['time']):'无缓存';echo '<br>';
    echo '条数:',$cache['num'];echo '<br>';
    echo '云端缓存时间:',date('Y-m-d H:i:s',$hitokototime);echo '<br>';
    echo ($cache['time']>=$hitokototime)?'<font color="#009900">无更新</font>':'<font color="#0000CC">有更新</font>';echo '<br><br>';
    
    echo 'hitokoto个人收录:';echo '<br>';
    echo '本地缓存时间:',$cache['time']?date('Y-m-d H:i:s',$cacheuid['time']):'无缓存';echo '<br>';
    echo '条数:',$cacheuid['num'];echo '<br>';
    echo '云端缓存时间:',date('Y-m-d H:i:s',$hitokototime);echo '<br>';
    echo ($cacheuid['time']>=$hitokotouidtime)?'<font color="#009900">无更新</font>':'<font color="#0000CC">有更新</font>';echo '<br><br>';
    
    echo '<form method="POST"><input type="submit" value="更新" name="update" /></form>';
?>