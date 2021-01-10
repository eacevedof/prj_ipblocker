import {BASE_URL} from "../config/constants"
import axios from "axios"
import {is_undefined, pr, get_error} from "@/helpers/functions"
import db from "@/helpers/localdb"

const Apiauth = {

  async_get_usertoken: async (objlogin)=>{

    const url = `${BASE_URL}/apify/security/login`
    
    try {
      const data = new FormData()
      data.append("user",objlogin.username)
      data.append("password",objlogin.password)
      
      console.log("apidb.async_get_usertoken",url)
      const response = await axios.post(url, data)

      console.log("apidb.async_get_usertoken.response",response)
      //pr(response)
      if(is_undefined(response.data.data.token))
        throw new Error("Wrong data received from server. No token")

      return response.data.data.token
    } 
    catch (e) {
      console.error("ERROR: apidb.async_get_usertoken.url:",url,"e:",e)
      return get_error(e)
    }
  },//async_get_usertoken

  async_is_validtoken: async () => {
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apify/security/is-valid-token`
    //hay que enviar header: apify-auth: token
    try {

      const data = new FormData()
      data.append("apify-usertoken",usertoken)

      console.log("apidb.async_is_validtoken.url",url)
      
      const response = await axios.post(url,data)
      console.log("apidb.async_is_validtoken.response raw",response)
      //pr(response,"async_is_valid_token")
      
      if(is_undefined(response.data.data.isvalid))
        throw new Error("Wrong data received from server. Token validation")

      return response.data.data.isvalid
    } 
    catch (e) {
      console.error("ERROR: apidb.async_is_validtoken.url:",url,"e:",e)
      return get_error(e)
    }    
  },

}//Apiauth

export default Apiauth;