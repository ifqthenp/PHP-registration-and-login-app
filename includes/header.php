<?php session_start();

/*
 * Prevent session fixation. Regenerate session ID every 30 seconds
 */
if (!isset($_SESSION['regenerated']) || $_SESSION['regenerated'] < (time() - 30))
{
    session_regenerate_id();
    $_SESSION['regenerated'] = time();
}

/*
 * Login user or assign error variable if login credentials not correct
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loginSubmit']))
{
    if (emailAndPasswordMatch($_POST['loginUsernameOrEmail'], $_POST['loginPassword']))
    {
        $_SESSION = retrieveSessionUserData($_POST['loginUsernameOrEmail']);
    }
    else
    {
        $loginErrorMessage = 'Login or password not correct'; // display in <legend> tag if login failed
    }
}

/*
 * Logout user
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logoutSubmit']))
{
    logout();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP FMA &#183; Andrei Bogomja</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
