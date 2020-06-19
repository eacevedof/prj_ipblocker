<template>
  <v-container>
    <scrumbs pagename="home" />
    <v-row>
      <v-col align="center">
        <v-img src="https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/128x128/c34d70188cc11e108b37e84dd97894e0/image.png"
        width="100"
        height="100"
        />
      </v-col>
    </v-row>
    <h1>IP Blocker</h1>
    <v-row>
      <v-col>
        <ul>
          <li>
            <a href="https://github.com/eacevedof/prj_ipblocker" class="black--text" target="_blank">Código fuente - Github</a><br/>
          </li>
        </ul>
        <br/>
        <h3>-¿Qué es?</h3>
        <p>
          IP Blocker es una mini librería <b>&lt;100K</b> realizada en <b>PHP</b> y gestionada con <b>Vue</b>.<br/>
          Tine como objetivo
          la gestión las peticiones que se hacen en distintos dominios que tienen php como backend y más concretamente
          que cuentan con un <b>"frontcontroller"</b> (por ejemplo Symfony, Cakephp, Yii, etc...) <br/>
          Aunque yo lo estoy usando en Wordpress.
        </p>
        <h3>- El Backend</h3>
        <p>
          Es la librería con un único punto de entrada el archivo: <b>public/ipblocker.php</b> <br/>
          Actuará como interceptor de peteciones.<br/>
          <code lang="php" class="blue--text">
          $pathboot = realpath(__DIR__."/../boot");<br/>
          include("$pathboot/appbootstrap.php");<br/>
          use \TheFramework\Components\ComponentIpblocker;<br/>
          (new ComponentIpblocker())->handle_request();
          </code><br/>
        </p>
        <h4>Configuración:</h4>
        <p>
          Este archivo se inyecta en el <b>frontcontroller</b> de la siguiente forma:
          <code lang="php" class="blue--text"><br/>
          if(is_file("ipblocker-folder-path/php/public/ipblocker.php"))<br/>
              include("ipblocker-folder-path/php/public/ipblocker.php");
          </code><br />
          Lo ideal es hacerlo en las primeras lineas del frontcontroller con el fin de evaluar la IP y lanzar un <b>exit()</b> si no se le permite el acceso
        </p>
        <h4>¿Qué se consigue con esta inyección?</h4>
        <p>
          Principalmente que se registren todas las peticiones a los dominios configurados en
          <a href="https://github.com/eacevedof/prj_ipblocker/blob/master/config/keywords.json" class="black--text" target="_blank">
            <b>config/keywords.json</b> (configuración ACL)
          </a><br/><br/>
          Ejemplo:<br/>
          <a href="https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/53a7a0438b19f6597a9b956620f962e8/image.png" target="_blank">
            <img src="https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/53a7a0438b19f6597a9b956620f962e8/image.png" 
              width="500"
              height="300"
            />
          </a>
        </p>
        <p>
          En base a estas peticiones y la configuración de <b>keywords.json</b> Se alimentará la tabla <b>app_ip_request</b>
          si esta petición no cumple con la <b>ACL</b> se agregará la ip de origen en <b>app_ip_blacklist</b> quedando así bloqueada
          para futuros accesos.<br/><br/>
          Ejemplo:<br/>
          <a href="https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/aa15adf40814f851d9777db766ffff6c/image.png" target="_blank">
            <img src="https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/aa15adf40814f851d9777db766ffff6c/image.png" 
              width="500"
              height="300"
            />
          </a>
        </p>

      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import scrumbs from "@/components/navigation/scrumbs.vue"
export default {
  name: "home",

  components:{
    scrumbs,
  },
};
</script>
