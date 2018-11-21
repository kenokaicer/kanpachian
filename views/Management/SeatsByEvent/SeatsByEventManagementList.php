<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <table id="mainTable" style="padding:0px;margin:0">
        <tr>
            <td colspan="3">
                <h3>Seleccionar Evento</h3>
                <select id="selectEvent" name="idEvent">
                    <?php
                        foreach ($eventList as $value) {
                    ?>
                        <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName()?></option>      
                    <?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr id="trEventByDate" hidden>
            <td colspan="3">
                Calendario:
                <select id="selectEventByDate" name="idEventByDate">
                    <!--onchange loads list-->
                </select>
            </td>
        </tr>
        <td id="trTable" hidden>
            <table id="seatsByEventTable">
                <thead>
                    <th>Tipo de Asiento</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Remanentes</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </thead>
                <tbody id="seatsByEventTableBody">
                </tbody>
            </table>
        </td>
        <tr id="loading" hidden><td><div><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div></td></tr>
    </table>
    <form method="get">
        <section>
            <button type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index">Volver</button>
        </section>
    </form>
</div>

<script>
    $("#selectEvent").mouseup(function() { //This is for events. //Is triggered when option changed.
            var open = $(this).data("isopen");
      
            if(open) {
                //RetriveCalendars(getByEventId',this.value); //moved to when jquerry
                $("#loading").show();
                $("#selectEventByDate").empty();//empty eventByDate select if it was full
                $("#trEventByDate").hide();//hide rest of form if performing another query
                $("#trTable").hide();
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
            $("#trTable").hide();
            $.when(ajaxQuery('getSeatsByEvents',this.value)).done(function(ajaxResponse){ //waits for ajax call to be done
                if (ajaxResponse.length == 0){
                    alert('No hay asientos cargados');    
                }else{
                    $('#seatsByEventTableBody').empty();
                    ajaxResponse.forEach(fillTable);  
                    $("#trTable").show(500); //show the select after loading it
                }
                $("#loading").hide();
            });     
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

        function fillTable(item)
        {
            var selectEventElem = document.getElementById("selectEvent");
            var selectEventByDateElem = document.getElementById("selectEventByDate");
            var eventName = selectEventElem.options[selectEventElem.selectedIndex].text;
            var theaterData = selectEventByDateElem.options[selectEventByDateElem.selectedIndex].text;
            var markup = "<tr><td>"+item.seatTypeName+"</td><td>"+item.quantity+"</td><td>"+item.price+"</td><td>"+item.remnants+"</td><td><a href='<?=FRONT_ROOT?>SeatsByEventManagement/viewEditSeatsByEvent?eventName="+eventName+"&theaterData="+theaterData+"&idSeatsByEvent="+item.idSeatsByEvent+"'>Editar</a></td><td><a href='<?=FRONT_ROOT?>SeatsByEventManagement/deleteSeatsByEvent?idSeatsByEvent="+item.idSeatsByEvent+"'>Eliminar</a></td></tr>";
            $('#seatsByEventTable').append(markup);
        }
      
        function loadCalendar(p)
        {
            $('#selectEventByDate').append($('<option>',{value:p.idEventByDate,text:'Teatro: ' +p.theaterName + ",  Fecha: "+ p.date }));
        }

</script>