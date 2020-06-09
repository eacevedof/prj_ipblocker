import axios from "axios"
const BASE_URL = "https://dbsapify.theframework.es"

const Api = {

  async_get_usertoken: async (objlogin)=>{

    const url = `${BASE_URL}/apifiy/security/login`
    
    try {
      const data = new FormData()
      data.append("user",objlogin.username)
      data.append("password",objlogin.password)
      
      console.log("api.async_get_usertoken",url)
      const response = await axios.post(url, data)

      console.log("api.async_get_usertoken.response",response)
      return response
    } 
    catch (e) {
      console.error("ERROR: api.async_get_usertoken.url:",url,"e:",e)
      return {
        error: e
      }
    }
  },//async_get_usertoken

  async_is_validtoken: async (usertoken)=>{
    const url = `${BASE_URL}/apifiy/security/is-valid-token`
    //hay que enviar header: apify-auth: token
    try {
      const data = new FormData()
      data.append("action","checktoken")

      const headers = {}
      headers["apify-auth"] = usertoken
      
      console.log("api.async_is_validtoken",url)
      const response = await axios.post(url, data, headers)

      console.log("api.async_is_validtoken.response",response)
      return response
    } 
    catch (e) {
      console.error("ERROR: api.async_is_validtoken.url:",url,"e:",e)
      return {
        error: e
      }
    }    
  }
  
}//Api

export default Api;