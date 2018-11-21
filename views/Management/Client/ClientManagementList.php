<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dni</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>rol</th>
                <th>Tarjeta de Crédito</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (isset($clientList)) {
                $i=0;
                foreach ($clientList as $client) {  
                    $clientValuesArray = $client->getAll();
                    $userValuesArray = $client->getUser()->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($clientValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idClient"){
                        ?>
                                    <input type="hidden" name="idClient" value="<?=$value?>">
                        <?php 
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <?php 
                            foreach ($userValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute != "idUser" && $attribute != "password"){
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <div style="margin:0 auto">
                                <input type="button" onclick="showTable(<?php echo 'table'.$i ?>)" value="Ver" formaction=""> <!--javascript for showing creditcard, if there is one -->
                            </div>
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>ClientManagement/deleteClient">
                        </td>
                    </form>
                </tr>
                <tr id="table<?=$i?>" hidden>
                    <td colspan="8">
                        <table>
                            <th style="width:25%">Número de tarjeta</th>
                            <th style="width:25%">Fecha de vencimiento</th>
                            <th style="width:50%">Titular</th>
                            <?php
                            if(!is_null($client->getCreditCard())){
                            ?>
                                <tr>
                                    <td><?=$client->getCreditCard()->getCreditCardNumber()?></td>
                                    <td><?=$client->getCreditCard()->getExpirationDate()?></td>
                                    <td><?=$client->getCreditCard()->getCardHolder()?></td>
                                </tr>
                            <?php
                            }else{
                            ?>
                                <tr>
                                    <td colspan="3">Tarjeta no cargada</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </td>
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
            <button type="submit" formaction="<?=FRONT_ROOT?>ClientManagement/index">Volver</button>
        </form>
    </section>
</div>

<script language='javascript' type='text/javascript'>
function showTable(tr){
    if($(tr).is(":hidden")){
        $(tr).show(500); 
    }else{
        $(tr).hide(500);
    }   
}
</script>