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
      //alert(JSON.stringify(response))
      //alert(response.data.data.token)
      return response.data.data.token
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
      objselect.reset()

      objselect.table = "app_ip_request r"
      objselect.foundrows = 1
      
      objselect.fields.push("r.id")
      objselect.fields.push("r.remote_ip")      
      objselect.fields.push("r.domain")
      objselect.fields.push("r.request_uri")
      objselect.fields.push("r.`get`")
      objselect.fields.push("r.post")
      objselect.fields.push("r.insert_date")
      objselect.fields.push("CASE WHEN bl.id IS NULL THEN '' ELSE 'Y' END inbl")
      objselect.joins.push("LEFT JOIN app_ip_blacklist bl ON r.remote_ip = bl.remote_ip")
      
      objselect.orderby.push("r.id DESC")
      objselect.limit.perpage = 50
      objselect.limit.regfrom = 0


      const objform = objselect.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_get_ip_request",url)
      const response = await axios.post(url, objform)

      console.log("api.async_get_ip_request.response",response)
      //alert(JSON.stringify(response.data.data)) esto viene con result: las filas, y numrows: el total
      return response.data.data
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