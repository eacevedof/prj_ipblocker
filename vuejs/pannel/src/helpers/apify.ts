const Apify = {
  //https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify
  select: {
    table: "",
    foundrows:0,
    distinct: 0,
    fields: [],
    joins: [],
    where: [],
    groupby:[],
    having:[],
    orderby:[],
    //limit:{regfrom:0,perpage:300,},
    limit:{perpage:null, regfrom:0},

    get_query(){
      const thisselect = Apify.select
      const oform = new FormData()

      //table
      oform.append("queryparts[table]",thisselect.table)

      if(thisselect.foundrows)
        oform.append("queryparts[foundrows]",thisselect.foundrows)

      if(thisselect.distinct)
        oform.append("queryparts[distinct]",thisselect.distinct)
      
      thisselect.fields.forEach((field,i) => {
        oform.append(`queryparts[fields][${i}]`,field)
      });

      thisselect.joins.forEach((join,i) => {
        oform.append(`queryparts[joins][${i}]`,join)
      });

      thisselect.where.forEach((strcond,i) => {
        oform.append(`queryparts[where][${i}]`,strcond)
      });

      thisselect.groupby.forEach((field,i) => {
        oform.append(`queryparts[groupby][${i}]`,field)
      });

      thisselect.having.forEach((metric,i) => {
        oform.append(`queryparts[having][${i}]`,metric)
      });

      thisselect.orderby.forEach((field,i) => {
        oform.append(`queryparts[orderby][${i}]`,field)
      });      

      if(thisselect.limit.perpage){
        oform.append(`queryparts[limit][perpage]`,thisselect.limit.perpage)
        oform.append(`queryparts[limit][regfrom]`,thisselect.limit.regfrom)
      }
      
      return oform
    },

    reset(){
      const thisselect = Apify.select
      thisselect.table = ""
      thisselect.foundrows =0
      thisselect.distinct = 0
      thisselect.fields = []
      thisselect.joins = []
      thisselect.where = []
      thisselect.groupby = []
      thisselect.having = []
      thisselect.orderby = []
      thisselect.limit = {perpage:null, regfrom:0}
    },
  }, //select
  
  update:{
    table: "",
    fields: [],
    where: [],

    get_query(){
      const thisupdate = Apify.update
      const oform = new FormData()
      oform.append("action","update")

     //table
     oform.append("queryparts[table]",thisupdate.table)

     
     thisupdate.fields.forEach( field => {
       oform.append(`queryparts[fields][${field.k}]`,field.v)
     });

     thisupdate.where.forEach((strcond,i) => {
       oform.append(`queryparts[where][${i}]`,strcond)
     });      

     return oform
    },
    
    reset(){
      const thisupdate = Apify.update
      thisupdate.table = ""
      thisupdate.fields = []
      thisupdate.where = []
    },    
  }//update


}

export default Apify