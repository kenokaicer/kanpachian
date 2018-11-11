<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
   <div class="wrapper">
      <section>

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
            <form action="<?=FRONT_ROOT?>SeatsByEventManagement/addSeatsByEvent" method="post">

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
                  Tipo de Asiento:
                  <select id="selectSeatType" name="idSeatType">
                     <!--onchange sets idSeatType to hidden input -->
                  </select>
               </td>
            </tr>
               <tr id="inputs" hidden>
                  <!--set unhidden when event changed on SeatType select-->
                  <td>
                     Cantidad: <input type="number" name="quantity" required>
                  </td>
                  <td>Precio: <input type="number" name="price" required></td>
               </tr>
               <tr id="loading" hidden><td><div><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div></td></tr>
         </table>
         <table style="padding:0px;margin:0">
         <tr>
         <td colspan="3">
         <div style="vertical-align: middle;">
         <button type="submit">Agregar</button>
         <input style="margin-top: 18px"  class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate> 
         
         </div>
         </td>
         </tr>
         </table>
         </form>
         <button onclick="test()">test</button>
      </section>
   </div>
   <script>
   
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

        function test(){
            $("#selectEventByDate").empty();
        }
      
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

