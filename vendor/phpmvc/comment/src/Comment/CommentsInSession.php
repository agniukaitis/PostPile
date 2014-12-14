<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * @param string $key with page key
     * 
     * @return void
     */
    public function add($comment, $key = null) 
    {
        $comments = $this->session->get('comments', []);
        $comments[$key][] = $comment;
        $this->session->set('comments', $comments);
    }

    /**
     * Save changes to a comment.
     *
     * @param array $comment with new details.
     * @param string $key with page key
     * @param string $id with comment id
     * 
     * @return void
     */
    public function save($comment, $key = null, $id = null) {
        $comments = $this->session->get('comments', []);
        $id--;
        $comments[$key][$id] = $comment;
        $this->session->set('comments', $comments);
    }

    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll($key = null, $id = null)
    {
        $comments = $this->session->get('comments', []);
        if(!isset($id)) {
            if(isset($comments[$key])) {
                return $comments[$key];
            }
        }
        else {
            $id--;
            $data = $comments[$key];
            if(isset($data[$id])) {
                return $data[$id];
            }
        }
        
    }


    /**
     * Delete all comments.
     *
     * @return void
     */
    public function deleteAll($key = null, $id = null)
    {
        $comments = $this->session->get('comments', []);
        
        if(!isset($id)) {
            unset($comments[$key]);
        }
        else {
            $id--;
            unset($comments[$key][$id]);
        }

        $this->session->set('comments', $comments);
    }
}
