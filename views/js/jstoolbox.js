
 var contador=0;
 var idComboSeleccionado;
 var maximoGustos;



 function nuevoPedido(cod)
 {
   //  console.log(cod);
    idComboSeleccionado = cod;
    selectCombo(cod);

    switch(cod) {
    case 1:
        maximoGustos= 4;
        break;
    case 2:
        maximoGustos= 3;
        break;
    case 3:
        maximoGustos= 6;
        break;
    case 4:
        maximoGustos= 9;
        break;
    case 5:
        maximoGustos= 4;
        break;


}

 }


 function selectCombo(comboID) // Hides non selected divs.
 {

  var childDivs = document.getElementById('comboPanel').getElementsByTagName('div');

  for( i=0; i< childDivs.length; i++ )
  {
     var childDiv = childDivs[i];
     //console.log("objeto:" + childDiv.id + ",  comboID:" + comboID);

     if(childDiv.id!=comboID)
     hideDivs(childDiv.id);

  }


    switchHiddenDiv("gustosPanel");
    switchHiddenDiv("escogidos");


 }

 function hideDivs(id)
 {
    var x = document.getElementById(id);

    if (x.style.display === "none") 
    {
        x.style.display = "block";
    } 
    else 
    {
        x.style.display = "none";
    }
 }

 function switchHiddenDiv(id)
 {

    var x = document.getElementById(id);
    console.log(x);

    if (x.style.visibility == "hidden") 
    {
        x.style.visibility = "visible";
    } 
    else 
    {
        x.style.visibility = "hidden";
    }
 }


function obtenerPeso()
 {
    var rate_value ="wtf";

    if (document.getElementById('1/4k').checked) 
    {
     rate_value = document.getElementById('1/4k').value;
    }
    else if (document.getElementById('1/2k').checked) 
    {
     rate_value = document.getElementById('1/2k').value;
    }
    else if (document.getElementById('1k').checked) 
    {
     rate_value = document.getElementById('1k').value;
    }

    return rate_value;
 }


function dosomething(heladoCodigo)
{


  if(contador>=maximoGustos)
  {
    alert("No se pueden agregar mas gustos");
  }
  else
  {

    contador++;
    addToTable(heladoCodigo);
  }
 

}

function getCookie(gustos)
{
  var c = document.cookie;
  //console.log(c);
  return c;
}

function addToTable(obj)
{
  var parenttbl = document.getElementsByTagName("tr");
  var newel = document.createElement('td');
  newel.setAttribute("id", obj);
  var elementid = document.getElementsByTagName("td").length; // Obtains the amount of td.
  //newel.setAttribute('id',elementid);
  var button = '<input type="submit" class="button" value="'+obj+'"'; 
  button+=' id="'+obj+'" onclick="removeFromTD(this.id)">';
  newel.innerHTML = '<tr><p class="text-center">'+ button +'</p></td>';
  parenttbl[0].appendChild(newel);
}

function addToDiv(obj)
{
  var div = document.createElement("div"); 
  div.className = "large-4 medium-6 cell";                      
  var t = document.createTextNode("This is a paragraph.");      
  div.appendChild(t);                                         
  document.getElementById("escogidos").appendChild(div);          
  var button = '<input type="submit" class="button" value="'+obj.gusto+'"'; 
  button+=' id="'+obj.gusto+'" onclick="removeFromTD(this.id)">';
}

function addCookie(valor)
{
  var json_str = JSON.stringify(valor);
  document.cookie=json_str;
  return json_str;
 // console.log(getCookie());
}

function getCookieWithName(name) {
  //var value = "; " + document.cookie;
  var value = document.cookie;
 // console.log(value);
  value = value.split(";", 1);
  //console.log(value);
  var obj = JSON.parse(value);
  //console.log(obj.gusto);
}

  function removeFromTD(id)
  {
    //console.log(id);
    var element = document.getElementById("id");
    //console.log(element);
  }

  function getIdOfTheTds()
  {
    var arr = ['vacio'];
   arr = [];
   $("td").each(function() 
   {
      var id = $(this).attr("id");
      arr.push(id);
   });
    return arr;
  }
  function sendDataViaAjax(data)
  {
    var gustos = getIdOfTheTds();
    var peso = obtenerPeso();
     var p =
     {
       g:gustos,
       p:peso
     };


    var jsonAr = JSON.stringify(p);
    $.ajax({ url: 'testy',
         data: {action: jsonAr},
         type: 'post',
         success: function(output) {
                      alert(output);
                  }
    });
  }


