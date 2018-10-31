var script = document.createElement('script');
 
script.src = '//code.jquery.com/jquery-3.3.1.min.js';
document.getElementsByTagName('head')[0].appendChild(script); 


function send(data)
{
    var jsonAr = JSON.stringify(data);
     
    $.ajax({ url: 'test.php',
     data: {action: jsonAr},
     type: 'post',
     success: function(output) {
                  alert(output);
              }
    });
}
