import helpapify from "@/helpers/apify"
import {is_undefined, get_keys, isset} from "@/helpers/functions"

const objselect = helpapify.select

const table = "app_ip_request"

const query = {
  fields:[
    "r.id",
    "r.remote_ip",
    "i.country",
    "i.whois",
    "r.domain",
    "r.request_uri",
    "r.`get`",
    "CASE WHEN r.`get`!='' THEN 'GET' ELSE '' END hasget",
    "r.post",
    "CASE WHEN r.`post`!='' THEN 'POST' ELSE '' END haspost",      
    "r.insert_date",
    "bl.insert_date bl_date",
    "bl.reason",
    "CASE WHEN bl.id IS NULL THEN '' ELSE 'INBL' END inbl",
  ],

}

export const get_list = (objparam={})=>{

  objselect.reset()

  objselect.table = `${table} r`
  objselect.foundrows = 1 //que devuelva el total de filas
  objselect.distinct = 1  //que aplique distinct
  
  query.fields.forEach(fieldconf => objselect.fields.push(fieldconf))
  
  if(isset(objparam.filter)){
    const filters = get_keys(objparam.filter)
    const stror = filters.map(field => `${field} LIKE '%${objparam.filter[field]}%'`).join(" OR ")
    objselect.where.push(`(${stror})`)
  }

  objselect.limit.perpage = 1000
  objselect.limit.regfrom = 0
  if(isset(objparam.page)){
    objselect.limit.perpage = objparam.page.ippage
    objselect.limit.regfrom = objparam.page.ifrom
  }

  objselect.orderby.push("r.id DESC")
  
  return objselect
}//get_list

export const detail = (objparam={})=>{
  objselect.reset()

  objselect.table = `${table} r`
  objselect.foundrows = 1 //que devuelva el total de filas
  objselect.distinct = 1  //que aplique distinct
    
  query.fields.forEach(fieldconf => objselect.fields.push(fieldconf))
  
  if(isset(objparam.filter)){
    const filters = get_keys(objparam.filter)
    filters.map(field => `${field}='${objparam.filter[field]}'`).forEach(cond => objselect.where.push(cond))
  }
  
  return objselect

}

export const get_insert = (objparam={})=>{
  const objinsert = helpapify.insert
  objinsert.reset()
  objinsert.table = "app_ip_request"

  const fields = get_keys(objparam)
  fields.forEach( field => {
      objinsert.fields.push({k:field,v:objrow[field]})
  })  
}

export const get_update = ()=>{
  
}

export const get_detete = ()=>{
  
}



