<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>SeatTypeManagement/editSeatType" method="post">
            <input type="hidden" name="idOldSeatType" value="<?=$oldSeatType->getIdSeatType()?>">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="name" value="<?=$oldSeatType->getSeatTypeName()?>" required></td>
                    <td>Descripción: <textarea name="description" rows="5" cols="50" required><?=$oldSeatType->getDescription()?></textarea>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input class="button" type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>SeatTypeManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>