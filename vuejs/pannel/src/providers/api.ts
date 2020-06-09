import axios from "axios"
const BASE_URL = "https://dbsapify.theframework.es"

const Api = {

  get_async_apikey: async(objlogin)=>{

    const url = `${BASE_URL}/apifiy/security/login`
    
    try {
      const data = new FormData()
      data.append("user",objlogin.username)
      data.append("password",objlogin.password)
      
      console.log("api.get_async_apikey",url)
      const response = await axios.post(url, data)

      console.log("api.get_async_apikey.response",response)
      return response
    } 
    catch (e) {
      console.error("ERROR: api.get_async_apikey.url:",url,"e:",e)
      return {
        error: e
      }
    }
  }//get_async_apikey
  
}//Api

export default Api;