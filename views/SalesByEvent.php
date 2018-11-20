<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
    
<h2 style="color:white">Ventas</h2>
<div class="wrapper" style="border-style:none;min-height:58vh;width:800px">
    <section class="app-feature-section">
        <div class="row align-middle">
            <div class="small-12 medium-12 columns" >
                <h3 class="app-feature-section-main-header">Seleccione el Evento</h3>    
                <!--<h4 class="app-feature-section-sub-header" style="display:inline-block">TEXTO</h4>-->
                <div>
                    <select id="selectEvent">
                        <?php
                        if(isset($eventList)){
                            foreach ($eventList as $event) {
                        ?>
                        <option value="<?=$event->getIdEvent()?>"><?=$event->getEventName()?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>                            
                </div>
                <div>
                    <table id="main-table">
                        <thead>
                            <th>Teatro</th>
                            <th>Fecha</th>
                            <th>Asiento</th>
                            <th>Vendidos</th>
                            <th>Remanentes</th>
                        </thead>
                        <tbody id="table-body">

                        </tbody>
                        <tbody id="loading" hidden><tr><td colspan="5"><div><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$("#selectEvent").mouseup(function() { //This is for events. //Is triggered when option changed.
    var open = $(this).data("isopen");

    if(open) {
        $("#loading").show();
        $("#table-body").empty();//empty table if it was full
        $.when(ajaxQuery('getByEventId',this.value)).done(function(ajaxResponse){ //waits for ajax call to be done
            if (ajaxResponse.length == 0){
                alert('No hay Calendarios o Plazas Evento cargadas para este evento');
            }else{
                ajaxResponse.forEach(loadTable);
            }
            $("#loading").hide();
        });  
    }

    $(this).data("isopen", !open);
});

function loadTable(p)
{
    $('#table-body').append("<tr><td>"+p.theaterName+"</td><td>"+p.date+"</td><td>"+p.seatType+"</td><td>"+p.sold+"</td><td>"+p.remnants+"</td></tr>")
}

function ajaxQuery(func,value)
{
    return $.ajax({ //return needed for when jquery
        url : <?=FRONT_ROOT?>+'controllers/Ajax/CheckSalesByEventControllerAjax.php', // requesting a PHP script
        type: 'post',
        dataType : 'json',
        data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
        success : function (data) 
        { // data contains the PHP script output
            //can do something here with the returned data
        },
    })
}
</script>

