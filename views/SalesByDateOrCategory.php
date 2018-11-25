<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
    
<h2 style="color:white">Ventas</h2>
<div class="wrapper" style="border-style:none;min-height:58vh;width:900px">
    <section class="app-feature-section">
        <div class="row align-middle">
            <div class="small-12 medium-12 columns" >
                <h3 class="app-feature-section-main-header">Seleccione por Fecha</h3>    
                <!--<h4 class="app-feature-section-sub-header" style="display:inline-block">TEXTO</h4>-->
                <div>
                    <form action="" onsubmit="return getTotal()">
                        <input type="date" name="date" id="date" required>                          
                        <button type="submit">Ver por Fecha</button>
                    </form>
                    <div id="loading" style="position:absolute;left:48%" hidden><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div>
                </div>
                <div>
                    <input style="margin:0 auto;width:200px" type="text" name="totalSale" id="totalSale" readonly>
                </div>
                </section>
                <section class="app-feature-section">
                <h3 class="app-feature-section-main-header">Seleccione Fecha de la Categoría</h3>    
                <div>
                <input type="date" name="date2" id="date2">
                <div id="loading2" style="position:absolute;left:48%" hidden><img src="<?=IMG_PATH?>loading.gif" alt="Loading"></div>
                    <table id="main-table">
                        <thead>
                            <th>Categoría</th>
                            <th>Total</th>
                            <th>Ver</th>
                            <th>Total por día</th>
                        </thead>
                        <tbody id="table-body">
                            <?php
                            $i = 0;
                            if(isset($totalPriceByCategoryArray)){
                                foreach ($totalPriceByCategoryArray as $cat => $price) {
                            ?>
                                <tr>
                                    <td style="width:40%" id="catName<?=$i?>"><?=$cat?></td>
                                    <td style="width:20%" id="catTotal<?=$i?>">$<?=$price?></td>
                                    <td style="width:20%"><div style="margin: 0 auto"><input type="button" onclick="getTotalCat(<?=$i?>)" value="ver"></div></td>
                                    <td style="width:20%"><input id="totalPriceCat<?=$i?>" style="margin:0" type="text" readonly></td>
                                </tr>
                            <?php
                                $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>


function getTotal()
{   
    var date1 = document.getElementById("date").value;

    $("#loading").show();
    $.when(ajaxQuery('getTotalByDate',date1,0)).done(function(ajaxResponse){ //waits for ajax call to be done
        ajaxResponse = "$" + ajaxResponse;
        document.getElementById("totalSale").value = ajaxResponse;
        $("#loading").hide();
    }); 

    return false
}

function getTotalCat(i)
{
    var totalPrice = "totalPriceCat"+i;
    var catName = "catName"+i;
    var date2 = document.getElementById("date2").value;
    var cat = document.getElementById(catName).innerText

    if(date2 == ""){
        alert('Debe seleccionar una fecha primero');
    }else{
        $("#loading2").show();

        $.when(ajaxQuery('getTotalByDateAndCategory',date2, cat)).done(function(ajaxResponse){ //waits for ajax call to be done
            ajaxResponse = "$" + ajaxResponse;
            $('#'+totalPrice).val(ajaxResponse);
            $("#loading2").hide();
        });
    }
}

function ajaxQuery(func,value,value2)
{
    return $.ajax({ //return needed for when jquery
        url : <?=FRONT_ROOT?>+'controllers/Ajax/CheckSalesByDateOrCategoryControllerAjax.php', // requesting a PHP script
        type: 'post',
        dataType : 'json',
        data: {"function": func, "value": value, "value2": value2}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
        success : function (data) 
        { // data contains the PHP script output
            //can do something here with the returned data
        },
    })
}
</script>

