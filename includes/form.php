<h1>Sign Up to continue reading</h1>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
    <fieldset>
        <legend>Register here!</legend>
        <div>
            <label for="fullName">Full Name</label>
            <input type="text" name="fullName" autofocus id="fullName" value="<?php if (isset($data['fullName'])) { echo htmlentities($data['fullName'], ENT_QUOTES, 'UTF-8'); } ?>"/>
            <?php if (isset($errors['fullName'])) { echo htmlentities($errors['fullName'], ENT_QUOTES, 'UTF-8'); } ?>
        </div>
        <div>
            <label for="userName">User Name</label>
            <input type="text" name="userName" id="userName" value="<?php if (isset($data['userName']) && !$usernameExist) { echo htmlentities($data['userName'], ENT_QUOTES, 'UTF-8'); } ?>"/>
            <?php if (isset($errors['userName'])) { echo htmlentities($errors['userName'], ENT_QUOTES, 'UTF-8'); } ?>
        </div>
        <div>
            <label for="contactByEmail">Email</label>
            <input type="text" name="email" id="contactByEmail" value="<?php if (isset($data['Email']) && !$emailExist) { echo htmlentities($data['Email'], ENT_QUOTES, 'UTF-8'); } ?>"/>
            <?php if (isset($errors['Email'])) { echo htmlentities($errors['Email'], ENT_QUOTES, 'UTF-8'); } ?>
        </div>
        <div>
            <label for="pwd">Password</label>
            <input type="password" name="pwd" id="pwd" value=""/>
            <?php if (isset($errors['Password']) || isset($pwdRequired)) { echo htmlentities($errors['Password'], ENT_QUOTES, 'UTF-8'); } ?>
        </div>
        <div>
            <label for="re-pwd">Re-enter password</label>
            <input type="password" name="re-pwd" id="re-pwd" value=""/>
            <?php if (isset($_POST['pwd']) && isset($errors['confirmPassword'])) { $pwdRequired = true; echo htmlentities($errors['confirmPassword'], ENT_QUOTES, 'UTF-8'); } ?>
        </div>
        <div>
            <input type="checkbox" title="Terms and Conditions" name="tac" id="tac" value="yes" <?php if (isset($_POST['tac']) && $_POST['tac'] == 'yes') { echo "checked"; } ?>/>
            <label for="tac">Tick this box to confirm you have read our <a href="#" id="tick">terms and conditions</a></label>
            <br><?php if (isset($errors['Checkbox'])) { echo htmlentities($errors['Checkbox'], ENT_QUOTES, 'UTF-8'); } ?>
            <br>
        </div>
        <div>
            <input type="submit" name="action" value="Submit"/>
        </div>
    </fieldset>
</form>

<section>
    <p>
        Already have an account? Back to the <a href="index.php">home page</a> to continue browsing or login
    </p>
</section>
