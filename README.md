## PRJ_IPBLOCKER
- autor: Eduardo A. F. 
- email: eacevedof@hotmail.com

### IP Blocker
## ¿Por qué esta herramienta?
Cada vez tengo más proyectos web realizados en php. Mi proveedor de Hosting registra en logs todo lo que entra y sale en relación a estos proyectos. <br/>
De vez en cuando le echo un vistazo.  Sucede que hace unos dos meses (hoy: 23 Jun 2020) monté un **Wordpress** sobre un dominio que tiene cierto tráfico y vi que sus logs tenían un tamaño más grande de lo normal.<br/><br/>

Empecé a comprobar peticiones y habian muchas que tenían que ver con **exploits** de wp.  Afortunadamente suelo tener el wp actualizado con lo cual los probé y daban **404** pero algún fallo en la última version si que había.  Concretamente con la API del wp. <br/>
Ya metido en arena, decidí revisar mis otros sitios y fue la locura. **spam**, **pruebas de exploits** inyección SQL y peticiones en binario, bots que iban haciendo un fullscan de vulnerabilidad en todos mis sitios.  Conclusión, mal rollo. <br/><br/>

Instalo algún plugin en los wp para bloqueo de ip (que hay unos cuantos) y los que no estaban con wp?, algun bundle en Symfony o Laravel... pero pensar en que tengo que gestionar el bloqueo en cada uno por separado no me agradaba mucho la idea. <br/> 
Al final la cabra tira al monte, decidí hacer esta mini librería. <br/><br/>

Esta era una solución más genérica.  Tenía centralizada todas las peticiones de todos los dominios y según los ataques configuraría unas reglas u otras.  
Así, si una IP ha atacado un dominio se bloquea para todos.

## ¿Qué es?
Es una mini librería **size < 100K** realizada en **php** y gestonada (de forma opcional) con **Vue**.
Su objetivo principal es el rastreo de peticiones **POST, GET y FILES** realizadas sobre nuestros distintos dominios.
Como es de esperar estos dominios deben de tener una **webapp** realizada en php y de ser posible contar con un *frontcontroller*
ya que al ser este el único punto de entrada hace más sencilla su configuración

Yo concretamente lo tengo configurado en sitios con: Symfony y Laravel pero se puede aplicar en Yii, Cakephp y otros frameworks similares.
Las webs con wordpress también se pueden proteger.

## El backend
Es un conjunto de archivos php (v7.4.1) que están *bundelizados* en un único fichero: **`public/ipblocker.php`** 
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
- Cuando ocurre una petición **HTTP | S** hacia el dominio ocurren varias cosas:
  - Se registra en bd el **POST, GET y FILES** en las tablas: **app_ip y app_ip_request** 
  - Primero en **app_ip** se guarda la ip (si no existiera), el país de origen y a quien pertenece
  - Si está en **app_ip_blacklist** directamente se aplica el exit con un mensaje: `ComponentIpblocker.pr()` con lo cual
  nos ahorramos la ejecución del resto del código
    - ![](https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/632x214/14ff372f5163fa979870db1e2248e851/image.png)
  - Si no estuviera en *blacklist* y no cumple con alguna regla terminará almacenandose en app_ip_blacklist
  
## Configuración
- Hay que retocar dos ficheros dentro de la carpeta config:
  - **contexts.json** (link más abajo)
    - Acceso a la bd (mysql) donde se almacenarán todas las peticiones
  - **keywords.json** (link más abajo)
    - Lista de acceso. Las reglas que se aplicarán a cada petición según dominio y endpoint

## El frontend Vue y Vuex
No es obligatorio el despliegue de Vue. Se puede gestionar desde la consola de mysql.<br/>
La interfaz tiene una dependencia. Esta es: [phpapify](https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify) una librería que publica una bd como una **pseudo-api**
Con **phpapify** configurado. (solo hay que configurar el contexto que es el acceso a la bd de ipblocker). <br/>
Hecho esto ya podríamos consumir la bd de ipblocker usando cualquier cliente por medio del protcolo **HTTP/S**.

### Ejemplos de la interfaz
- ![](https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/7b0e29bae06e1aa4b376804cc0f662f8/image.png)
- ![](https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/e799e918478493b2ef1d46443416f09c/image.png)
- ![](https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/d7415ae08cfabe2b0d5f9e6ead908f35/image.png)
- ![](https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/c973859c6a2800ff6088deab6b0e3e86/image.png)

## Lista blanca:
- El archivo: **/php/lib/component_searchbots.php** tiene configurado dos arrays de buscadores. Uno va por dominio y el otro por IPS. Toda ip y/o dominio que esté en estos arrays podrán hacer un rastreo completo del sitio con cualquier llamada POST o GET.
```php
private static $botsns = [
    "baidu1"=>".crawl.baidu.com",
    "baidu2"=>".crawl.baidu.jp",
    "msn"=>".msn.com",
    "google1"=>".google.com",
    "google2"=>".googlebot.com",
    "google3-user"=>".googleusercontent.com",
    "yahoo1"=>".crawl.yahoo.net",
    "yandex1"=>".yandex.ru",
    "yandex2"=>".yandex.net",
    "yandex3"=>".yandex.com",
];

private static $botsip = [
    //https://help.duckduckgo.com/duckduckgo-help-pages/results/duckduckbot/
      "duckduckgo" => [
        "23.21.227.69",
        "40.88.21.235",
        "50.16.241.113",
        "50.16.241.114",
        "50.16.241.117",
        "50.16.247.234",
        "52.204.97.54",
        "52.5.190.19",
        "54.197.234.188",
        "54.208.100.253",
        "54.208.102.37",
        "107.21.1.8",
      ]
];
```

## recursos

### db
- [mysql](https://github.com/eacevedof/prj_ipblocker/tree/master/db)
- [config file: contexts.json](https://github.com/eacevedof/prj_ipblocker/blob/master/config/contexts.json)

### library
- [php - ipblocker](https://github.com/eacevedof/prj_ipblocker/tree/master/php)
- [ACL config file: keywords.json](https://github.com/eacevedof/prj_ipblocker/blob/master/config/keywords.json)

### frontend
- [phpapify](https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify)
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

