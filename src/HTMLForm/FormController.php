<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class FormController
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Index action.
     *
     */
    public function indexAction($key)
    {
        //$this->di->session(); // Will load the session service which also starts the session

        $form = $this->di->form->create([], [
            'redirect' => [
                'type'        => 'hidden',
                'value'       => $this->di->url->create($key),
            ],
            'pageKey' => [
                'type'        => 'hidden',
                'value'       => $key,
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Name:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'mail' => [
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'content' => [
                'type'        => 'textarea',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'post' => [
                'type'      => 'submit',
                'class'     => 'myButton',
                'callback'  => [$this, 'callbackSubmit'],
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'myButton',
            ],
        ]);


        // Check the status of the form
        $form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        $this->di->views->add('comment/index', [
            'content' => $form->getHTML()
        ], 'comments');

        if(!empty($_SESSION['flash'])) {
            $this->di->views->add('me/flash', ['flash' => $this->di->informer->getMessage()], 'flash');
            $this->di->informer->clear();
        }
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit($form)
    {
        $form->saveInSession = true;
        return true;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail($form)
    {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess($form)
    {
        
        $this->redirectTo('comment/add'); 
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>Something went wrong.</i></p>");
        $this->redirectTo();
    }
}
