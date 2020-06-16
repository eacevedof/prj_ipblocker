export const is_undefined = mxvar => (typeof mxvar == "undefined")

export const is_key = (obj,k) => Object.keys(obj).filter(ki=>ki==k).length>0

export const pr = (mxvar,title="") => alert(title+":\n"+JSON.stringify(mxvar))

export const is_object = mxvar => (typeof mxvar == "object")

export const get_error = objerr => ({"error": objerr.toString().replace("Error:","").trim()})
