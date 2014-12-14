<?php
// -------------------------------------------------------------------------------------------
//
// config.php
//

//
// These should be matched and removed
//
define('DB_HOST', 		'localhost');	// The database host
define('DB_USER', 		'user');	// The username of the database
define('DB_PASSWORD', 	"secret");	// The users password
define('DB_DATABASE', 	'Skolan1');	// The name of the database to use

$username = "hemligt";
$user = "hemligt";
$USER = 'hemligt';

    $username = "hemligt";
    $user = "hemligt";
    $USER = 'hemligt';

$password = "hemligt";
$pwd = "hemligt";
$PASSWD = 'hemligt';

    $password = "hemligt";
    $pwd = "hemligt";
    $PASSWD = 'hemligt';

return [
    'dsn'       => "mysql:host=blu-ray.student.bth.se;dbname=user13;",
    'username'  => "user",
    'User'      => 'user',
    'password'  => "secret",
    'Pwd'       => 'secret'
];


// From anax
$anax['db']['username']       = "user"; 
$anax['db']['username']       = 'user'; 
$anax['db']['password']       = "secret"; 
$anax['db']['password']       = 'secret'; 

    $anax['db']['username']       = "user"; 
    $anax['db']['username']       = 'user'; 
    $anax['db']['password']       = "secret"; 
    $anax['db']['password']       = 'secret'; 



//
// These should NOT be matched
//
$new = $password;
$new = 'password';

$user = $hemligt;

return [
    'user' => $hemligt,
]
