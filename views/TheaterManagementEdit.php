<div class="wrapper">
    <section>
        <form action="<?=BASE?>TheaterManagement/editTheater" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$name?>"></td>
                    <td>Ubicación: <input type="text" name="location" value="<?=$location?>"></td>
                    <td>Capacidad Máxima: <input type="number" name="maxCapacity" value="<?=$maxCapacity?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Modificar</button>
                            <input type="submit" value="Cancelar" formaction="<?=BASE?>TheaterManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>