<?php

// 引入自动载入文件并注册自动载入函数
require('../src/loader.php'); 
spl_autoload_register('\\Load\\Loader::autoload');

//Create an instance
$HttpClient = new \Leaps\HttpClient\Adapter\Curl();
$response = $HttpClient->get('http://www.baidu.com/');
echo $response->getContent();
