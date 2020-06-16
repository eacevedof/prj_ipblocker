
const is_undefined = mxvar => (typeof mxvar == "undefined")

const is_key = (obj,k) => Object.keys(obj).filter(ki=>ki==k).length>0


const url = {

  route: {},

  get_param(k){
    //alert(JSON.stringify(this.route))
    if(is_undefined(this.route.params))
      return null

    if(is_key(this.route.params, k))
      return this.route.params[k]

    return null
  },   

  get_get(k){
    let uri = window.location.href.split('?');
    //alert(JSON.stringify(uri))
    if(is_undefined(this.route.query[k]))
      return null
    return this.route.query[k]
  },

}
  
export default url;