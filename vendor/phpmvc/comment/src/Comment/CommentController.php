<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize() {
        $this->comments = new Comment();
        $this->comments->setDI($this->di);
    }

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($pageKey) {

        $all = $this->comments->query()
            ->where('pageKey = "' . $pageKey . '"')
            ->execute();

        $this->views->add('comment/comments', [
            'comments' => $all,
        ], 'comments');
    }

    /**
     * Reset and setup database tabel.
     *
     * @return void
     */
    public function setupAction() {
        $table = [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'content' => ['text'],
                'name' => ['varchar(80)'],
                'mail' => ['varchar(80)'],
                'pageKey' => ['varchar(80)'],
                'timestamp' => ['datetime'],
                'ip' => ['varchar(20)'],
        ];

        $res = $this->comments->setupTable($table);

        // Add a comment
        $this->comments->create([
            'content' => 'Please leave a comment :)',
            'name' => 'Julius Semenas',
            'mail' => md5( strtolower( trim('semius@gmail.com') )),
            'pageKey' => 'kmom04',
            'timestamp' => time(),
            'ip' => $this->request->getServer('REMOTE_ADDR'),
        ]);

        $this->informer->setMessage(array(
            'type' => 'success',
            'message' => 'The comment database has been restored!'
        ));
 
        $url = $this->url->create('kmom01');
        $this->response->redirect($url);

        //md5( strtolower( trim($this->request->getPost('mail')) ) )
    }

    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction() {
        // Get data from the form and unset the session variable
        $comment['content']     = $_SESSION['form-save']['content']['value'];
        $comment['name']        = $_SESSION['form-save']['name']['value'];
        $comment['mail']        = md5( strtolower( trim($_SESSION['form-save']['mail']['value']) ) );
        $comment['pageKey']     = $_SESSION['form-save']['pageKey']['value'];
        $comment['timestamp']   = time();
        $comment['ip']          = $this->di->request->getServer('REMOTE_ADDR');

        session_unset($_SESSION['form-save']);

        $this->informer->setMessage(array(
            'type' => 'success',
            'message' => 'Your comment has been added!'
        ));

        $this->comments->save($comment);
        $url = $this->url->create($comment['pageKey']);
        $this->response->redirect($url);
    }

    /**
     * Remove a comment.
     *
     * @return void
     */
    public function removeAction($pageKey = null, $id = null) {

        if (!isset($id)) {
            $res = $this->comments->deleteAll('pageKey', $pageKey);

            $this->informer->setMessage(array(
                'type' => 'success',
                'message' => 'All comments were deleted!'
            ));
        }
        else {
            $res = $this->comments->delete($id);

            $this->informer->setMessage(array(
                'type' => 'success',
                'message' => 'The comment was deleted!'
            ));
        }

        $url = $this->url->create($pageKey);
        $this->response->redirect($url);
    }

    /**
     * Edit a comment.
     *
     * @return void
     */
    public function editAction($id = null) {

        $this->di->theme->setTitle("Edit post");

        if (!isset($id)) {
            die("Missing id");
        }

        $comment = $this->comments->find($id);

        $form = $this->di->form->create([], [
            'redirect' => [
                'type'        => 'hidden',
                'value'       => $this->di->url->create($comment->pageKey),
            ],
            'pageKey' => [
                'type'        => 'hidden',
                'value'       => $comment->pageKey,
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Name:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $comment->name,
            ],
            'content' => [
                'type'        => 'textarea',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $comment->content,
            ],
            'save' => [
                'type'      => 'submit',
                'class'     => 'myButton',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
            'reset' => [
                'type'      => 'reset',
                'class'     => 'myButton',
            ],
        ]);

        // Prepare the page content
        $this->views->add('comment/edit', [
            'title' => "Edit post",
            'main' => $form->getHTML(),
            'pageKey' => $comment->pageKey,
        ]);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {

            // Get data from the form and unset the session variable
            $edit_comment['id']     = $comment->id;
            $edit_comment['content']     = $_SESSION['form-save']['content']['value'];
            $edit_comment['name']        = $_SESSION['form-save']['name']['value'];

            session_unset($_SESSION['form-save']);

            $this->comments->save($edit_comment);

            $this->informer->setMessage(array(
                'type' => 'success',
                'message' => 'The changes were saved!'
            ));

            // Route to prefered controller function
            $url = $this->url->create($comment->pageKey);
            $this->response->redirect($url);
         
        } else if ($status === false) {  

            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Something went wrong.</i></p>");
        }

    }

} 