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

### Sql check:
```sql

-- ips con su contenido
SELECT remote_ip,post 
FROM
(
  SELECT remote_ip, substring(post,1,175) post 
  FROM app_ip_request 
  WHERE 1 
  AND post!=''
  AND remote_ip NOT IN 
  (
    SELECT remote_ip FROM app_ip_blacklist
  )
) x 
order by remote_ip asc;

-- guardo ips sospechosas
INSERT INTO app_ip_blacklist (remote_ip, reason)

SELECT remote_ip, substring(`post`,1,120) p
FROM app_ip_request 
WHERE 1
AND id IN
(
  SELECT MAX(id) mid
  FROM app_ip_request
  WHERE 1
  AND `post`!=''
  AND remote_ip IN
  (
    SELECT remote_ip
    FROM app_ip_request 
    WHERE 1 
    AND `post`!=''
    AND remote_ip NOT IN 
    (
      SELECT remote_ip 
      FROM app_ip_blacklist
    )
  )
  GROUP BY remote_ip
)

-- sospechosos por get
INSERT INTO app_ip_blacklist (remote_ip, reason)

SELECT remote_ip, substring(`get`,1,120) g
FROM app_ip_request 
WHERE 1
AND id IN
(
  SELECT MAX(id) mid
  FROM app_ip_request
  WHERE 1
  AND `get` LIKE '%email%'
  AND remote_ip IN
  (
    SELECT remote_ip
    FROM app_ip_request 
    WHERE 1 
    AND `get` LIKE '%email%'
    AND remote_ip NOT IN 
    (
      SELECT remote_ip 
      FROM app_ip_blacklist
    )
  )
  GROUP BY remote_ip
)

-- numero de accesos por ip
SELECT remote_ip, domain, count(id) ireq
FROM app_ip_request
WHERE 1
GROUP BY remote_ip, domain
ORDER BY ireq DESC;

INSERT INTO app_ip_blacklist (remote_ip, reason) 
values('152.32.104.0','pais:ph, fuente: ionos log, accion: intenta acceder a eduardoaf.com/wp-login');
```

### To-do:
- Warning messages a partir de app_words