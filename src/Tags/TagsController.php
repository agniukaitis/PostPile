<?php

namespace Anax\Tags;
 
/**
 * A controller for users and admin related events.
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize() {
        $this->tags = new \Anax\Tags\Tag();
        $this->tags->setDI($this->di);
    }

    public function indexAction() {
        $this->di->theme->setTitle('Tags');

        //$all = $this->tags->findAll();

        $all = $this->tags->query('tag, COUNT(*) AS count')
            ->groupBy('tag')
            ->execute();

        $this->views->add('tags/view-all', [
            'tags' => $all,
            'header' => "Tags",
        ], 'main-wide');
    }

    public function mostusedAction()
    {
        $tags = $this->tags->query('tag, COUNT(*) AS tagCount')
            ->groupBy('tag')
            ->orderBy('tagCount DESC')
            ->limit(4)
            ->execute();

        return $tags;
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function slugAction($slug = null)
    {
        /*$user = $this->users->find($id);

        if($user == null) {
          $this->redirectTo('users');
        }

        $tools = false;
        if($this->di->session->get('user') != null) {
            if($id == $this->di->session->get('user')[0]->id) {
                $tools = true;
            }
        }

        $this->theme->setTitle("User " . $user->username);
        $this->views->add('users/view', [
            'user' => $user,
            'tools' => $tools,
            'header' => $user->username,
        ], 'main-wide');*/
    }

    public function getalltagsAction($id = null) {
        if($id != null) {
            $tags = $this->tags->query('tag')
                ->groupBy('tag')
                ->where('postId = ?')
                ->orderBy('id ASC')
                ->execute([$id]);
        }
        else {
            $tags = $this->tags->query()
                ->execute();
        }

        return $tags;
    }

    public function processtagsAction($string = null)
    {
        if($string != null) {
            $string = strtolower($string);
            $rawTags = explode(',', $string);
            $tags = array();

            foreach($rawTags as $tag) {
                $tag = trim($tag);
                $tag = str_replace(' ', '-', $tag);
                $tag = substr($tag, 0, 30);
                $tags[] = $tag;
            }

            $tags = array_unique($tags);
            $tags = array_slice($tags, 0, 5);

            return $tags;
        }

        return false;
    }

    public function gettagstringAction($tags = null)
    {
        if($tags != null) {
            $tagArray = array();
            foreach($tags as $tag) {
                $tagArray[] = $tag->tag;
            }

            $tagString = implode(', ', $tagArray);
            return $tagString;
        }

        return false;
    }

    public function settagsAction($postId = null, $userId = null, $tags = null)
    {
        if($postId != null && $userId != null && $tags != null) {
            
            foreach($tags as $tag) {
                $this->tags->create([
                    'postId' => $postId,
                    'userId' => $userId,
                    'tag' => $tag,
                ]);
            }

            return true;
        }

        return false;
    }

    public function replacetagsAction($postId = null, $userId = null, $tags = null)
    {
        if($postId != null && $userId != null && $tags != null) {
            
            $this->tags->deleteAll('postId', $postId);

            foreach($tags as $tag) {
                $this->tags->create([
                    'postId' => $postId,
                    'userId' => $userId,
                    'tag' => $tag,
                ]);
            }

            return true;
        }

        return false;
    }

    public function deletetagsAction($id = null)
    {
        if($id != null) {
            $this->tags->deleteAll('postId', $id);

            return true;
        }

        return false;
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
            'userId' => ['integer', 'not null'],
            'postId' => ['integer', 'not null'],
            'tag' => ['varchar(80)'],
        ];

        $res = $this->tags->setupTable($table);

        $this->informer->setMessage(array(
            'type' => 'success',
            'message' => 'Tag database has been wiped!'
        ));
     
        $url = $this->url->create('tags');
        $this->response->redirect($url);
    }
 
}
