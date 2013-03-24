// crud.php location

var crud_location = '';
function crud(table)
{
    this.table = table;
    this.create = create;
    this.read = read;
    this.update = update;
    this.delete = _delete;
    this.crud = crud_location+'crud.php';
    
    // Return false or last insert id
    function create(json_obj){
        $.ajax({
            type:"POST",
            async: false,
            url:this.crud,
            data:{type:'create',data:json_obj,table:this.table}
        }).done(function(data){
            return data;
        })
    }
    
    // Return false or json obj
    function read(column_names,where,order,limit){
        var _return;
        where = (typeof where === "undefined") ? false : where;
        order = (typeof order === "undefined") ? false : order;
        limit = (typeof limit === "undefined") ? false : limit;
        
        where = (where === false) ? '' : 'where '+where;
        order = (order === false) ? '' : 'ORDER BY '+order;
        limit = (limit === false) ? '' : 'LIMIT '+limit;
    
        $.ajax({
            type:"POST",
            url:this.crud,
            async: false,
            data:{type:'read',data:'select '+column_names+' from '+this.table+' '+where+' '+order+' '+limit,table:this.table}
        }).done(function(data){
            _return = eval(data);
        })
        
        return _return;
        
    }
    
    // return false or change id
    function update(json_obj,where){
         where = (typeof where === "undefined") ? false : where;
         where = (where === false) ? '' : 'where '+where;
           $.ajax({
                type:"POST",
                url:this.crud,
                async: false,
                data:{type:'update',data:json_obj,where:where}
            }).done(function(data){
                return_data = data;
            })
    }
    
    // return false or true
    function _delete(where){
          where = (typeof where === "undefined") ? false : where;
          if(where !== false){
            $.ajax({
                  type:"POST",
                  url:this.crud,
                  async: false,
                  data:{type:'update',data:json_obj,where:where}
            }).done(function(data){
                  return data;
            })
          }
          return false;
    }
}