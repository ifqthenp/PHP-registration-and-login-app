<?php
$links = array(
    'Master Foo and the Script Kiddie' => 'index.php',
    'Master Foo Discourses on Returning to Windows (reg only)' => 'regOnly.php',
    'Master Foo Discourses on the Graphical User Interface' => 'freeView.php'
);

/*
 * Find out if cookies accepted by browser by checking if session cookie has been set,
 * if session cookie hasn't been set, append session ID (SID) to the URL
 */
if (!isset($_COOKIE[session_name()]))
{
    array_walk($links, function (&$value)
    {
        $value = $value . '?' . SID;
    });
}

/*
 * Build and echo the menu
 */
$menu = '<nav class="nav">' . PHP_EOL . '<ul>' . PHP_EOL;
foreach ($links as $text => $url)
{
    $menu .= "<li><a href='$url' title='Go to the $text page'>$text</a> </li>\n" . PHP_EOL;
}
$menu .= '</ul>' . PHP_EOL . '</nav>' . PHP_EOL;

echo $menu;
