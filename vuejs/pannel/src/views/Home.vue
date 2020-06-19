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
        <p>
          En los próximos intentos recibiría un mensaje como el siguiente:<br/>
          <img src="https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/632x214/14ff372f5163fa979870db1e2248e851/image.png" 
            width="500"
            height="200"
          />
        </p>
        <h3>- El Frontend Vue y Vuex</h3>
        <p>
          Es opcional.  Al ser 3 tablas no implica mucho esfuerzo hacer la gestión de las mismas por consola. <br/>
          No obstante para facilitar este trabajo me he decantado por Vue y Vuex apoyandome en un mini-framework PHP
          <a href="https://github.com/eacevedof/prj_phpapify/tree/master/backend" class="black--text" target="_blank"><b>PHP Apify - Github</b></a><br/>
          "PHPApify" publica una <b>BD</b> en mysql en forma de <b>API</b>
        </p>
        <h4>Ejemplos de la UI:</h4>
        <ul>
          <li>
            <img src="https://trello-attachments.s3.amazonaws.com/569bbf4d1fa18d93a4e89813/5ed40bd5cb5f856d00a8a3f5/9688d9bfaddaa06bfaa69e23cc7f13dd/image.png" 
              width="500"
              height="100"
            />
          </li>
          <li>
            <img src="https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/1009x666/a95ad1c570f3deb86bd568546851ff10/image.png" 
              width="500"
              height="400"
            />
          </li>     
          <li>
            <img src="https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/939x667/7fe4dde5babfa2abed70257bfe399bdc/image.png" 
              width="500"
              height="400"
            />
          </li>
          <li>
            <img src="https://trello-attachments.s3.amazonaws.com/5ed40bd5cb5f856d00a8a3f5/1084x796/a0c414df2995286a63d54d459e0192cb/image.png" 
              width="500"
              height="400"
            />
          </li>          
        </ul>
      </v-col>
    </v-row>
    <v-row>
        <v-btn
            v-scroll="onScroll"
            v-show="fab"
            fab
            dark
            fixed
            bottom
            right
            color="primary"
            class="black--text"
            @click="toTop"
          >
          <v-icon>keyboard_arrow_up</v-icon>
        </v-btn>
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

  data: () => ({
    fab: false
  }),

  methods: {
    to_top(){
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
      });
    },  
    onScroll (e) {
      if (typeof window === 'undefined') return
      const top = window.pageYOffset ||   e.target.scrollTop || 0
      this.fab = top > 20
    },
    toTop () {
      this.$vuetify.goTo(0)
    }
  }  
};
</script>
