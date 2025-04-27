<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

use function Symfony\Component\VarDumper\Dumper\esc;

require __DIR__ . '/../vendor/autoload.php';


// 指定IP开启调试
if ($_SERVER['REMOTE_ADDR'] == '12.12.12.120'){
    $http = (new App())->setEnvName('develop')->http;
}else{
    $http = (new App())->http;
}


$response = $http->run();

$response->send();

$http->end($response);
