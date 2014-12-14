<?php
/**
 * Config-file for navigation bar.
 *
 */

// Get menu items

$items = array();
$appendItems = array();
$menuItems = array();

$items = [
    // Home
    'home'  => [
        'text'  => '<i class="fa fa-home"></i> Home',
        'url'   => $this->di->get('url')->create(''),
        'title' => 'Home',
    ],

    // Home
    'about'  => [
        'text'  => '<i class="fa fa-info"></i> About',
        'url'   => $this->di->get('url')->create('about'),
        'title' => 'About PostPile',
    ],

    // Questions
    'questions'  => [
        'text'  => '<i class="fa fa-th-list"></i> Questions',
        'url'   => $this->di->get('url')->create('questions'),
        'title' => 'Questions',
        'mark-if-parent-of' => 'questions',
    ],

    // Tags
    'tags'  => [
        'text'  => '<i class="fa fa-tags"></i> Tags',
        'url'   => $this->di->get('url')->create('tags'),
        'title' => 'Tags',
        'mark-if-parent-of' => 'tags',
    ],

    // Tags
    'users'  => [
        'text'  => '<i class="fa fa-users"></i> Users',
        'url'   => $this->di->get('url')->create('users'),
        'title' => 'Users',
        'mark-if-parent-of' => 'users',
    ],
];

if($this->di->session->get('user')) {

    $user = $this->di->session->get('user');

    $appendItems = 
    // Logout
    [
        'ask' => [
            'text' => '<i class="fa fa-question"></i> Ask',
            'url' => $this->di->get('url')->create('ask'),
            'title' => 'Ask a Question',
        ],
        'profile'  => [
            'text'  => "<i class='fa fa-caret-down'></i> <i class='fa fa-user'></i>  ".$user[0]->username,
            'url'   => $this->di->get('url')->create('users/self'),
            'title' => 'User Profile',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'logout'    => [
                        'text'  => '<i class="fa fa-sign-out"></i> Logout',
                        'url'   => $this->di->get('url')->create('users/logout'),
                        'title' => 'Logout'
                    ],
                ],
            ],
        ],
    ];
    }
    else {
        $appendItems = 
        [
            // Register
            'register'  => [
                'text'  => '<i class="fa fa-pencil-square-o"></i> Register',
                'url'   => $this->di->get('url')->create('register'),
                'title' => 'Register',
            ],
            // Login
            'login'  => [
                'text'  => '<i class="fa fa-sign-in"></i> Login',
                'url'   => $this->di->get('url')->create('login'),
                'title' => 'Login',
            ],
    ];
}
$menuItems = array_merge($items, $appendItems);

return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => $menuItems,
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },

    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },

    // Callback to create the urls
    /*'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },*/
];
