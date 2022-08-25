
//备份sql
vendor/bin/phinx-migrations generate



nginx 部署配置
```
# 至少需要一个 webman 节点，多个配置多行
upstream webman {
    # Webman HTTP Server 的 IP 及 端口
    server 127.0.0.1:8787;
}

server
{
    listen 18888;
    server_name 129.28.186.67;
    # 同域根目录前端代码部署,注意：
    location / {
      root /www/wwwroot/nyuwa_prod;
      try_files $uri $uri/ /index.html;
      index index.html;
    }
  
    # 支持自定义下划线参数通过header传输
    # underscores_in_headers on;
  
    # PHP 代码
    location /api/ {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        # 将协议架构转发到对应节点，如果使用非https请改为http
        proxy_set_header X-scheme http;
  
        # 执行代理访问真实服务器
        proxy_pass http://webman/;
    }
    
    #SSL-START SSL相关配置，请勿删除或修改下一行带注释的404规则
    #error_page 404/404.html;
    #SSL-END
    
    #ERROR-PAGE-START  错误页配置，可以注释、删除或修改
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    #include enable-php-00.conf;
    #PHP-INFO-END
    
    #REWRITE-START URL重写规则引用,修改后将导致面板设置的伪静态规则失效
    #include /www/server/panel/vhost/rewrite/129.28.186.67.conf;
    #REWRITE-END
    
    #禁止访问的文件或目录
    #location ~ ^/(\.user.ini|\.htaccess|\.git|\.svn|\.project|LICENSE|README.md)
    #{
    #    return 404;
    #}
    
    #一键申请SSL证书验证目录相关设置
    #location ~ \.well-known{
    #    allow all;
    #}
    
    #location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    #{
    #    expires      30d;
    #    error_log /dev/null;
    #    access_log /dev/null;
    #}
    
    #location ~ .*\.(js|css)?$
    #{
    #    expires      12h;
    #    error_log /dev/null;
    #    access_log /dev/null; 
    #}
    access_log  /www/wwwlogs/129.28.186.67.log;
    error_log  /www/wwwlogs/129.28.186.67.error.log;
}

```