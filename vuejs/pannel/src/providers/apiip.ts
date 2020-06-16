import axios from "axios"
import {get_error} from "@/helpers/functions"

const Apiip = {
  
  async_get_myip: async function(){
    const url = "https://api.ipify.org?format=json"
    try{
      const response = await axios.get(url)
      //pr(response,"response.ip")
      const ip = response.data.ip
      return ip
    }
    catch(e){
      console.error("ERROR: apiip.async_get_myip.url:",url,"e:",e)
      return get_error(e)
    }

  }
}
export default Apiip;