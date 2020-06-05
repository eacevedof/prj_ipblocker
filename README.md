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
- [ipgeolocation](https://ipgeolocation.io/ip-location/34.241.107.14)
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
  AND post != ''
  AND remote_ip IN
  (
    SELECT remote_ip
    FROM app_ip_request 
    WHERE 1 
    AND remote_ip NOT IN 
    (
      SELECT remote_ip 
      FROM app_ip_blacklist
    )
  )
  AND remote_ip IN
  (
    SELECT DISTINCT remote_ip FROM app_ip_request WHERE post LIKE '%.ru%'
  )  
  GROUP BY remote_ip
);

-- sospechosos por get
INSERT INTO app_ip_blacklist (remote_ip, reason)

SELECT remote_ip, substring(`get`,1,120) g
FROM app_ip_request 
WHERE 1
AND insert_date>=curdate()
AND id IN
(
  SELECT MAX(id) mid
  FROM app_ip_request
  WHERE 1
  AND `get` != ''
  AND remote_ip IN
  (
    SELECT remote_ip
    FROM app_ip_request 
    WHERE 1 
    AND remote_ip NOT IN 
    (
      SELECT remote_ip 
      FROM app_ip_blacklist
    )
  )
  AND remote_ip IN
  (
    SELECT DISTINCT remote_ip FROM app_ip_request WHERE `get` LIKE '%.ru%'
  )
  GROUP BY remote_ip
);

-- comprueba q tipo de peticiones ha hecho una determinada ip
SELECT id, remote_ip, insert_date, domain
, substring(request_uri,1,100) r
, substring(`get`,1,100) g
, substring(`post`,1,100) p

FROM app_ip_request 
WHERE 1
-- AND post like '%.ru%'
-- AND request_uri LIKE '%?%'
AND remote_ip NOT IN 
(
  SELECT remote_ip FROM app_ip_blacklist

  UNION
 
  -- los que estan inventariados
  SELECT remote_ip FROM app_ip WHERE whois IS NOT NULL
)
AND insert_date>=curdate()
ORDER BY remote_ip,id DESC
LIMIT 500;


-- ips de google
SELECT DISTINCT remote_ip FROM app_ip_request WHERE request_uri LIKE '%th1s_1s_a_4o4%';

-- numero de accesos por ip
SELECT r.remote_ip, domain
, count(r.id) ireq
, CASE WHEN b.id IS NULL THEN '' ELSE 'bl' END AS blid
, CONCAT(COALESCE(i.whois,''),' - ',COALESCE(i.country,'')) who
FROM app_ip_request r
LEFT JOIN app_ip_blacklist b
ON r.remote_ip = b.remote_ip
LEFT JOIN app_ip i
ON r.remote_ip = i.remote_ip
WHERE 1
AND r.remote_ip NOT IN
(
  SELECT remote_ip FROM app_ip_request WHERE request_uri LIKE '%th1s_1s_a_4o4%'
  UNION 
  SELECT remote_ip FROM app_ip WHERE whois LIKE '%google%'
)
-- AND  r.insert_date>=curdate()
AND  r.insert_date < curdate()
GROUP BY r.remote_ip, domain
HAVING count(r.id)>1
ORDER BY domain ASC,ireq DESC;
-- =============================================
-- =============================================

SELECT * FROM app_ip_request WHERE remote_ip='45.86.203.42';

INSERT INTO app_ip_blacklist (remote_ip, reason) 
values('45.86.203.42','pais:DE, fuente:ip manual, accion: gracestyle, https://flipme.link/957bDy spam');

-- INSERT INTO app_ip_blacklist (remote_ip,reason)

SELECT remote_ip, 'https:// spam'
-- ,post
-- ,insert_date 
FROM app_ip_request WHERE post LIKE '%https://%' AND remote_ip NOT IN (SELECT remote_ip FROM app_ip_blacklist); 

UPDATE app_ip
SET 
  country='RU'
  ,whois='KINGISEPP-ONLINE Ltd.'
WHERE remote_ip IN ('31.132.211.144')
AND whois is null;
```

### To-do:
- Warning messages a partir de app_words