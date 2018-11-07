var script = document.createElement('script');
 
script.src = '//code.jquery.com/jquery-3.3.1.min.js';
document.getElementsByTagName('head')[0].appendChild(script); 


function woopity(controller,method,data)
{
    var jsonAr = JSON.stringify(data);

    $.ajax({ url: controller+"/"+method,
     data: {action: jsonAr},
     type: 'post',
     success: function(output) {
                  //alert(output);
                  lala = output.getElementById('return');
                  console.log(lala);

              }
    });
}

/*function demo()
{
     
    $.ajax({ url: 'ajax/perro',
     data: {action: 'perro'},
     type: 'post',
     success: function(output) {
            //  alert(output);
              }
    });
}*/

function Load(div,page)
{
  alert("Hola");
  $("#" + div).load(page);
}


