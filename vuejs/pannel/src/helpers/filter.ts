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

const filtercmd = (search, arconfig=[])=>{
  if(search.trim()=="") return []
  if(!search.includes(LS)) return []

  const commands = search.split(CS).map(strcmd => mini(strcmd))
  
  const labels = commands.map(cmd => {
    const parts = cmd.split(LS)
    return {
      label: parts[0],
      cmd: parts[1] || ""
    }
  })

  //pr(arconfig,"arconfig"); return
  const r = labels.map(objcmd => get_field(objcmd.label, arconfig))
  cl(r,"r")

  //pr(labels,"labels")
}

export const filtersimple = (search, arconfig) => {
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
  return allfields
}

export default filtercmd;