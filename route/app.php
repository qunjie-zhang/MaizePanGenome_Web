<?php

use think\facade\Route;
use think\facade\Config;
use think\facade\Env;

Route::rule('/','index/index')->middleware(\app\middleware\CheckLogin::class);

Route::group('haplotype',function (){
    Route::rule('createTask','haplotype.CreateTask/index');
    Route::rule('action','haplotype.CreateTask/action');
    Route::rule('datasetGetList','haplotype.CreateTask/datasetGetList');
    Route::rule('datasetGetParamById','haplotype.CreateTask/datasetGetParamById');
    Route::rule('getGeneInfo','haplotype.CreateTask/getGeneInfo');
    Route::rule('getRangeFile','haplotype.CreateTask/getRangeFile');
})->middleware(\app\middleware\CheckLogin::class);

// JOB路由分组
Route::group('job',function(){
    Route::rule('index','job/index');
    Route::rule('getJobList','job/getJobList');
    Route::rule('delete','job/delete');
    Route::rule('view','job/view');
    Route::rule('redo','job/redo');
})->middleware(\app\middleware\CheckLogin::class);


Route::group('download',function (){
    Route::rule('index','download.Index/index');
    Route::rule('expression_species','download.Index/expression_species');
    Route::rule('expression_gene','download.Index/expression_gene');
    Route::rule('group','download.Index/group');
})->middleware(\app\middleware\CheckLogin::class);


// 用户路由分组
Route::group('user',function(){
    Route::rule('login','user/login');
    Route::rule('logout','user/logout');
    Route::rule('verify','user/verify');
    Route::rule('status','user/status');
    Route::rule('useradd','user/useradd');
});


// 公共 API 接口
Route::group('api',function (){
    // 用于计算端访问的接口 无需登录
    Route::group('job',function (){
        Route::rule('updateInfo','job/updateInfo');
        Route::rule('getOneJob','job/getOneJob');
    });

    // Haplotype 模块数据操作接口
    Route::group('haplotype',function (){
        Route::rule('datasetAdd','haplotype.Api/datasetAdd');
        Route::rule('geneAdd','haplotype.Api/geneAdd');
    });

    // 表达量数据操作接口
    Route::group('expression',function (){
        Route::rule('add','expression.Api/add');
        Route::rule('delete','expression.Api/delete');
        Route::rule('test','expression.Api/test');
    });

    Route::group('group',function (){
        Route::rule('add','group.Api/add');
        Route::rule('delete','group.Api/delete');
        Route::rule('test','group.Api/test');
    });

});

// 非调试模式下启用错误页面
if (!app()->isDebug()){
    Route::miss('error/E404');
}
