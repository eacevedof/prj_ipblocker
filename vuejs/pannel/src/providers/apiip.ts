import axios from "axios"
import helpapify from "@/helpers/apify.ts"
import db from "@/helpers/localdb"
import {is_undefined, pr, get_error} from "@/helpers/functions"

let BASE_URL = "https://dbsapify.theframework.es"
BASE_URL = "https://dbsapify.theframework.es"
BASE_URL = "http://localhost:3000"

const Apiip = {
  
  async_get_myip: async function(){
    const url = "https://api.ipify.org?format=json"
    const response = await axios.get(url)
    const ip = response.data.ip
    return ip
  }
}
export default Apiip;