# php_ipblocker
Gestion de bloqueo de ips en php  

php -S localhost:3100 -t public

### db:
```sql
TRUNCATE TABLE app_ip;
TRUNCATE TABLE app_ip_blacklist;
TRUNCATE TABLE app_ip_request;
```
```s
py.sh dump ipblocker
py.sh deploy.dbrestore ipblocker
```
- [prod mysql console](https://trello.com/c/G9OArwWO/9-ionos-conectar-por-consola)
### php
- [prod include](https://trello.com/c/5qbASalI/10-ipblocker)
```php
if(is_file("~/mi_common/prj_ipblocker/php/public/ipblocker.php"))
    include("~/mi_common/prj_ipblocker/php/public/ipblocker.php");
```