<?php

namespace Anax\Informer;

/**
 * Flash message class used for setting and displaying flash messages
 *
 */
class Informer
{
    use \Anax\DI\TInjectionaware;

    /**
     * Properties
     */
    public $valid = array();

    /**
     * Constructor for CInformer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->valid = ['info', 'error', 'success', 'warning'];
    }

    /**
     * Set the message into session variable
     *
     * @return boolean
     */
    public function setMessage($data)
    {
        // initialize a flash session variable
        if($this->di->session->get('flash') == null) {
            $this->di->session->set('flash', array());
        }

        // check for valid message template. Set to default if not valid
        if(!in_array($data['type'], $this->valid)) {
            $data['type'] = $this->valid[0];
        }

        $this->di->session->set('flash', ['type' => $data['type'], 'message' => $data['message']]);

        return true;
    }

    /**
     * Get the message from the session variable
     *
     * @return array $flash that contains the message type and the message string
     */
    public function getMessage()
    {
        $flash = $this->di->session->get('flash');
        $this->clear();

        return $flash;
    }

    /**
     * Clear the session variable content
     *
     * @return boolean
     */
    private function clear()
    {
        $this->di->session->set('flash', array());
        return true;
    }
}