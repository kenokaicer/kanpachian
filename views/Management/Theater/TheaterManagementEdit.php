<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>TheaterManagement/editTheater" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$name?>"></td>
                    <td>Ubicación: <input type="text" name="location" value="<?=$location?>"></td>
                    <td>Capacidad Máxima: <input type="number" name="maxCapacity" value="<?=$maxCapacity?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input class="button" type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>TheaterManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>