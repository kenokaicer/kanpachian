<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
        <section>
            <h3 style="color:white">Seleccionar Evento</h3>
            <select name="idEvent" id="idEvent">
                <?php
                    foreach ($eventList as $value) {
                ?>
                    <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName()?></option>      
                <?php
                    }
                ?>
            </select>
        </section>
        <section style="background-color:white">
            <table id="main-table">
                <thead>
                    <th>Teatro</th>
                    <th>Fecha</th>
                    <th>Fecha Finalización Oferta</th>
                    <th>Es Oferta</th>
                    <th>Artistas</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </thead>
                <tbody id="main-tbody">
                </tbody>
            </table>
            <table id="loading" hidden>
                <tr><td><tr><td><div><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div></td></tr></td></tr>
            </table>
        </section>
        <section>
            <form method="get"><button class="button" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/index">Volver</button></form>
        </section>
    </form>
</div>

<script>
$("#idEvent").mouseup(function() { //This is for events. //Is triggered when option changed.
    var open = $(this).data("isopen");

    if(open) {
        $("#loading").show();
        $("#main-tbody").empty();//empty eventByDate select if it was full
        $.when(ajaxQuery('getByEventId',this.value)).done(function(ajaxResponse){ //waits for ajax call to be done
            if (ajaxResponse.length == 0){
                alert('No hay Calendarios cargados para este evento');
            }else{
                ajaxResponse.forEach(fillTable);
            }
            $("#loading").hide();
        });  
    }

    $(this).data("isopen", !open);
});

function ajaxQuery(func,value)
{
    return $.ajax({ //return needed for when jquery
        url : <?=FRONT_ROOT?>+'controllers/Ajax/EventByDateManagementAjax.php', // requesting a PHP script
        type: 'post',
        dataType : 'json',
        data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
        success : function (data) 
        { // data contains the PHP script output
        },
    })
}

function fillTable(item)
{
    if(item.isSale == 1){
        var sale = 'Si';
    }else{
        var sale = 'No';
    }

    if(item.endPromoDate == null){
        item.endPromoDate = "-";
    }

    var markup = "<tr><td>"+item.theaterName+"</td><td>"+item.date+"</td><td>"+item.endPromoDate+"</td><td>"+sale+"</td><td>"+item.artistList+"</td><td><a href='<?=FRONT_ROOT?>EventByDateManagement/viewEditEventByDate?idEventByDate="+item.idEventByDate+"'>Editar</a></td><td><a href='<?=FRONT_ROOT?>EventByDateManagement/deleteEventByDate?idEventByDate="+item.idEventByDate+"'>Eliminar</a></td></tr>";
    $('#main-tbody').append(markup);
}
</script>
