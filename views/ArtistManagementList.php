<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Apellido</th>
            </thead>
            <tbody>
            <?php
            if (!empty($artistList)) {
                foreach ($artistList as $key => $artist) {  
            ?>
                <tr>
                    <td><?= $artist['name'] ?></td>
                    <td><?= $artist['lastname'] ?></td>
                    <td>
                    <form action="<?=BASE?>ArtistManagement/viewEditArtist" method="post">
                            <input type="hidden" name="id" value="<?=$artist['id_artist']?>">
                            <input type="hidden" name="name" value="<?=$artist['name']?>">
                            <input type="hidden" name="lastname" value="<?=$artist['lastname']?>">
                            <input type="submit" value="Editar">
                        </form>
                    </td>
                    <td>
                        <form action="<?=BASE?>ArtistManagement/deleteArtist" method="post">
                            <input type="hidden" name="id" value="<?=$artist['id_artist']?>">
                            <input type="submit" value="Eliminar">
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
            <button type="submit" formaction="<?=BASE?>ArtistManagement/index">Volver</button>
        </form>
    </section>
</div>
