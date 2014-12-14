<?php 
/**
 * This is a Anax frontcontroller.
 *
 */

// Get environment & autoloader.
require __DIR__.'/config_with_app.php';

if($di->session->get('flash')) {
    $app->views->add('default/flash', ['flash' => $di->informer->getMessage()], 'flash');
}


// Home route
$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Home");

    $questions = $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'latest',
    ]);

    $users = $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'mostactive',
    ]);

    $tags = $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'mostused',
    ]);

    $app->views->add('default/main', [
        'questions' => $questions,
        'users' => $users,
        'tags' => $tags,
    ], 'main-wide');

});

// About route
$app->router->add('about', function() use ($app) {

    $app->theme->setTitle("About");

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
    $app->views->add('default/about', [
        'header' => 'About <span class="postpile"><span class="first-p">P</span><span class="first-after">ost</span><span class="second-p">P</span><span class="second-after">ile</span></span>',
        'content' => $content,
    ], 'main-wide');

});

// Register route
$app->router->add('register', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'register',
    ]);

});

// Login route
$app->router->add('login', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'login',
    ]);

});

// Logout route
$app->router->add('logout', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'logout',
    ]);

});

// Ask route
$app->router->add('ask', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'ask',
    ]);

});

// Source route
/*$app->router->add('source', function() use ($app) {
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Source");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('default/source', [
        'content' => $source->View(),
    ], 'main-wide');
});*/

// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();
