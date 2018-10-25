<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>EventManagement/addEvent" method="post">
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="eventName" required></td>
                    <td>Imágen: <input type="text" name="image"></td>
                </tr>
                <tr>
                    <td>Descripción: <textarea name="description" cols="30" rows="10" required></textarea></td>
                    <td>Categoría: 
                        <select name="category">
                            <?php
                                foreach ($categoryList as $value) {
                            ?>
                                <option value="<?=$value->getIdCategory()?>"><?=$value->getCategory()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button type="submit">Agregar</button>
                            <input type="submit" value="Volver" formaction="<?=FRONT_ROOT?>EventManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>