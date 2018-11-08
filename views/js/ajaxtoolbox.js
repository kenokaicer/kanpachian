var script = document.createElement('script');
 
script.src = '//code.jquery.com/jquery-3.3.1.min.js';
document.getElementsByTagName('head')[0].appendChild(script); 


function woopity(controller,method,data)
{
    var jsonAr = JSON.stringify(data);

    $.ajax({ url: controller+"/"+method,
     data: {action: jsonAr},
     type: 'post',
     success: function(data) {

            var res = data.split(" ");
                  alert(output);
                   //lala = document.getElementById("demo");
                   //var str ='<div id="divCheckbox" style="display: none;">'+ output+'</div>' ;
                   //document.writeline(str);
                  // output.getElementById('return');
                  //console.log(lala);

              }
    });
}

//mismo problema, devuelve index, header y footer en la respuesta, y es dificil de filtrar el resultado para json, script o xml
function CallPHPFunction2(controller,method,value) //llamar al metodo y pasa parametro/s por GET
{
    $.ajax({
    url: controller+"/"+method+"?value="+value,
    type: 'get',
    dataType : 'script',
    success: function(data) {
        console.log(data);
        alert(data);
    },
})
}

function CallPHPFunction(path,func, value) // option = id , date , theater. && el claendario set attribute value = id. .
{
    $.ajax({
    url : path+'SeatsByEventManagementAjax.php', // requesting a PHP script
    type: 'post',
    dataType : 'json',
    data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
    success : function (data) { // data contains the PHP script output
        //console.log(data);
        //alert(data);
        alert(data);
        console.log(data);
        return data;
    },
})
}

function Load(div,page)
{
  alert("Hola");
  $("#" + div).load(page);
}


