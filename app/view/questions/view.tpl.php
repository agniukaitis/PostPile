<h1 class='content-header'><?=$header?></h1>

<?php $authorId = $questions[0]->userId ?>
<?php $i=0; foreach ($questions as $question) : $grav = md5(strtolower(trim($question->email))); ?>
<table class='post'>
    <tr>
        <td class='vote-cell'>
            <div></div>
            <div class="accepted">
                <?php if($question->type == 'a') : ?>
                    <?php if($this->di->session->get('user') != null && $authorId == $this->di->session->get('user')[0]->id) : ?>
                        <?php if($question->accepted == 1) : ?>
                            <a href="<?=$this->url->create('questions/accept/'.$question->questionId.'/'.$authorId) ?>" title="Click to undo acceptance of this answer"><i class="fa fa-check yes"></i></a>
                        <?php else : ?>
                            <a href="<?=$this->url->create('questions/accept/'.$question->questionId.'/'.$authorId) ?>" title="Click to accept this answer because it solved your problem or was the most helpful in finding your solution (click again to undo)"><i class="fa fa-check no"></i></a>
                        <?php endif; ?>
                    <?php elseif($question->accepted == 1) : ?>
                        <i class="fa fa-check yes"></i>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </td>
        <td class='post-cell'>
            <div>
                <div class='post-text'><?=$question->content?></a></div>
                <div class='taglist'>
                <?php if($question->type == 'q' && $i < 1) : ?>
                    <?php foreach ($tags as $tag) : ?>
                    <a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
                <table class="post-head">
                    <tr>
                        <td class='post-tools'>
                            <div>
                            <?php if($this->di->session->get('user') != null) : if($question->userId == $this->di->session->get('user')[0]->id) : ?>
                                <a href="<?=$this->url->create('questions/edit/'.$question->questionId) ?>">Edit</a> |
                                <a href="<?=$this->url->create('questions/delete/'.$question->questionId) ?>">Delete</a>
                            <?php endif; endif;?>
                            </div>
                        </td>
                        <td class='post-signature'>
                            <div class='user-info'>
                                <div class='post-time'>
                                    <?php if ($question->type == 'q') : ?>
                                        asked
                                    <?php else : ?>
                                        answered
                                    <?php endif; ?>
                                    <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
                                </div>
                                <?php if($question->modified != null) : ?>
                                <div class='modify-time'>
                                    modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
                                </div>
                                <?php endif; ?>
                                <div class='user-gravatar'>
                                    <a href="<?=$this->url->create('users/id/' . $question->userId) ?>"><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32&amp;d=identicon&amp;r=PG" alt="Gravatar" width='32' height='32'></a>
                                </div>
                                <div class='user-details'>
                                    <a href="<?=$this->url->create('users/id/' . $question->userId) ?>"><?=$question->username?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <?php if (!empty($question->comments)) : ?>
    <tr>
        <td class='vote-cell'></td>
        <td>
            <table class='comments'>
                <?php foreach ($question->comments as $comment) : ?>
                <tr>
                    <td class="post-text">
                        <?=$comment->content?> &#8211; <a href="<?=$this->url->create('users/id/' . $comment->userId) ?>">
                        <?=$comment->username?></a> <span class="comment-time"><?=dateDiff(date("Y-m-d H:i:s"), (isset($comment->modified)?$comment->modified:$comment->posted));?></span>
                    </td>
                    <td class="comment-tools">
                        <?php if($this->di->session->get('user') != null) : if($comment->userId == $this->di->session->get('user')[0]->id) : ?>
                            <a href="<?=$this->url->create('questions/edit/'.$comment->questionId) ?>" title="Edit"><i class="fa fa-pencil-square-o"></i></a> |
                            <a href="<?=$this->url->create('questions/delete/'.$comment->questionId) ?>" title="Delete"><i class="fa fa-times"></i></a>
                        <?php endif; endif;?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>
    <?php endif; ?>
    <tr><td></td><td><a href="<?=$this->url->create('questions/comment/'.$question->questionId) ?>">add a comment</a></td></tr>
</table>
<?php if($i<1) : ?>
<h2 class='answer-header'><?=$answerCount?></h2>
<?php $i++ ?>
<?php endif; ?>
<?php endforeach; ?>

<?php if(isset($form)) : ?>
    <div><?=$form?></div>
<?php else : ?>
    <div class="no-content">
        <p>
            <b>You must be logged in to post your answer</b><br>
            <a href="<?=$this->url->create('login')?>"><i class="fa fa-sign-in"></i> Login</a> or 
            <a href="<?=$this->url->create('register')?>"><i class="fa fa-pencil-square-o"></i> Register</a> to participate.
        </p>
    </div>
<?php endif; ?>