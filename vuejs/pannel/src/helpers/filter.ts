import {pr, cl, is_defined} from "../helpers/functions"
import { table } from '@/modules/iprequest/queries'
const CS = "|" //command separator
const LS = ":" //lable separator cmd:search |

const mini = str => str.trim().toLowerCase()

const get_field = (label, arconfig) => {
  const labelmin = mini(label)
  if(labelmin=="") return []
  
  const allfields = []
  arconfig.forEach(objtable => {
    
    const alias = is_defined(objtable.table.alias) ? objtable.table.alias +".": ""

    objtable.table.fields.forEach( objf => {
      const fldlbl = mini(objf.label)
      if(fldlbl !== labelmin) return
      
      const obj = {
        field: `${alias}${objf.name}`,
      }
      allfields.push(obj)
    })

  })

  return allfields
}

const filtercmd = (search, arconfig=[]) => {
  if( search.trim()=="" ) return []
  if( !search.includes(LS) || !search.includes(CS) ) return filtersimple(search, arconfig)

  const commands = search.split(CS).map(strcmd => mini(strcmd))
  
  const labels = commands.map(cmd => {
    const parts = cmd.split(LS)
    return {
      label: parts[0],
      search: parts[1] || ""
    }
  }).filter(objsrch => objsrch.label != "")

  //cl(labels,"l")
  //pr(arconfig,"arconfig"); return
  const r = labels
                .map(objsrch => {
                  const arfield = get_field(objsrch.label, arconfig)
                  if(objsrch.search=="") return
                  if(arfield.length==0) return
                  return {
                    field:  arfield[0].field || "",
                    value:  objsrch.search
                  }
                })
                .filter(el => is_defined(el))
                //.map(ar => ar[0])
  //cl(r,"r")
  return r
  // Nº:1|get:xxx|post
}

const filtersimple = (search, arconfig) => {
  //const table = arconfig.table.name
  const allfields = []
  arconfig.forEach(objtable => {
    const alias = is_defined(objtable.table.alias) ? objtable.table.alias +".": ""
    objtable.table.fields.forEach( objf => {
      const obj = {
        field: `${alias}${objf.name}`,
        value: search,
      }
      allfields.push(obj)
    })
  })
  pr(allfields,"filtersimple")
  return allfields
}

export default filtercmd;