<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>EventManagement/addEvent" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="eventName" required></td>
                    <td>Imágen: <input id="file" type="file" name="file"></td>

                </tr>
                <tr>
                    <td>Descripción: <textarea name="description" cols="30" rows="10" required></textarea></td>
                    <td>Categoría: 
                        <select name="category">
                            <?php
                                foreach ($categoryList as $value) {
                            ?>
                                <option value="<?=$value->getIdCategory()?>"><?=$value->getCategoryName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button class="button" type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>EventManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>