### Requirements

* PHP 7.1+
* PHP PDO
* Composer

### Installion

1. Install packages
```
composer install
```

2. DB connection 

`/config/db.php`

3. Execute `/var/init.sql` sql file (if not `sqlite` adapter)

4. Configuring Web Servers
nginx:

```
server {
    server_name mysite.test;

    root </path/to/project>/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        #fastcgi_pass unix:/run/php/php7.1-fpm.sock;
        try_files $uri =404;
    }
}
```
