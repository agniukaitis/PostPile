<?php

namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize() {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }

    public function indexAction() {
        $this->di->theme->setTitle('Users');

        $all = $this->users->findAll();
 
        $this->views->add('users/view-all', [
            'users' => $all,
            'header' => "Users",
        ], 'main-wide');
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);

        if($user == null) {
          $this->redirectTo('users');
        }

        $tools = false;
        if($this->di->session->get('user') != null) {
            if($id == $this->di->session->get('user')[0]->id) {
                $tools = true;
            }
        }

        $questions = $this->di->dispatcher->forward([
            'controller' => 'questions',
            'action'     => 'getuserquestions',
            'params'     => [$id],
        ]);
        $user->questions = $questions;

        $answers = $this->di->dispatcher->forward([
            'controller' => 'questions',
            'action'     => 'getuseranswers',
            'params'     => [$id],
        ]);
        $user->answers = $answers;

        $this->theme->setTitle("User " . $user->username);
        $this->views->add('users/view', [
            'user' => $user,
            'tools' => $tools,
            'header' => $user->username,
        ], 'main-wide');
    }

    public function mostactiveAction()
    {
        $users = $this->di->dispatcher->forward([
            'controller' => 'questions',
            'action'     => 'mostactive',
        ]);

        if($users == null) {
            return $users;
        }

        foreach($users as $user) {
            $data = $this->users->query()
                ->where('id = ?')
                ->execute([$user->userId]);

            $user->username = $data[0]->username;
            $user->registered = $data[0]->registered;
            $user->email = $data[0]->email;

        }

        return $users;
    }

    public function registerAction() {
        $this->di->theme->setTitle('Register');

       if($this->di->session->get('user') != null) {
          $this->redirectTo('logout');
        }

        $form = $this->form->create([], [
            'username' => [
                'type'        => 'text',
                'label'       => 'Username',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['username']['value']) ? $_SESSION['form-save']['username']['value'] : null,
            ],
            'email' => [
                'type'        => 'email',
                'label'       => 'E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['email']['value']) ? $_SESSION['form-save']['email']['value'] : null,
            ],
            'emailCnfrm' => [
                'type'        => 'email',
                'label'       => 'Retype E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['emailCnfrm']['value']) ? $_SESSION['form-save']['emailCnfrm']['value'] : null,
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['password']['value']) ? $_SESSION['form-save']['password']['value'] : null,
            ],
            'passwordCnfrm' => [
                'type'        => 'password',
                'label'       => 'Retype Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['passwordCnfrm']['value']) ? $_SESSION['form-save']['passwordCnfrm']['value'] : null,
            ],
            'register' => [
                'type'      => 'submit',
                'class'     => 'button success-button',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'button default-button',
            ]
        ]);

        // Prepare the page content
        $this->views->add('users/form', [
            'header' => 'Register',
            'main' => $form->getHTML(),
        ], 'main-wide');

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            if($this->users->findBy('username', $_SESSION['form-save']['username']['value'])) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The username is already in use!']);
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['email']['value'] != $_SESSION['form-save']['emailCnfrm']['value']) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The e-mail addresses did not match!']);
                $this->redirectTo();
            }
            else if(strlen($_SESSION['form-save']['password']['value']) < 6) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The password is too short!']);
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['password']['value'] != $_SESSION['form-save']['passwordCnfrm']['value']) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The passwords did not match!']);
                $this->redirectTo();
            }

            $now = date("Y-m-d H:i:s");
            
            $res = $this->users->save([
                'username' => $_SESSION['form-save']['username']['value'],
                'email' => $_SESSION['form-save']['email']['value'],
                'password' => md5(strtolower(trim($_SESSION['form-save']['password']['value']))),
                'registered' => $now,
            ]);

            if($res) {
                $this->di->informer->setMessage(['type' => 'success', 'message' => 'Success! Your account has been created. You may now login.']);
                unset($_SESSION['form-save']);
            }

            $this->redirectTo('');
        } else if ($status === false) {   
            $this->di->informer->setMessage(['type' => 'error', 'message' => 'We were unable to process your registration. Please try again.']);
            $this->redirectTo();
        }
    }

    public function loginAction() {
        $this->di->theme->setTitle('Login');

        if($this->di->session->get('user') != null) {
            $this->redirectTo('logout');
        }

        $form = $this->form->create([], [
            'username' => [
                'type'        => 'text',
                'label'       => 'Username',
                'required'    => true,
                'autofocus'   => true,
                'validation'  => ['not_empty'],
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'login' => [
                'type'      => 'submit',
                'class'     => 'button success-button',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ]
        ]);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            $user = $this->users->query()
                ->where('username = ?')
                ->andWhere('password = ?')
                ->execute([$_SESSION['form-save']['username']['value'], md5(strtolower(trim($_SESSION['form-save']['password']['value'])))]);

            if($user == null) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'Incorrect username and/or password']);
                $this->redirectTo();
            }

            $this->di->session->set('user', $user);

            unset($_SESSION['form-save']);
            $this->redirectTo('');

        } else if ($status === false) {   
            $this->di->informer->setMessage(['type' => 'error', 'message' => 'Unable to login. Please try again.']);
            $this->redirectTo();
        }

        // Prepare the page content
        $this->views->add('users/form', [
            'header' => 'Login',
            'main' => $form->getHTML(),
        ], 'main-wide');
    }

    public function logoutAction() {
        if($this->di->session->get('user') != null) {
            $this->di->session->set('user', null);
        }
        $this->redirectTo('');
    }

    public function selfAction()
    {
        if($this->di->session->get('user') != null) {
            $user = $this->di->session->get('user');
            $this->redirectTo('users/id/'.$user[0]->id);
        }
        $this->redirectTo('users');
    }

    /**
     * Account details.
     *
     * @return void
     */
    public function editAction() 
    {        
        if($this->di->session->get('user') == null) {
            $this->redirectTo('');
        }

        $this->di->theme->setTitle('Edit profile');

        $user = $this->di->session->get('user');

        $form = $this->form->create([], [
            'username' => [
                'type'        => 'text',
                'label'       => 'Username',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['username']['value']) ? $_SESSION['form-save']['username']['value'] : $user[0]->username,
            ],
            'email' => [
                'type'        => 'email',
                'label'       => 'E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['email']['value']) ? $_SESSION['form-save']['email']['value'] : $user[0]->email,
            ],
            'emailCnfrm' => [
                'type'        => 'email',
                'label'       => 'Retype E-mail Address',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => isset($_SESSION['form-save']['emailCnfrm']['value']) ? $_SESSION['form-save']['emailCnfrm']['value'] : $user[0]->email,
            ],
            'save' => [
                'type'      => 'submit',
                'class'     => 'button success-button',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'button default-button',
            ]
        ]);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            if($this->users->findBy('username', $_SESSION['form-save']['username']['value']) && $user[0]->username != $_SESSION['form-save']['username']['value']) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'Username is already in use!']);
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['email']['value'] != $_SESSION['form-save']['emailCnfrm']['value']) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The e-mail addresses did not match!']);
                $this->redirectTo();
            }

            // Collect data and unset the session variable
            $data['username'] = $_SESSION['form-save']['username']['value'];
            $data['email'] = $_SESSION['form-save']['email']['value'];
            $data['id'] = $user[0]->id;

            // Save updated user data
            $res = $this->users->save($data);          
            if($res) {
                $this->di->informer->setMessage(['type' => 'success', 'message' => 'Success! Your profile has been updated.']);
                unset($_SESSION['form-save']);
                unset($_SESSION['user']);

                $newUser = $this->users->query()
                    ->where('id = ?')
                    ->execute([$user[0]->id]);
                $this->di->session->set('user', $newUser);
                $this->redirectTo('users/account');
            }
         
        } else if ($status === false) {     
            $this->di->informer->setMessage(['type' => 'error', 'message' => 'Something went wrong. Please try again.']);
            $this->redirectTo();
        }

        // Prepare the page content
        $this->views->add('users/form', [
            'header' => "Edit Profile",
            'userId' => $user[0]->id,
            'main' => $form->getHTML(),
        ], 'main-wide');
    }

    public function passwordAction()
    {
        if($this->di->session->get('user') == null) {
            $this->redirectTo('');
        }

        $this->di->theme->setTitle('Change password');

        $user = $this->di->session->get('user');

        $form = $this->form->create([], [
            'passwordOld' => [
                'type'        => 'password',
                'label'       => 'Old Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['passwordOld']['value']) ? $_SESSION['form-save']['passwordOld']['value'] : null,
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'New Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['password']['value']) ? $_SESSION['form-save']['password']['value'] : null,
            ],
            'passwordCnfrm' => [
                'type'        => 'password',
                'label'       => 'Retype New Password',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => isset($_SESSION['form-save']['passwordCnfrm']['value']) ? $_SESSION['form-save']['passwordCnfrm']['value'] : null,
            ],
            'save' => [
                'type'      => 'submit',
                'class'     => 'button success-button',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'button default-button',
            ]
        ]);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            $res = $this->users->query()
                ->where('id = ?')
                ->andWhere('password = ?')
                ->execute([$user[0]->id, md5(strtolower(trim($_SESSION['form-save']['passwordOld']['value'])))]);

            if($res == null) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'Incorrect password']);
                $this->redirectTo();
            }
            else if(strlen($_SESSION['form-save']['password']['value']) < 6) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The new password is too short!']);
                $this->redirectTo();
            }
            else if($_SESSION['form-save']['password']['value'] != $_SESSION['form-save']['passwordCnfrm']['value']) {
                $this->di->informer->setMessage(['type' => 'error', 'message' => 'The passwords did not match!']);
                $this->redirectTo();
            }

            // Collect data and unset the session variable
            $data['password'] = md5(strtolower(trim($_SESSION['form-save']['password']['value'])));
            $data['id'] = $user[0]->id;

            // Save updated user data
            $res = $this->users->save($data);
            if($res) {
                $this->di->informer->setMessage(['type' => 'success', 'message' => 'Success! Your password has been changed. Use it the next time you log in.']);
                unset($_SESSION['form-save']);
                $this->redirectTo('users/account');
            }
         
        } else if ($status === false) {     
            $this->di->informer->setMessage(['type' => 'error', 'message' => 'We were unable to change your password. Please try again.']);
            $this->redirectTo();
        }

        // Prepare the page content
        $this->views->add('users/form', [
            'header' => "Change Password",
            'userId' => $user[0]->id,
            'main' => $form->getHTML(),
        ], 'main-wide');
    }

    /**
     * Reset and setup database tabel with default users.
     *
     * @return void
     */
    public function setupAction() 
    {
        // Enable the following line once you setup the user databse for the first time in order to protect it from getting restored by anyone.
        /*if($this->di->session->get('users')[0]->username != "Admin") {
            $this->di->informer->setMessage(['type' => 'warning', 'message' => 'Warning! You do not have permission to do that.']);
            $this->redirectTo('');
        }*/

        $table = [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'username' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'registered' => ['datetime'],
        ];

        $res = $this->users->setupTable($table);

        // Add some users 
        $now = date("Y-m-d H:i:s");
 
        $this->users->create([
            'username' => 'Admin',
            'email' => 'admin@postpile.com',
            'password' => md5(strtolower(trim('admin'))),
            'registered' => $now,
        ]);

        $this->informer->setMessage(array(
            'type' => 'success',
            'message' => 'User database has been restored!'
        ));
     
        $url = $this->url->create('users');
        $this->response->redirect($url);
    }
 
}
