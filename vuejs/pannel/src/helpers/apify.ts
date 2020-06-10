const Apify = {
  //https://github.com/eacevedof/prj_phpapify/tree/master/backend/src/Controllers/Apify
  select: {
    table: "",
    fields: [],
    where: [],
    groupby:[],
    orderby:[],
    //limit:{regfrom:0,perpage:300,},
    limit:{perpage:null, regfrom:0},

    get_query(){
      const oform = new FormData()
      const thisselect = Apify.select
      oform.append("queryparts[table]",thisselect.table)
      
      thisselect.fields.forEach((field,i) => {
        oform.append(`queryparts[fields][${i}]`,field)
      });

      thisselect.where.forEach((strcond,i) => {
        oform.append(`queryparts[where][${i}]`,strcond)
      });

      thisselect.groupby.forEach((strcond,i) => {
        oform.append(`queryparts[groupby][${i}]`,strcond)
      });

      thisselect.orderby.forEach((strcond,i) => {
        oform.append(`queryparts[orderby][${i}]`,strcond)
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
      thisselect.fields = []
      thisselect.where = []
      thisselect.groupby = []
      thisselect.orderby = []
      thisselect.limit = {perpage:null, regfrom:0}
    },

  }



}

export default Apify