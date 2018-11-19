<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>UserManagement/addUser" method="post">
            <table>
                <tr>
                    <td>Username: <input type="text" name="user" required></td>
                    <td>Password: <input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td>Email: <input type="email" name="email" ></td>
                    <td>Rol: 
                        <select name="role">
                            <?php
                                
                                foreach ($roles as $key => $role) {
                            ?>
                                <option value="<?=$key?>" <?php if($role=="Admin")echo "selected"?>><?=$role?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit" class="button" >Agregar</button>
                            <input type="submit"  class="button" value="Volver" formaction="<?=FRONT_ROOT?>UserManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>