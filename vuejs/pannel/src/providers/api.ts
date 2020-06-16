import axios from "axios"
import helpapify from "@/helpers/apify.ts"
import db from "@/helpers/localdb"
import {is_undefined, get_error} from "@/helpers/functions"

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
      if(is_undefined(response.data.data.token))
        throw new Error("Wrong data received from server. No token")

      return response.data.data.token
    } 
    catch (e) {
      console.error("ERROR: api.async_get_usertoken.url:",url,"e:",e)
      return get_error(e)
    }
  },//async_get_usertoken

  async_is_validtoken: async () => {
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apifiy/security/is-valid-token`
    //hay que enviar header: apify-auth: token
    try {

      const data = new FormData()
      data.append("apify-usertoken",usertoken)

      console.log("api.async_is_validtoken.url",url)
      
      const response = await axios.post(url,data)
      console.log("api.async_is_validtoken.response raw",response)
      //pr(response,"async_is_valid_token")
      
      if(is_undefined(response.data.data.isvalid))
        throw new Error("Wrong data received from server. Token validation")

      return response.data.data.isvalid
    } 
    catch (e) {
      console.error("ERROR: api.async_is_validtoken.url:",url,"e:",e)
      return get_error(e)
    }    
  },
  
  async_get_ip_request: async (objpage=null, id=null, filters=[]) => {
    const usertoken = db.select("usertoken")
    //const url = `${BASE_URL}/apify/read?context=c3&dbname=dbs433062`
    const url = `${BASE_URL}/apify/read?context=c3&dbname=db_security`

    //hay que enviar header: apify-auth: token
    try {
      const objselect = helpapify.select
      objselect.reset()

      objselect.table = "app_ip_request r"
      objselect.foundrows = 1
      objselect.distinct = 1
      objselect.fields.push("r.id")
      objselect.fields.push("r.remote_ip")
      objselect.fields.push("i.country")
      objselect.fields.push("i.whois")
      objselect.fields.push("r.domain")
      objselect.fields.push("r.request_uri")
      objselect.fields.push("r.`get`")
      objselect.fields.push("CASE WHEN r.`get`!='' THEN 'GET' ELSE '' END hasget")
      objselect.fields.push("r.post")
      objselect.fields.push("CASE WHEN r.`post`!='' THEN 'POST' ELSE '' END haspost")      
      objselect.fields.push("r.insert_date")
      objselect.fields.push("bl.insert_date bl_date")
      objselect.fields.push("bl.reason")
      objselect.fields.push("CASE WHEN bl.id IS NULL THEN '' ELSE 'INBL' END inbl")
      
      objselect.joins.push("LEFT JOIN app_ip_blacklist bl ON r.remote_ip = bl.remote_ip")
      objselect.joins.push("LEFT JOIN app_ip i ON r.remote_ip = i.remote_ip")

      objselect.where.push("i.whois NOT LIKE '%google%'")
      objselect.where.push("i.whois NOT LIKE '%msn%'")
      filters.forEach(filter => {
        objselect.where.push(filter)
      })

      if(id)
        objselect.where.push(`r.id=${id}`)
      
      objselect.orderby.push("r.id DESC")

      objselect.limit.perpage = 1000
      objselect.limit.regfrom = 0
      if(objpage!==null)
        if(Object.keys(objpage).length>0){
          objselect.limit.perpage = objpage.ippage
          objselect.limit.regfrom = objpage.ifrom
        }



      const objform = objselect.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_get_ip_request",url)
      const response = await axios.post(url, objform)

      console.log("api.async_get_ip_request.response",response)

      if(is_undefined(response.data.data))
        throw new Error("Wrong data received from server. Resultset")

      return response.data.data
    } 
    catch (e) {
      console.error("ERROR: api.async_get_ip_request.url:",url,"e:",e)
      return get_error(e)
    }
  },

  async_get_fields: async(table)=>{
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apify/fields/c3/db_security/${table}`

    try{
      const objform = new FormData()
      objform.append("apify-usertoken",usertoken)
      const fields = await axios.post(url,objform)
      return fields
    }
    catch (e) {
      console.error("ERROR: api.async_get_fields.url:",url,"e:",e)
      return get_error(e)
    }    
  },

  async_insert: async(objrow) => {
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apify/write?context=c3&dbname=db_security`
    //hay que enviar header: apify-auth: token
    try {
      const objinsert = helpapify.insert
      objinsert.reset()
      objinsert.table = "app_ip_request"

      const fields = Object.keys(objrow)
      fields.forEach( field => {
          objinsert.fields.push({k:field,v:objrow[field]})
      })

      const objform = objinsert.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_insert",url)
      const response = await axios.post(url, objform)
      //pr(response,"async_insert")
      console.log("api.async_insert.response",response)

      if(is_undefined(response.data.data.lastid))
        throw new Error("Wrong data received from server. insert lastid")
      //alert(JSON.stringify(response.data.data)) esto viene con result: las filas, y numrows: el total
      return response.data.data.lastid
    } 
    catch (e) {
      console.error("ERROR: api.async_insert.url:",url,"e:",e)
      return get_error(e)
    }
  },

  async_update: async(objrow, keys=[]) => {
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apify/write?context=c3&dbname=db_security`
    //hay que enviar header: apify-auth: token
    try {
      const objupdate = helpapify.update
      objupdate.reset()

      objupdate.table = "app_ip_request"

      const arfields = await Api.async_get_fields(objupdate.table)
      if(arfields.error)
        throw new Error(arfields.error)

      console.log("arfields",arfields)
      const onlyfields = arfields.data.data.map(objconf => objconf.field_name)


      const fields = Object.keys(objrow)
      fields.forEach( field => {
        if(!onlyfields.includes(field))
          return

        //si el campo es clave
        if(keys.includes(field)){
          objupdate.where.push(`${field}='${objrow[field]}'`)
        }
        else
          objupdate.fields.push({k:field,v:objrow[field]})
      })

      const objform = objupdate.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_update",url)
      const response = await axios.post(url, objform)

      console.log("api.async_update.response",response)

      if(is_undefined(response.data.data.result))
        throw new Error("Wrong data received from server. Update result")
      //alert(JSON.stringify(response.data.data)) esto viene con result: las filas, y numrows: el total
      return response.data.data.result
    } 
    catch (e) {
      console.error("ERROR: api.async_update.url:",url,"e:",e)
      return get_error(e)
    }
  },

  async_delete: async(objrow, keys=[]) => {
    //alert("async objrow: "+JSON.stringify(objrow))
    //return
    const usertoken = db.select("usertoken")
    const url = `${BASE_URL}/apify/write?context=c3&dbname=db_security`
    //hay que enviar header: apify-auth: token
    try {
      const objdelete = helpapify.delete
      objdelete.reset()

      objdelete.table = "app_ip_request"

      const fields = Object.keys(objrow)
      fields.forEach( field => {
        if(!keys.includes(field))
          return
        objdelete.where.push(`${field}='${objrow[field]}'`)
      })

      const objform = objdelete.get_query()
      objform.append("apify-usertoken",usertoken)

      console.log("api.async_delete",url)
      const response = await axios.post(url, objform)

      console.log("api.async_delete.response",response)
      //devuelve el num de registros afectados
      if(is_undefined(response.data.data.result))
        throw new Error("Wrong data received from server. Delete result")
      //alert(JSON.stringify(response.data.data)) esto viene con result: las filas, y numrows: el total
      return response.data.data.result
    } 
    catch (e) {
      console.error("ERROR: api.async_delete.url:",url,"e:",e)
      return get_error(e)
    }
  }, //async_delete

  async_getflags: async(arrows) => {
    if(is_undefined(arrows.length) || arrows.length==0)
        return
    
        //https://restcountries.eu/rest/v2/alpha?codes=ES;PE
    const url = `https://restcountries.eu/rest/v2/alpha?codes=`
    //hay que enviar header: apify-auth: token
    try {
      const fnunique = (value, i, self) => (self.indexOf(value) == i)

      const arcodes = arrows.map(objrow => objrow.country).filter(fnunique)
      if(!arcodes) return
      const strcodes = arcodes.join(";")
      const response = await axios.get(url+strcodes)
      
      //devuelve el num de registros afectados
      if(is_undefined(response.data))
        throw new Error("Wrong data received from server. getflags result")

      const fullinfo = response.data
      const mindata = fullinfo.map(objinfo => ({name:objinfo.name,flag:objinfo.flag}))
      //pr(mindata,"mindata")
      return mindata
    } 
    catch (e) {
      console.error("ERROR: api.async_getflags.url:",url,"e:",e)
      return get_error(e)
    }
  }, //async_getflags  


}//Api


export default Api;