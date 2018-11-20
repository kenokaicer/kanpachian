<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>UserManagement/editUser" method="post">
        <input type="hidden" name="idOldUser" value="<?=$oldUser->getIdUser()?>">
            <table>
                <tr>
                    <td>Categoría: <input type="text" name="name" value="<?=$oldUser->getUsername()?>"></td>
                    <td>Password: (No es posible, sal & hash)</td>
                </tr>
                <tr>
                    <td>Email: <input type="email" name="email" value="<?=$oldUser->getEmail()?>"></td>
                    <td>Rol: 
                        <select name="role">
                            <?php
                                $roles = Role::getConstants();
                                foreach ($roles as $key => $role) {
                            ?>
                                <option value="<?=$key?>" selected="<?php if($key==$oldUser->getRole())echo "selected"?>"><?=$role?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button class="button" type="submit">Modificar</button>
                            <input type="submit" class="button" value="Cancelar" formaction="<?=FRONT_ROOT?>UserManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>