import axios from "axios"
import helpapify from "@/helpers/apify.ts"

let BASE_URL = "https://dbsapify.theframework.es"
BASE_URL = "https://dbsapify.theframework.es"
BASE_URL = "http://localhost:3000"

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

  async_is_validtoken: async (usertoken) => {
    const url = `${BASE_URL}/apifiy/security/is-valid-token`
    //hay que enviar header: apify-auth: token
    try {

      const data = new FormData()
      data.append("apify-usertoken",usertoken)

      console.log("api.async_is_validtoken.url",url)
      //console.log("api.async_is_validtoken.headers",headers)
      const response = await axios.post(url,data)

      console.log("api.async_is_validtoken.response.data",response.data)
      return response.data
    } 
    catch (e) {
      console.error("ERROR: api.async_is_validtoken.url:",url,"e:",e)
      return {
        error: e
      }
    }    
  },
  
  async_get_ip_request: async (usertoken) => {
    //const url = `${BASE_URL}/apify/read?context=c3&dbname=dbs433062`
    const url = `${BASE_URL}/apify/read?context=c3&dbname=db_security`

    //hay que enviar header: apify-auth: token
    try {
      const objselect = helpapify.select
      objselect.table = "api_ip_request"
      objselect.fields.push("id")
      objselect.fields.push("remote_ip")
      const objform = objselect.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_get_ip_request",url)
      const response = await axios.post(url, objform)

      console.log("api.async_get_ip_request.response",response)
      return response
    } 
    catch (e) {
      console.error("ERROR: api.async_get_ip_request.url:",url,"e:",e)
      return {
        error: e
      }
    }
  }

}//Api

export default Api;