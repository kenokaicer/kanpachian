
<div class="main-wrapper">
  <div class="header"><h1>Kanpachian!</h1></div>
  <div id="vue">
    <cart :cart="cart" :cart-sub-total="cartSubTotal" :tax="tax" :cart-total="cartTotal" :checkout-bool="checkoutBool"></cart>
    <products :cart="cart" :cart-sub-total="cartSubTotal" :tax="tax" :cart-total="cartTotal" :products-data="productsData"></products>
    <checkout-area v-if="checkoutBool" :cart="cart" :tax="tax" :cart-sub-total="cartSubTotal" :cart-total="cartTotal" :products-data="productsData" :total-with-tax="totalWithTax"></checkout-area>
  </div>
</div>

<script>
var cart = new Cart();
</script>
<!--
<button id="1" class="button primary"<img src="img/1.jpg" alt=""  onclick="cart.add(new Product(1,'Gato',200));">Demo Cart</button>
-->

<button id="2" class="button primary"<img src="img/1.jpg" alt=""  onclick="woopity('ajax','perro','woopity');">Demo Ajax</button>

<button id="2" class="button primary"<img src="img/1.jpg" alt=""  onclick="woopity('ajax','gato','woopity');">Demo Ajax</button>
<article class="grid-container">

<h2>Seleccione su combo</h2>

<style type="text/css" media="screen">
 .button img {
    display: inline-block;
    padding-right: 1px;
    padding-top: 5px;
    border-radius: 0%;
    padding-bottom: 10px;
  }

  .button
  {
    border: 2px solid white;
    border-radius: 15px;
    text-align: center;
    opacity: .7
  }

.button:hover {background-color: black}

.button:active {
  background-color: black;
 box-shadow: 5px 10px #888888;
   transform: translateY(4px);
}

.button:hover {opacity: 1}

</style>



<div class="grid-x grid-margin-x small-up-3 medium-up-4 large-up-5" id="comboPanel">
<div id="1" class="cell">
<button id="1" name="1k" class="button primary"<img src="img/1.jpg" alt=""  onclick="nuevoPedido(1);">Combo 1 kg</button>
</div>
<div id="2" class="cell">
<button href="#" id="2" name="1/2k" class="button primary"<img src="img/2.jpg" alt="" onclick="nuevoPedido(2)">Combo 1/2 kg</button>
</div>
<div id="3" class="cell">
<button href="#" id="3"  name="2x1/4k" class="button primary"<img src="img/3.jpg" alt="" onclick="nuevoPedido(3)">2 de 1/4 kg</button>
</div>
<div id="4" class="cell">
<button href="#" id="4" name="2x1/4k"class="button primary"<img src="img/4.jpg" alt="" onclick="nuevoPedido(4)">3 de 1/4 kg</button>
</div>
<div id="5" class="cell">
<button href="#" id="5" class="button primary"<img src="img/5.jpg" alt="" onclick="nuevoPedido(5)">Combo 1 kg</button>
</div>
</div>
</article>




<!--
<div class="callout primary"> 
   <div class ="row column">
      <fieldset class="large-5 cell">
         <legend>Elija tamaño del pedido</legend>
         <input type="radio" name="peso" value="1/4k" id="1/4k" checked="checked"  required>
         <label for="1/4">1/4 Un cuarto</label>
         <input type="radio" name="peso" value="1/2k" id="1/2k">
         <label for="1/2">1/2 Medio Kilo</label>
         <input type="radio" name="peso" value="1k" id="1k">
         <label for="1">1 Kilo</label>
      </fieldset>
   </div>
</div>
-->


   <div class="row medium-up-3 large-up-5"  id="escogidos" style="visibility: hidden" >
    <table id="tabla" height="200" bgcolor="#E6E6FA">
    <caption>Gustos Seleccionados</caption>
      <tr id="fila">

      </tr>
    </table>

    <div class ="column" >
    <input type="submit" class="button" value="Enviar Pedido" onclick="sendDataViaAjax('milanesa')">
   </div>
   </div>



<style type="text/css" media="screen">
  
.sticky-topbar {
  width: 100%;
}
</style>



    <?php
    
      $pedidos = \controllers\pedidosController::get()->getAll();
      $cantidadDeColumnas = 3;
      $boostrapDivision = 12/$cantidadDeColumnas;
      $contador=0;
      //var_dump($pedidos);
      foreach($pedidos as $x => $x_value) 
      {
       if($contador==0)
       {
        echo'<div class="large-12 cell" id="gustosPanel" style="visibility: hidden">'; //hidden
        echo'<div class="grid-x grid-padding-x">';
       }
      $pc =  $x_value["post_title"];
       echo'<div class="large-'.$boostrapDivision.' medium-6 cell">';
      // echo'<div class="md-wishlist" mbsc-form>';
         //Chocolatechocolate amargoFrutilla
        //echo'<div mbsc-page class="demo-wishlist">';
        //echo'<div class="md-wishlist" mbsc-form>';
       // echo'<img src=class="md-wishlist-img">';
       // print("<center>");
        print($x_value["post_title"]);
        print("</ceneter>");
        print("<center>".$x_value['post_content']."</center>");
        echo '<input type="submit" class="button" value="Agregar Gusto" 
        id="'. $x_value["post_title"].'" onclick="dosomething(this.id)">';
       // echo'<div class="md-title">'.$x_value["post_title"].'</div>';
        //print('<button id="'.$pc.'" class="mbsc-btn mbsc-btn-block md-wish" "data-icon="plus" onClick="reply_click(this.id)"> <span class="md-wish-text">Agregar gusto</span></button>');
             echo'</div>';//end row
        if($contador==$cantidadDeColumnas)
        {
           echo'</div>';//end row
             echo'</div>';//end large-12 cell
            $contador=$cantidadDeColumnas;
        }
        $contador++;
   
      }
      
      
      ?> 
</div>


<script>

 var contador=0;
 var idComboSeleccionado;
 var maximoGustos;

 function xd()
{
  aler("holis");
    $.ajax({
       url : "index.php", // the resource where youre request will go throw
       type : "POST", // HTTP verb
       data : { action: 'myActionToGetHits', param2 : myVar2 },
       dataType: "json",
       success : function (response) {
          //in your case, you should return from the php method some fomated data that you  //need throw the data var object in param
             data = toJson(response) // optional
            //heres your code
            
       },
       error : alert("wrong"); 
       complete : //...
});
}




 function nuevoPedido(cod)
 {
   //  console.log(cod);
    idComboSeleccionado = cod;
    var prod = new Product(cod,"Gusto","0");
    add(prod);
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


</script>







