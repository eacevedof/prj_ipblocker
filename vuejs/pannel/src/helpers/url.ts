
const is_undefined = mxvar => (typeof mxvar == "undefined")

const url = {

  route: {},

  get_param(k){
    //console.log("URL ROUTE",this.route)
    if(is_undefined(this.route.params[k]))
      return null
    return this.route.params[k]
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