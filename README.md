# 文档

如果你没有定义schema属性的话，可以在部署完成后运行如下指令。
```shell
php think optimize:schema
```

数据表中status定义
```
0 => '未开始',3
1 => '正在进行',
2 => '已完成',

```

输出文件放置在
/public/result/{md5(job_id)}/




定义一个数据集规范
在数据集的根路径放置 dataset.json, 内容包含该数据分析模块所需资源路径.
资源路径写基于当前json文件的相对路径

```json
{
    "chr_vcf": "m350_new",
    "gff": "Zea_mays.Zm-B73-REFERENCE-NAM-5.0.57.gff3",
    "acc": "acc.csv",
    "pheo": "pheo.csv"
}
```
cres 顺势元件调控搜索

```json
{
  "data":"B73.fimo.marked.tsv"
}

```



宝塔面板部署时需删除 配置文件 中的有关错误页面的配置。错误页面会影响调试输出。
```
location ~ ^/result/[^/]+/$ {
    autoindex on;
}
```


为应对应用程序对外发布时在二级目录运行，需对生成URL进行指定域名处理
1. 在配置文件 app.php 中配置 app_host (自动从env中获取对应配置，择一配置即可)
2. HTML生成URL: {:url('index/index')->domain(config('app.app_host'))->build()}
3. PHP生成URL： $url = Route::buildUrl('job/getJobList')->domain(config('app.app_host'))->build();

所有的html静态资源部分添加  {:config('app.app_host')} 即可
如：<script src="{:config('app.app_host')}/assets/js/template.js"></script>


// 线上调试，当 cookie 的  app_debug 为 true 时开启调试模式 
if (isset($_COOKIE['app_debug']) and $_COOKIE['app_debug'] == true){
    $http = (new App())->debug()->http;
}else{
    $http = (new App())->http;
}