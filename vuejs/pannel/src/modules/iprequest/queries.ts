import helpapify from "@/helpers/apify"
import {is_defined, get_keys, isset, is_empty, is_key, pr} from "@/helpers/functions"

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

export const get_obj_list = (objparam={filter:{},page:{},orderby:{}})=>{

  objselect.reset()

  objselect.table = `${table} r`
  objselect.foundrows = 1 //que devuelva el total de filas
  objselect.distinct = 1  //que aplique distinct
  
  query.fields.forEach(fieldconf => objselect.fields.push(fieldconf))
  
  if(!is_empty(objparam.filter)){
    const filters = get_keys(objparam.filter)
    const stror = filters.map(field => `${field} LIKE '%${objparam.filter[field]}%'`).join(" OR ")
    objselect.where.push(`(${stror})`)
  }

  objselect.limit.perpage = 1000
  objselect.limit.regfrom = 0
  if(!is_empty(objparam.page)){
    objselect.limit.perpage = objparam.page.ippage
    objselect.limit.regfrom = objparam.page.ifrom
  }

  objselect.orderby.push("r.id DESC")
  pr(objselect,"get_obj_list.objselect")
  return objselect
}//get_list

export const detail = (objparam={filter:{}})=>{
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

export const get_obj_insert = (objparam={fields:{}})=>{
  const objinsert = helpapify.insert
  objinsert.reset()
  objinsert.table = table

  if(!is_empty(objparam)){
    const fields = get_keys(objparam.fields)
    fields.forEach( field => {
      objinsert.fields.push({k:field,v:objparam.fields[field]})
    })  
  }

  return objinsert
}

export const get_obj_update = (objparam={fields:{}},dbfields=[])=>{
  const objupdate = helpapify.update
  objupdate.reset()
  objupdate.table = table

  if(is_defined(objparam.fields)){
    const onlyfields = dbfields.map(dbfield => dbfield.field_name)
    const fields = get_keys(objparam.fields)

    fields.forEach( field => {
      if(!onlyfields.includes(field))
        return
  
      //si el campo es clave
      if(objparam.keys.includes(field)){
        objupdate.where.push(`${field}='${objparam.fields[field]}'`)
      }
      else
        objupdate.fields.push({k:field,v:objparam.fields[field]})
    })    
  }

  return objupdate
}

export const get_obj_detete = (objparam={fields:{},keys:[]})=>{
  const objdelete = helpapify.delete
  objdelete.reset()
  objdelete.table = table
  
  if(isset(objparam.fields) && isset(objparam.fields)){
    const fields = Object.keys(objparam.fields)
    fields.forEach( field => {
      if(!objparam.keys.includes(field))
        return
      objdelete.where.push(`${field}='${objparam.fields[field]}'`)
    })  
  }
  
  return objdelete
}



