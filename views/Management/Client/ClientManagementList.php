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
            if (!empty($clientList)) {
                foreach ($clientList as $client) {  
                    $clientValuesArray = $client->getAll();
                    array_pop($clientValuesArray);
                    array_pop($clientValuesArray);
                    $userValuesArray = $client->getUser()->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($clientValuesArray as $atribute => $value) { //print all attributes from object in a td each
                                if($atribute=="idClient"){
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
                            foreach ($userValuesArray as $atribute => $value) { //print all attributes from object in a td each
                                if(!$atribute=="iduser"){
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <input type="submit" value="Ver" formaction=""> <!--javascript for showing creditcard, if there is one -->
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>ClientManagement/deleteClient">
                        </td>
                    </form>
                </tr>
            <?php
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
