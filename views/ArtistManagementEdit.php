<div class="wrapper">
    <section>
        <form action="<?=BASE?>ArtistManagement/editArtist" method="post">
            <input type="hidden" name="id" value="<?=$id?>">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$name?>"></td>
                    <td>Apellido: <input type="text" name="lastname" value="<?=$lastname?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Modificar</button>
                            <input type="submit" value="Cancelar" formaction="<?=BASE?>ArtistManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>