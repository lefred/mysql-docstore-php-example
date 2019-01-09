# mysql-docstore-php-example
Some fun with PHP and MySQL Document Store

How to start:

```
$ sudo dnf install protobuf-devel
$ sudo pecl install mysql_xdevapi
$ echo "extension=mysql_xdevapi.so" > /etc/php.d/40-mysql_xdevapi.ini
```

You can use the PHP embedded server:

```
# php -S localhost:8000
```

In case you use it on localhost like explained above, in Firefox, you need to change the following confg (`about:config`) to *false* : **security.csp.enable**
