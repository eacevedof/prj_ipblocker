import {is_undefined, is_key} from "./functions"

const url = {


  get_param(k,route){
    //alert(JSON.stringify(this.route))
    if(is_undefined(route.params))
      return null

    if(is_key(route.params, k))
      return route.params[k]

    return null
  },   

  get_get(k,route){
    let uri = window.location.href.split('?');
    //alert(JSON.stringify(uri))
    if(is_undefined(route.query[k]))
      return null
    return route.query[k]
  },

}
  
export default url;