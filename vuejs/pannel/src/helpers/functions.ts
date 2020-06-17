export const is_undefined = mxvar => (typeof mxvar === "undefined")

export const is_defined = mxvar => (typeof mxvar !== "undefined")

export const isset = mxvar => mxvar!==null && is_defined(mxvar)

export const is_key = (obj,k) => Object.keys(obj).filter(ki=>ki==k).length>0

export const pr = (mxvar,title="") => alert(title+":\n"+JSON.stringify(mxvar))

export const is_object = mxvar => (typeof mxvar == "object")

export const get_error = objerr => ({"error": objerr.toString().replace("Error:","").trim()})

export const is_objectlit = objany => isset(objany) ? is_defined(objany.constructor) ? objany.constructor == Object : false : false

export const get_keys = objany => !is_objectlit(objany) ? [] : Object.keys(objany)

export const is_empty = objany => !isset(objany) ? true : (is_objectlit(objany) && get_keys(objany).length == 0)

export const cl = (objany, title="") => console.log(title, objany)