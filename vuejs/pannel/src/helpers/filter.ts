import {pr, cl, is_defined} from "../helpers/functions"
import { table } from '@/modules/iprequest/queries'
const CS = "|" //command separator
const LS = ":" //lable separator

const mini = str => str.trim().toLowerCase()

const get_field = (label, arconfig) => {
  const labelmin = mini(label)
  const r = arconfig.map(obj => obj.table.fields.filter(objf => mini(objf.label) === labelmin) )
  return r.filter(ar => ar.length>0)[0][0]
}

const filtercmd = (search, arconfig=[])=>{
  if(search.trim()=="") return []
  //if()

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
    const alias = is_defined(objtable.alias) ? objtable.alias +".": ""
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