const Apify = {
  //https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify
  select: {
    table: "",
    fields: [],
    where: [],
    groupby:[],
    orderby:[],

    get_query(){
      const oform = new FormData()
      oform.append("queryparts[table]",Apify.select.table)
      Apify.select.fields.forEach((field,i) => {
        oform.append(`queryparts[fields][${i}]`,field)
      });
      return oform
    }
  }



}

export default Apify