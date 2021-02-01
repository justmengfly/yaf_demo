yaf框架目录组织

1. php.ini 的配置如下
```
[yaf]
extension=yaf.so
yaf.environ=dev
yaf.use_namespace=1
yaf.use_spl_autoload=1
```
2. 配置 Nginx 的 Rewrite
3. composer update
4. 浏览器访问 http://your_domain/test/test/index
5. 命令行执行 php cli.php test index "a=1&b=2"