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
                  alert(output);
                   //lala = document.getElementById("demo");
                   var str ='<div id="divCheckbox" style="display: none;">'+ output+'</div>' ;
                   document.writeline(str);
                  // output.getElementById('return');
                  //console.log(lala);

              }
    });
}

function CallPHPFunction() // option = id , date , theater. && el claendario set attribute value = id. .
{

    $.ajax({
    url : 'ajaxtest.php', // requesting a PHP script
    dataType : 'json',
    success : function (data) { // data contains the PHP script output
      console.log(data);
       alert(data);
    },
})
}

function Load(div,page)
{
  alert("Hola");
  $("#" + div).load(page);
}


