<?php include 'includes/functions.php';

/*
 * This php file validates user input provided in registration form
 */

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['action'])))
{
    $form_is_submitted = false;
    $errors_detected = false;

    $data = array();
    $errors = array();

    /*
     * Validate full name:
     * must contain only alphabetic characters
     * must contain at least one space
     * must be 100 characters long or less
     */
    if (!(filter_has_var(INPUT_POST, 'fullName') && (strlen(filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING)) > 0)))
    {
        $errors_detected = true;
        $errors['fullName'] = 'FULL NAME IS REQUIRED';
    }
    else
    {
        if (filter_has_var(INPUT_POST, 'fullName') && (strlen(filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING)) <= 100))
        {
            $_POST['fullName'] = trim($_POST['fullName']);

            if (count(explode(' ', $_POST['fullName'])) > 1) // Check if name contains at least one space character
            {
                // Now make sure each of the words separated by space contains only alphabetic characters
                $lettersOnly = false;
                $countLettersOnly = 0;

                foreach (explode(' ', $_POST['fullName']) as $name)
                {
                    if (ctype_alpha($name))
                    {
                        $lettersOnly = true;
                        $countLettersOnly++;
                    }
                    else
                    {
                        $lettersOnly = false;
                    }
                }

                if (($lettersOnly === true) && ($countLettersOnly === count(explode(' ', $_POST['fullName']))))
                {
                    $data['fullName'] = $_POST['fullName'];
                }
                else
                {
                    $errors_detected = true;
                    $errors['fullName'] = 'NAME MUST ONLY CONTAIN ALPHABETIC CHARACTERS';
                }
            }
            else
            {
                $errors_detected = true;
                $errors['fullName'] = 'ENTER FULL NAME SEPARATED BY SPACES';
            }
        }
        else
        {
            $errors_detected = true;
            $errors['fullName'] = 'SUBMITTED NAME IS TOO LONG';
        }
    }

    /*
     * Validate username:
     * must contain only alphabetic characters and digits
     * no spaces allowed
     * must be 15 characters long or less
     */
    if (!(filter_has_var(INPUT_POST, 'userName') && (strlen(filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING)) > 0)))
    {
        $errors_detected = true;
        $errors['userName'] = 'USER NAME IS REQUIRED';
    }
    else
    {
        if (filter_has_var(INPUT_POST, 'userName') && (strlen(filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING)) < 16))
        {
            $_POST['userName'] = trim($_POST['userName']);

            if (count(explode(' ', $_POST['userName'])) > 1)
            {
                $errors_detected = true;
                $errors['userName'] = 'USERNAME MUST NOT CONTAIN SPACES';
            }
            else
            {
                if (ctype_alnum($_POST['userName']) || ctype_alpha($_POST['userName']))
                {
                    $data['userName'] = $_POST['userName'];
                }
                else
                {
                    $errors_detected = true;
                    $errors['userName'] = 'USER NAME MUST ONLY CONTAIN LETTERS OR DIGITS';
                }
            }
        }
        else
        {
            $errors_detected = true;
            $errors['userName'] = 'USER NAME IS TOO LONG (try 15 characters or less)';
        }
    }

    /*
     * Validate email
     * FILTER_VALIDATE_EMAIL filter checks the email provided by user according to RFC 5321 standards
     */
    if (!(filter_has_var(INPUT_POST, 'email') && (strlen(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)) > 0)))
    {
        $errors_detected = true;
        $errors['Email'] = 'EMAIL IS REQUIRED';
    }
    else
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false)
        {
            $errors_detected = true;
            $errors['Email'] = 'SUBMITTED EMAIL IS INVALID';
        }
        else
        {
            $data['Email'] = $_POST['email'];
        }
    }

    /*
     * Validate password:
     * minimum 8, maximum 64 characters
     * must contain at least one digit or one alphabetic character
     * optional characters: `~!@#$%^&*()_-+={}[]\|:;"'<>,.?/
     * not valid: spaces
     * not valid: ASCII control characters ( < 32 )
     * not valid: ASCII extended characters ( > 126 )
     * confirm password by matching it with another user input
     */
    $passwordInputIsNotEmpty = strlen(filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) > 0;
    $passwordConfirmationInputIsNotEmpty = strlen(filter_input(INPUT_POST, 're-pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) > 0;

    // Check if password has been submitted
    if (!(filter_has_var(INPUT_POST, 'pwd') && $passwordInputIsNotEmpty))
    {
        $errors_detected = true;
        $errors['Password'] = 'PASSWORD IS REQUIRED';
    }
    else
    {
        // Check if password confirmation has been submitted
        if (!(filter_has_var(INPUT_POST, 're-pwd') && $passwordConfirmationInputIsNotEmpty))
        {
            $errors_detected = true;
            $errors['confirmPassword'] = 'PLEASE CONFIRM PASSWORD';
            $errors['Password'] = 'RE-ENTER PASSWORD';
        }
        else
        {
            // Sanitize all high ANSI characters (ASCII < 32 and ASCII > 126)
            // if pre-sanitized strlen($_POST['pwd']) === strlen(sanitized string), this means that submitted password does not contain restricted ASCII characters
            $noRestrictedASCII = strlen($_POST['pwd']) === strlen(filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
            if ($noRestrictedASCII)
            {
                $minPasswordLen = strlen(filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) > 7;
                $maxPasswordLen = strlen(filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) < 65;

                // Check password length
                if ($minPasswordLen && $maxPasswordLen)
                {
                    $_POST['pwd'] = filter_input(INPUT_POST, 'pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

                    $lower = strtolower($_POST['pwd']) !== $_POST['pwd'];
                    $upper = strtoupper($_POST['pwd']) !== $_POST['pwd'];
                    $alNum = ctype_alnum($_POST['pwd']);
                    $specialChar = ctype_graph($_POST['pwd']);

                    // Check if password contains at least one uppercase, one lowercase and one digit
                    // Special characters are optional, excluding spaces
                    if (($alNum && ($lower && $upper)) || ($specialChar && ($lower && $upper)))
                    {
                        $data['Password'] = $_POST['pwd'];
                    }
                    else
                    {
                        $errors_detected = true;
                        $errors['Password'] = 'PASSWORD MUST CONTAIN AT LEAST ONE DIGIT AND ONE LETTER';
                    }
                }
                else
                {
                    $errors_detected = true;
                    $errors['Password'] = 'PASSWORD MUST BE BETWEEN 8 AND 32 CHARACTERS LONG';
                }
            }
            else
            {
                $errors_detected = true;
                $errors['Password'] = 'PASSWORD MUST CONTAIN ONLY VISIBLE PRINTABLE CHARACTERS';
            }
        }
    }

    /*
     * Validate confirmation password
     * Same requirements as for main password validation form above
     */

    // Check if main password has been submitted
    // if not, then confirmation password form is not going through validation process
    if (!(filter_has_var(INPUT_POST, 'pwd') && $passwordInputIsNotEmpty))
    {
        $errors_detected = true;
    }
    else
    {
        // Sanitize all high ANSI characters (ASCII < 32 and ASCII > 126)
        // if pre-sanitized strlen($_POST['re-pwd']) === strlen(sanitized string), this means that submitted password does not contain restricted ASCII characters
        $noRestrictedASCII = strlen($_POST['re-pwd']) === strlen(filter_input(INPUT_POST, 're-pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
        if ($noRestrictedASCII)
        {
            $minPasswordLen = strlen(filter_input(INPUT_POST, 're-pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) > 7;
            $maxPasswordLen = strlen(filter_input(INPUT_POST, 're-pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)) < 65;

            // Check password length
            if ($minPasswordLen && $maxPasswordLen)
            {
                $_POST['re-pwd'] = filter_input(INPUT_POST, 're-pwd', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

                $lower = strtolower($_POST['re-pwd']) !== $_POST['re-pwd'];
                $upper = strtoupper($_POST['re-pwd']) !== $_POST['re-pwd'];
                $alNum = ctype_alnum($_POST['re-pwd']);
                $specialChar = ctype_graph($_POST['re-pwd']);

                // Check if password contains at least one uppercase, one lowercase and one digit
                // Special characters are optional, excluding spaces
                if (($alNum && ($lower && $upper)) || ($specialChar && ($lower && $upper)))
                {
                    $data['confirmPassword'] = $_POST['re-pwd'];
                }
                else
                {
                    $errors_detected = true;
                }
            }
            else
            {
                $errors_detected = true;
            }
        }
        else
        {
            $errors_detected = true;
        }
    }

    /*
     * Validate Terms and Conditions check box
     */
    if (filter_has_var(INPUT_POST, 'tac'))
    {
        if ($_POST['tac'] === 'yes')
        {
            $form_is_submitted = true;
        }
        else
        {
            $errors_detected = true;
            $errors['Checkbox'] = 'INVALID CHECKBOX VALUE SUBMITTED';
        }
    }
    else
    {
        $errors_detected = true;
        $errors['Checkbox'] = 'READ TERMS & CONDITIONS AND TICK THE BOX';
    }

    /*
     * Check if user data file exists and if it is, check if email and user name are in the data file
     * If they have been already registered, do not proceed with user registration
     * Display error that email or user name exist in the data file. All user names and emails must be unique
     */
    $emailValidated = isset($data['Email']);
    $usernameValidated = isset($data['userName']);
    $dataFileExist = is_file('userData/data.txt');
    $dataFileReadable = is_readable('userData/data.txt');
    $usernameExist = false;
    $emailExist = false;

    // check if user name already exist in data file
    if (($dataFileExist && $dataFileReadable) && $usernameValidated)
    {
        if (userDataExist('userData/data.txt', 1, $data['userName']))
        {
            $usernameExist = true;
            $errors_detected = true;
            $errors['userName'] = 'USER NAME HAS BEEN ALREADY REGISTERED';
        }
    }

    // check if email already exist in data file
    if (($dataFileExist && $dataFileReadable) && $emailValidated)
    {
        if (userDataExist('userData/data.txt', 2, $data['Email']))
        {
            $emailExist = true;
            $errors_detected = true;
            $errors['Email'] = 'EMAIL HAS BEEN ALREADY REGISTERED';
        }
    }

    /*
     * If no errors detected and terms & condition box is checked,
     * write user credentials to the 'userData/data.txt'
     */
    if (($form_is_submitted === true) && ($errors_detected === false))
    {
        // Check if folder exist and it is writable
        if (file_exists('userData') && is_writable('userData'))
        {
            // Write user details to the file using user-defined function
            registerUser($data['fullName'], $data['userName'], $data['Email'], $data['Password']);

            // clear user data and errors from the array
            $data = array();
            $errors = array();

            // redirect to the confirmation page
            header('Location: confirm.php');
            exit;
        }
        else
        {
            $data = array();
            $errors = array();
            echo '<h1>' . 'Errors detected, please try again' . '</h1>';
        }
    }
}

include 'includes/header.php';
include 'includes/form.php';
include 'includes/footer.php';
