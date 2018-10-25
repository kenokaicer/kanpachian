<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>ArtistManagement/editArtist" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$name?>"></td>
                    <td>Apellido: <input type="text" name="lastname" value="<?=$lastname?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Modificar</button>
                            <input type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>ArtistManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>