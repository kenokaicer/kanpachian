<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>EventByDateManagement/addEventByDate" method="post">
            <table>
                <tr>
                    <td colspan="2">Evento:
                        <select name="idEvent">
                            <?php
                                foreach ($eventList as $value) {
                            ?>
                                <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName().", Categoría: ".$value->getCategory()->getCategoryName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Fecha: <input type="date" name="date"></td>
                    <td>Teatro:
                        <select name="idTheater">
                            <?php
                                foreach ($theaterList as $value) {
                            ?>
                                <option value="<?=$value->getIdTheater()?>"><?=$value->getTheaterName().", maxCap: ".$value->getMaxCapacity()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Artista: 
                        <select name="idArtist" onchange="addArtistList(this.value)">
                            <?php
                                foreach ($artistList as $value) {
                            ?>
                                <option value="<?=$value->getIdArtist()?>"><?=$value->getName()." ".$value->getLastname()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">Lista de artistas seleccionados</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>EventByDateManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>


                            <button onclick="sendx()">probar</button>

<script>

var artistList = [];


function addArtistList(artist)
{
 //Falta remover el artista que se agrego.
 artistList.push(artist);
 console.log(artistList);
 //Se debe pasar la id de la tabla. 
 addToTable(artist);
 //sendAjaxsend(artistList);
 //post('test',artist,'test');
}

function send()
{
    post('test',artistList,'test');
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
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

function sendx()
{
    var jsonAr = JSON.stringify(artistList);
     
    $.ajax({ url: 'test',
     data: {action: jsonAr},
     type: 'post',
     success: function(output) {
                  alert(output);
              }
    });
    
}

/*
$("button").click(function(){
alert("ostias");
);
*/




</script>