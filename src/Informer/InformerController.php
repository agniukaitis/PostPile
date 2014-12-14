<?php

namespace Anax\Informer;

/**
 * Anax base class for wrapping sessions.
 *
 */
class InformerController
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Index action.
     *
     */
    public function indexAction()
    {
        //$this->di->session(); // Will load the session service which also starts the session

        $form = $this->di->form->create([], [
            'type' => [
                'label'      => 'Select Message Type',
                'type'      => 'select',
                'options'     => array('default' => 'Default', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error', 'info' => 'Info',),
            ],
            'test' => [
                'label'     => 'Test',
                'type'      => 'submit',
                'class'     => 'myButton',
                'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);


        // Check the status of the form
        $form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        $this->di->views->add('comment/index', [
            'content' => $form->getHTML()
        ], 'main-wide');
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit($form)
    {
        $this->di->informer->setMessage(array(
            'type' => $this->di->request->getPost('type'),
            'message' => ucfirst($this->di->request->getPost('type')).' message!',
        ));

        return true;
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess($form)
    {
        $this->redirectTo();
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