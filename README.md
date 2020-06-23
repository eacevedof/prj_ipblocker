## PRJ_IPBLOCKER
- autor: Eduardo A. F. 
- email: eacevedof@hotmail.com

### IP Blocker
## ¿Qué es?
Es una mini librería **<100K** realizada en **php** y gestonada con **Vue**.
Su objetivo principal es la gestión de peticiones **POST, GET y FILES** realizadas sobre nuestros distintos dominios.
Como es de esperar estos dominios deben de tener una **webapp** realizada en php y de ser posible contar con un *frontcontroller*
ya que al ser este el único punto de entrada hace más sencilla su configuración

Yo concretamente lo tengo configurado en sitios con: Symfony y Laravel pero se puede aplicar en Yii, Cakephp y otros frameworks similares.
Las webs con wordpress también se pueden proteger.

## El backend
Son un conjunto de archivos php (v7.4.1) que están *bundelizados* en un único archivo **`public/ipblocker.php`** 
```php
//public/ipblocker.php
$pathboot = realpath(__DIR__."/../boot");
include("$pathboot/appbootstrap.php");
use \TheFramework\Components\ComponentIpblocker;<br/>
(new ComponentIpblocker())->handle_request();
```
Este (ipblocker.php) se incuirá en el **frontcontroller** en sus primeras lineas. Algo así:
```php
//ejemplo frontcontroller de symfony 5
<?php
//public/index.php
$time_start = microtime(true);
if(is_file("<ruta-a-la-carpeta-ipblocker-php>/public/ipblocker.php"))
  include("<ruta-a-la-carpeta-ipblocker-php>/public/ipblocker.php");

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
...
```
### ¿Qué se consigue con esta inyección?
- Cuando ocurre una petición HTTP/S hacia el dominio ocurren varias cosas:
  - Se registra en bd el **POST, GET y FILES** en las tablas: **app_ip y app_ip_request**




## recursos

### db
- [mysql](https://github.com/eacevedof/prj_ipblocker/tree/master/db)

### library
- [php - ipblocker](https://github.com/eacevedof/prj_ipblocker/tree/master/php)

### frontend
- [php apifyer](https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify)
- [vue & nextjs](https://github.com/eacevedof/prj_ipblocker/tree/master/vuejs)

### deploy (python)
- [py.sh - help](https://github.com/eacevedof/prj_bash/blob/master/py/help.py)
```js
// db
py.sh dump ipblocker
py.sh deploy.dbrestore ipblocker

// vue
py.sh deploy.frontbuild ipblocker
```

