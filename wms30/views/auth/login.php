
<div class="container">
    <div class="header">
        <p><strong>EasyRock WMS Login</strong></p>
    </div>
    <div class="form">
        <?php echo form_open('auth/authenticate'); ?>
        <label for="username">Username:</label>
        <input class="inv" type="text" size="20" name="username"/>
        <br/>
        <label for="password">Password:</label>
        <input class="inv" type="password" size="20" name="password"/>
        <br/>
        <input type="submit" value="Login"/>
        </form>
    </div>
</div>
