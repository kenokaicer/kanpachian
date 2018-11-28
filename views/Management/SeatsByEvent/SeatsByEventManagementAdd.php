<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
   <div class="wrapper">
      <section>
         <form id="myForm" onsubmit="return gatherData()" action="<?=FRONT_ROOT?>SeatsByEventManagement/addSeatsByEvent" method="post">
         <table id="mainTable" style="padding:0px;margin:0">
            <tr>
               <td colspan="3">
                  Evento:
                  <select id="selectEvent" name="idEvent">
                     <!--no longer use of onchange, for a jquery script that detects click on same option as currently selected-->
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
            
            <tr id="trEventByDate" hidden>
               <!--set unhidden when event changed on Event select-->
               <td colspan="3">
                  Calendario:
                  <select id="selectEventByDate" name="idEventByDate">
                     <!--onchange returns seatTypes-->
                  </select>
               </td>  
            </tr>
            <tr id="trSeatType" hidden>
               <!--set unhidden when event changed on EventByDate select-->
               <td colspan="3">
                  <table>
                    <thead>
                        <th>Tipo de asiento</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </thead>
                    <tbody id="seatTypeTable">
                    </tbody>
                  </table>
               </td>
            </tr>
            <tr id="loading" hidden><td><div><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div></td></tr>
         </table>
         <table style="padding:0px;margin:0">
            <tr>
                <td colspan="3">
                    <div id="hiddenInputs" style="vertical-align: middle;">
                        <button type="submit">Agregar</button>
                        <input style="margin-top: 18px"  class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate> 
                    </div>
                </td>
            </tr>
         </table>
         </form>   
      </section>
   </div>

<style>
input{
    margin:0 auto !important;
}
</style>

<script>
var idSeatTypeList = [];
var quantityList = [];
var priceList = [];


    $("#selectEvent").mouseup(function() { //This is for events. //Is triggered when option changed.
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
                $('#seatTypeTable').empty(); //empty table if it was full
                idSeatTypeList = []; //empty array if it had data
                var i=0;
                ajaxResponse.forEach(loadSeatTypes, i);
                $("#trSeatType").show(500); //show the table after loading it
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
    
    function loadCalendar(p)
    {
        $('#selectEventByDate').append($('<option>',{value:p.idEventByDate,text:'Teatro: ' +p.theaterName + ",  Fecha: "+ p.date }));

        /* Alternative method for older browsers 
        $(option).html("texto");
        $('#selectEventByDate').append(option);
        */
    }

    function loadSeatTypes(p,i)
    {
        var markup = "<tr><td>"+p.seatTypeName+"</td><td><input type='number' id='quantity"+i+"' required></td><td><input type='number' id='price"+i+"' required></td></tr>";
        $('#seatTypeTable').append(markup);
        idSeatTypeList.push(p.idSeatType);   
    }

    function gatherData()
    {
        if(idSeatTypeList.length == 0){
            alert('Asientos no cargados');
            return false;
        }

        var i = 0;

        idSeatTypeList.forEach(fillArrays, i);

        var seatTypeJson = JSON.stringify(idSeatTypeList);
        var quantityJson = JSON.stringify(quantityList);
        var priceJson = JSON.stringify(priceList);
        
        $('#hiddenInputs').append("<input type='hidden' value='"+seatTypeJson+"' name='idSeatTypeList' />");
        $('#hiddenInputs').append("<input type='hidden' value='"+quantityJson+"' name='idQuantityList' />");
        $('#hiddenInputs').append("<input type='hidden' value='"+priceJson+"' name='idPriceList' />");
        $('#selectEvent').prop('disabled', true);

        return true;
    }

    function fillArrays(p,i)
    {
        var quantity = $('#quantity'+i).val();
        var price = $('#price'+i).val();

        $('#quantity'+i).prop('disabled', true);
        $('#price'+i).prop('disabled', true);

        quantityList.push(quantity);
        priceList.push(price);
    }

</script>

