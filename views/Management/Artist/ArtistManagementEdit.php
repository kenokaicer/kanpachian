<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>ArtistManagement/editArtist" method="post">
            <input type="hidden" name="idOldArtist" value="<?=$oldArtist->getIdArtist()?>">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$oldArtist->getName()?>" required></td>
                    <td>Apellido: <input type="text" name="lastname" value="<?=$oldArtist->getLastname()?>" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input class="button" type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>ArtistManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>