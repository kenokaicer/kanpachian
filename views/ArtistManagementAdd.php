<div class="wrapper">
    <section>
        <form action="<?=BASE?>ArtistManagement/addArtist" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" required></td>
                    <td>Apellido: <input type="text" name="lastname" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Agregar</button>
                            <input type="submit" value="Volver" formaction="<?=BASE?>ArtistManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>