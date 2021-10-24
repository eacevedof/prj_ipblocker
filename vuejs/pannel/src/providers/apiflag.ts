import axios from "axios"
import {is_undefined, get_error} from "@/helpers/functions"

const Apiflag = {

  async_getflags: async(arrows) => {
    if(is_undefined(arrows.length) || arrows.length==0)
      return

    //https://restcountries.com/v3/alpha?codes=ES,PE
    const url = `https://restcountries.com/v3/alpha?codes=`
    //hay que enviar header: apify-auth: token
    try {
      const fnunique = (value, i, self) => (self.indexOf(value) == i)

      const arcodes = arrows.map(objrow => objrow.country).filter(fnunique)
      if(!arcodes) return
      const strcodes = arcodes.join(",")
      const response = await axios.get(url+strcodes)

      //devuelve el num de registros afectados
      if(is_undefined(response.data))
        throw new Error("Wrong data received from server. getflags result")

      const fullinfo = response.data
      const mindata = fullinfo.map(objinfo => ({name:objinfo.name.common, flag:objinfo?.flags[0] ?? ""}))
      //pr(mindata,"mindata")
      return mindata
    }
    catch (e) {
      console.error("ERROR: apiflag.async_getflags.url:",url,"e:",e)
      return get_error(e)
    }
  }, //async_getflags
}

export default Apiflag;
