<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
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
                foreach ($artistList as $artist) {  
                    $artistValuesArray = $artist->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($artistValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idArtist"){
                        ?>
                                    <input type="hidden" name="idArtist" value="<?=$value?>">
                        <?php 
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>ArtistManagement/viewEditArtist"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>ArtistManagement/deleteArtist">
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
            <button type="submit" formaction="<?=FRONT_ROOT?>ArtistManagement/index">Volver</button>
        </form>
    </section>
</div>
