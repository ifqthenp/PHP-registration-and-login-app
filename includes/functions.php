<?php

/**
 * DEVELOPMENT ONLY. Print an array with print_r() function
 * @param $value
 */
function pp($value)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

/**
 * This function adds registered user to the file in 'userData/data.txt' after user provided data has been sanitized,
 * validated and checked for specified duplicate entries. Delimiter for entries is a string ' : ' (space colon space)
 * @param string $name     user's full name
 * @param string $userName user name
 * @param string $email    email
 * @param string $password password
 */
function registerUser($name, $userName, $email, $password)
{
    file_put_contents('userData/data.txt', "$name : $userName : $email : $password : \n", FILE_APPEND);
}

/**
 * This function opens user data file on the server and reads user data recorded previously
 * and compares it to the data provided by user in registration form. If user provides data that has been
 * registered before, then function returns TRUE
 * @param string $path     path to the data.txt file
 * @param int    $bit      number of the bit in the array created by explode function (needed to extract specific entry)
 * @param string $userData validated user data, ready to be recorded to the data.txt file
 * @return bool
 */
function userDataExist($path, $bit, $userData)
{
    $result = false;
    $handle = fopen($path, 'r');
    while (!feof($handle))
    {
        $readUserDataFile = fgets($handle);
        if (!feof($handle))
        {
            $registeredData = explode(' : ', $readUserDataFile);

            if ($registeredData[$bit] === $userData)
            {
                $result = true;
            }
        }
    }
    fclose($handle);
    return $result;
}

/**
 * This function checks if email and password provided by user match records in data file.
 * Email and password must match on a single line in the file.
 * @param string $usernameOrEmail
 * @param string $password
 * @return bool
 */
function emailAndPasswordMatch($usernameOrEmail, $password)
{
    $result = false;
    $file = fopen('userData/data.txt', 'r');
    while (!feof($file))
    {
        $registeredData = fgets($file);
        if (!feof($file))
        {
            $explodeRegisteredData = explode(' : ', $registeredData);

            $uNameValid = $explodeRegisteredData[1] === $usernameOrEmail;
            $emailValid = $explodeRegisteredData[2] === $usernameOrEmail;
            $passwordValid = $explodeRegisteredData[3] === $password;

            if (($uNameValid || $emailValid) && $passwordValid)
            {
                $result = true;
            }
        }
    }
    fclose($file);
    return $result;
}

/**
 * This function retrieves full name and user name after successful login and returns it to $_SESSION array
 * @param string $loginData user name or email
 * @return string full name of logged in user
 */
function retrieveSessionUserData($loginData)
{
    $_SESSION = array();
    if (is_file('userData/data.txt') && is_readable('userData/data.txt'))
    {
        $file = fopen('userData/data.txt', 'r');
        while (!feof($file))
        {
            $registeredData = fgets($file);
            if (!feof($file))
            {
                $explodeRegisteredData = explode(' : ', $registeredData);

                $uNameValid = $explodeRegisteredData[1] === $loginData;
                $emailValid = $explodeRegisteredData[2] === $loginData;

                if ($uNameValid || $emailValid)
                {
                    $_SESSION['fullName'] = $explodeRegisteredData[0];
                    $_SESSION['userName'] = $explodeRegisteredData[1];
                }
            }
        }
        fclose($file);
    }
    return $_SESSION;
}

/**
 * This function returns user's full name if successfully logged in or returns 'stranger' otherwise
 * @return string
 */
function displayUserFullName()
{
    if (isset($_SESSION['fullName']) && !empty($_SESSION['fullName']))
    {
        return $_SESSION['fullName'];
    }
    else
    {
        return 'stranger';
    }
}

/**
 * This function logs out user from the current session
 */
function logout()
{
    session_destroy();
    $_SESSION = array();
    header('Location: ' . $_SERVER['PHP_SELF']);
}
