\\\\ SETTINGS /////

Set database settings in crud.php

$host = 
$dabase = 
$username = 
$password = '';

Set crud.php location in crud.js
if crud.php is in same directory just leave it empty

\\\\ HOW TO /////

make new instace of crud object with a tablename
	var crud_users = new crud('users');
                
Create user
        crud_users.create({
              name:'ole',
              password:'pw123',
              rank:'1'
         });
                
Update user
         crud_users.update({
              password:'pw1234'
         },'name="ole"');
            
Remove user
         crud_users.remove('name="ole"');
                
print user
         var result = crud_users.read('name, password as pw')
         alert( result[0].name+' '+result[0].pw );
      