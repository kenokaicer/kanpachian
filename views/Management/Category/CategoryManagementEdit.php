<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>CategoryManagement/editCategory" method="post">
        <input type="hidden" name="idOldCategory" value="<?=$oldCategory->getIdCategory()?>">
            <table>
                <tr>
                    <td>Categoría: <input type="text" name="name" value="<?=$oldCategory->getCategoryName()?>"></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input class="button" type="submit" class="button" value="Cancelar" formaction="<?=FRONT_ROOT?>CategoryManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>