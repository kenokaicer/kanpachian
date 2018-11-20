<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>EventManagement/editEvent" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="idOldCategory" value="<?=$oldEvent->getIdEvent()?>" required>
            <table>
                <tr>
                    <td>Nombre: <input type="text" name="eventName" value="<?=$oldEvent->getEventName()?>"></td>
                    <td>Imágen: <input type="file" name="file" value="<?=$oldEvent->getImage()?>"></td>
                </tr>
                <tr>
                    <td>Descripción: <textarea name="description" cols="30" rows="10" required><?=$oldEvent->getDescription()?></textarea></td>
                    <td>Categoría: 
                        <select name="category">
                            <?php
                                foreach ($categoryList as $value) {
                                    if ($value->getIdCategory() == $oldEvent->getCategory()->getIdCategory()){
                                        ?>
                                        <option value="<?=$value->getIdCategory()?>" selected="selected"><?=$value->getCategoryName()?></option>
                                        <?php 
                                    }else{
                                        ?>
                                        <option value="<?=$value->getIdCategory()?>"><?=$value->getCategoryName()?></option>      
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input class="button" type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>EventManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>