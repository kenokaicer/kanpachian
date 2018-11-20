<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>ArtistManagement/addArtist" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" required></td>
                    <td>Apellido: <input type="text" name="lastname" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button class="button" type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>ArtistManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>