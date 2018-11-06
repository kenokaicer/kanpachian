<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th width="20%">Nombre</th>
                <th width="10%">Imágen</th>
                <th width="60%">Descripción</th>
                <th width="10%">Categoría</th>
                <th width="5%">Editar</th>
                <th width="5%">Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($eventList)) {
                $i = 0;
                foreach ($eventList as $event) {  
                    $eventValuesArray = $event->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($eventValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idEvent"){
                        ?>
                                    <input type="hidden" name="idEvent" value="<?=$value?>">
                        <?php 
                                }elseif($attribute=="image"){
                                    echo "<script>var".$i." = '".IMG_PATH.$value."';</script>";
                                    echo "<td><button type='button' onclick='showTable(tr".$i.", td".$i.",var".$i.")'>Ver/Esconder</button></td>";
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <?=$event->getCategory()->getCategoryName();?>
                        </td>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>EventManagement/viewEditEvent"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>EventManagement/deleteEvent">
                        </td>
                    </form>
                </tr>
                <tr id="tr<?=$i?>" hidden>
                    <td colspan="6" id="td<?=$i?>"></td>
                </tr>
            <?php
                $i++;
                }
            }
            ?>
            </tbody>
        </table>
    </section>
    <section>
        <form method="post">
            <button type="submit" formaction="<?=FRONT_ROOT?>EventManagement/index">Volver</button>
        </form>
    </section>
</div>

<script language='javascript' type='text/javascript'>
function showTable(tr, td, image){
    if($(td).is(":empty")){ //create div and img tags only on first show, saving bandwidth by not loading all images
        var div = document.createElement("div");
        var img = document.createElement("img");
        img.setAttribute("src", image);
        div.setAttribute("style", "align:center;width:700px;margin:0 auto");
        td.appendChild(div);
        div.appendChild(img);
    }
    if($(tr).is(":hidden")){
        jQuery(tr).show(1000); //$() is the same as jQuery()
    }else{
        $(tr).hide(1000);
    }   
}
</script>