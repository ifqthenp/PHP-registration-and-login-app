<?php include 'includes/functions.php';
include 'includes/header.php';
include 'includes/login.php';
include 'includes/menu.php';

$welcome = '<h1>Welcome, ' . displayUserFullName() . '!</h1>';
echo $welcome;

$registeredOnly = '<section id="content">
    <h3>Master Foo Discourses on Returning to Windows</h3>
    <p>A student said: &ldquo;We have learned that Unix is not just an operating system, but also a style of
     approaching problems.&rdquo;</p>
    <p>Master Foo nodded in agreement.</p>
    <p>The student continued: &ldquo;Then, the Great Way of Unix can be applied on other operating systems?&rdquo;</p>
    <p>Master Foo sat silent for a moment, then said: &ldquo;In every operating system there is a path to the Great Way,
     if only we can find it.&rdquo;</p>
    <p>The student continued: &ldquo;What, then, of Windows? It is preinstalled on most computers, and though its tools
     are mostly far inferior, they are easy to use for beginners. Surely, Windows users could benefit from the Unix philosophy.&rdquo;</p>
    <p>Master Foo nodded again.</p>
    <p>The student said: &ldquo;How, then, are those enlightened in the Unix Way to return to the Windows world?&rdquo;</p>
    <p>Master Foo said: &ldquo;To return to Windows, you have but to boot it up.&rdquo;</p>
    <p>The student said, growing agitated: &ldquo;Master Foo, if it is so easy, why are there so many monolithic and
     broken software packages for Windows? Elegant software should also be possible with a GUI and fancy colors,
     but there is little evidence that this occurs. What becomes of an enlighted one who returns to Windows?&rdquo;</p>
    <p>Master Foo: &ldquo;A broken mirror never reflects again; fallen flowers never go back to the old branches.&rdquo;</p>
    <p>Upon hearing this, all present were enlightened.</p>
</section>';

if ($sessionFullNameValid && $sessionUserNameValid)
{
    echo $registeredOnly;
}
else
{
    echo '<p>' . 'You need to be logged in to view this page' . '</p>';
}

include 'includes/footer.php';
