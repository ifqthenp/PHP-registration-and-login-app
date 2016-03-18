<?php
/*
 * Display login form
 */
if (isset($loginErrorMessage))
{
    $loginMessage = $loginErrorMessage;
}
else
{
    $loginMessage = 'Login or Register'; // default login message in <legend> tag
}

$loginFormOutput = '<form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post">
    <fieldset>
        <legend>' . $loginMessage . '</legend>
        <label for="loginUsernameOrEmail">Username / E-mail</label>
        <input type="text" name="loginUsernameOrEmail" autofocus id="loginUsernameOrEmail">
        <label for="loginPassword">Password</label>
        <input type="password" name="loginPassword" id="loginPassword">
        <input type="submit" name="loginSubmit" value="Login">

        <p>Do not have an account? <a href="register.php">Register here</a></p>
    </fieldset>
</form>';

/*
 * Validate $_SESSION array key values and if valid display 'Login successful' form
 * otherwise display 'Login or register' form
 */
$sessionFullNameValid = isset($_SESSION['fullName']) && !empty($_SESSION['fullName']);
$sessionUserNameValid = isset($_SESSION['userName']) && !empty($_SESSION['userName']);

if ($sessionFullNameValid && $sessionUserNameValid)
{
    $userLoggedIn = '<form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post">
    <fieldset>
        <legend>Login successful</legend>
        <p>You are now logged in as ' . $_SESSION['userName'] . '</p>
        <p>Press the button to <input type="submit" name="logoutSubmit" value="Logout"></p>
    </fieldset>
    </form>';

    echo $userLoggedIn;
}
else
{
    echo $loginFormOutput;
}

