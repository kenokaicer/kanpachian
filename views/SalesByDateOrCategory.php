<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
    
<h2 style="color:white">Ventas</h2>
<div class="wrapper" style="border-style:none;min-height:58vh;width:800px">
    <section class="app-feature-section">
        <div class="row align-middle">
            <div class="small-12 medium-12 columns" >
                <h3 class="app-feature-section-main-header">Seleccione Categoría o Fecha</h3>    
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
                <div>
                    <table id="main-table">
                        <thead>
                            <th>Categoría</th>
                            <th>Total</th>
                        </thead>
                        <tbody id="table-body">
                            <?php
                            if(isset($totalPriceByCategoryArray)){
                                foreach ($totalPriceByCategoryArray as $cat => $price) {
                            ?>
                                <tr>
                                    <td><?=$cat?></td>
                                    <td>$<?=$price?></td>
                                </tr>
                            <?php
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
    $.when(ajaxQuery('getTotalByDate',date1)).done(function(ajaxResponse){ //waits for ajax call to be done
        ajaxResponse = "$" + ajaxResponse;
        document.getElementById("totalSale").value = ajaxResponse;
        $("#loading").hide();
    }); 

    return false
}

function ajaxQuery(func,value)
{
    return $.ajax({ //return needed for when jquery
        url : <?=FRONT_ROOT?>+'controllers/Ajax/CheckSalesByDateOrCategoryControllerAjax.php', // requesting a PHP script
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

