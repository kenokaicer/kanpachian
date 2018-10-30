<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>UserManagement/addUser" method="post">
            <table>
                <tr>
                    <td>Username: <input type="text" name="user" required></td>
                    <td>Password: <input type="password" name="password"></td>
                </tr>
                <tr>
                    <td>Email: <input type="email" name="email"></td>
                    <td>Rol: 
                        <select name="role">
                            <?php
                                
                                foreach ($roles as $key => $role) {
                            ?>
                                <option value="<?=$key?>"><?=$role?></option>      
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
                            <input type="submit" value="Volver" formaction="<?=FRONT_ROOT?>UserManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>