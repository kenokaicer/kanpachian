<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>CategoryManagement/editCategory" method="post">
        <input type="hidden" name="idOldCategory" value="<?=$oldCategory->getIdCategory()?>" required>
            <table>
                <tr>
                    <td>Categoría: <input type="text" name="name" value="<?=$oldCategory->getCategoryName()?>"></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button type="submit">Modificar</button>
                            <input class="button" type="submit" value="Cancelar" formaction="<?=FRONT_ROOT?>CategoryManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>