<?php

?>

<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($artistList)) {
                foreach ($artistList as $key => $artist) {  
                    $artistValuesArray = $artist->getAll();
            ?>
                <tr>
                    <?php 
                        foreach ($artistValuesArray as $atribute => $value) { //print all atributes from object in a td each
                            if($atribute!="idArtist"){
                                echo "<td>";
                                echo $value;
                                echo "</td>";
                            }
                        }
                    ?>
                    <td>
                    <form method="post">
                        <?php
                            foreach ($artistValuesArray as $atribute => $value) {
                        ?>
                                <input type="hidden" name="<?=$atribute?>" value="<?=$value?>">
                        <?php
                            }
                        ?>                         
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>ArtistManagement/viewEditArtist">
                        
                    </td>
                    <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>ArtistManagement/deleteArtist">
                        </form>
                    </td>
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
            <button type="submit" formaction="<?=FRONT_ROOT?>ArtistManagement/index">Volver</button>
        </form>
    </section>
</div>
