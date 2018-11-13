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

$("#selectEvent").mouseup(function() 
        { //This is for events. //Is triggered when option changed.
            var open = $(this).data("isopen");
      
            if(open) {
                //RetriveCalendars(getByEventId',this.value); //moved to when jquerry
                $("#loading").show();
                $("#selectEventByDate").empty();//empty eventByDate select if it was full
                $("#selectSeatType").empty();
                $("#trEventByDate").hide();//hide rest of form if performing another query
                $("#trSeatType").hide();
                $("#inputs").hide();
                $.when(ajaxQuery('getByEventId',this.value)).done(function(ajaxResponse){ //waits for ajax call to be done
                    if (ajaxResponse.length == 0){
                        alert('No hay Calendarios cargados para este evento');
                    }else{
                        ajaxResponse.forEach(loadCalendar);
                        $("#trEventByDate").show(500); //show the select after loading it
                    }
                    $("#loading").hide();
                });  
            }

            $(this).data("isopen", !open);
        });
      
        $("#selectEventByDate").mouseup(function() { // This is for calendars
            var open = $(this).data("isopen");
    
            if(open) {
            $("#loading").show();
            $("#selectSeatType").empty();
            $("#trSeatType").hide();
            $("#inputs").hide();
            $.when(ajaxQuery('getSeatTypes',this.value)).done(function(ajaxResponse){ //waits for ajax call to be done
                if (ajaxResponse.length == 0){
                    alert('No hay Asientos para cargar para esta fecha (ya se han cargado todos los asientos)');    
                }else{
                    ajaxResponse.forEach(loadSeatTypes);
                    $("#trSeatType").show(500); //show the select after loading it
                }
                $("#loading").hide();
            });     
            }
      
            $(this).data("isopen", !open);
        });
      
        $("#selectSeatType").mouseup(function() { //This is for seat Types.
            var open = $(this).data("isopen");
            if(open) { //only show the inputs tr
                $("#inputs").show(500); //show the select after loading it
            }
            $(this).data("isopen", !open);
        });
      
        function ajaxQuery(func,value)
        {
            return $.ajax({ //return needed for when jquery
                url : <?=FRONT_ROOT?>+'controllers/Ajax/SeatsByEventManagementAjax.php', // requesting a PHP script
                type: 'post',
                dataType : 'json',
                data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
                success : function (data) 
                { // data contains the PHP script output
                    //$(this).data("isopen", !open);
                    //data.forEach(loadCalendar); //this line was used here before implementing jquery.done
                },
            })
        }
      
        function loadCalendar(p)
        {
            $('#selectEventByDate').append($('<option>',{value:p.idEventByDate,text:'Teatro: ' +p.theaterName + ",  Fecha: "+ p.date }));

            /* Alternative method for older browsers 
            $(option).html("texto");
            $('#selectEventByDate').append(option);
            */
        }

        function loadSeatTypes(p)
        {
            $('#selectSeatType').append($('<option>',{value:p.idSeatType,text: p.seatTypeName }));
        }


           
    //another idea is to load all seat types in lines, and pass to the controller a list of seattype ids, values and quantities as json


      
      //estaría bueno agregar una funcion que agrege los asientos sin irse de la página, el tema es que como devolver la confirmación si
      //los agregó o no, 
      //de ser hay que hacer un remove del option del select de seatType, para prevenir que no se pueda volver a cargar
   </script>