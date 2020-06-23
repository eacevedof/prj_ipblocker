## PRJ_IPBLOCKER
- autor: Eduardo A. F. 
- email: eacevedof@hotmail.com

### IP Blocker
## ¿Por qué esta herramienta?
Cada vez tengo más proyectos web realizados en php. Mi proveedor de Hosting registra en logs todo lo que entra y sale en relación a estos proyectos. 
De vez en cuando le echo un vistazo.  Sucede que hace unos dos meses monte un **Wordpress** sobre un dominio que tiene cierto tráfico y vi que sus logs tenían un tamaño más grande de lo normal.
Empecé a comprobar peticiones y habian muchas que tenían que ver con **exploits** de wp.  Afortunadamente suelo tener el wp actualizado con lo cual los probé y daban **404** pero algun fallo en la última version si que había.  Concretamente con la API del wp. Ya metido en arena, decidí revisar mis otros sitios y fue la locura. **spam**, **pruebas de exploits** inyección SQL y peticiones en binario, bots que iban haciendo un fullscan de vulnerabilidad en todos mis sitios.  Conclusión, mal rollo. <br/>

Instalo algún plugin en los wp para bloqueo de ip (que hay unos cuantos) y los que no estaban con wp?, algun bundle en Symfony o Laravel... y al final como suelo ir por el camino más largo, decidí hacer esta mini librería. <br/>

Con lo cual tenía centralizada todas las peticiones de todos los dominios y según los ataques configuraría unas reglas y otras.  Así, si una IP ha atacado un dominio se bloquea para todos.

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
use \TheFramework\Components\ComponentIpblocker;
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

