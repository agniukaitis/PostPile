<table class="user-head">
    <?php $grav = md5(strtolower(trim($user->email))); ?>
    <tr>
        <td class="user-gravatar-large"><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=256&amp;d=identicon&amp;r=PG" alt="Gravatar" width='256' height='256'></td>
        <td class="user-username"><?=$user->username?></td>
    </tr>

</table>

<h2>Questions</h2>
<?php if($user->questions != null) : ?>
<?php foreach($user->questions as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
<div class="post">
    <div class="user-post">
        <div class="statscontainer">
            <?php if ($question->answers == 0) : ?>
            <div class="no-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
            <?php elseif ($question->answers > 0) : ?>
            <div class="has-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
            <?php endif; ?>
        </div>
        <div class="summary">
            <h3 class="post-header"><a href="<?=$this->url->create('questions/id/'.$question->id)?>"><?=$question->title?></a></h3>
            <div class='post-time'>
                asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
            </div>
            <?php if($question->modified != null) : ?>
            <div class='modify-time'>
                modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php else : ?>
<div class="no-content">
    <p>This user has not yet asked any questions.<p>
</div>
<?php endif; ?>



<h2>Answers</h2>
<?php if($user->answers != null) : ?>
<?php foreach($user->answers as $question) : $answerString = $question->answers == 1 ? "answer" : "answers"; ?>
<div class="post">
    <div class="user-post">
        <div class="statscontainer">
            <?php if ($question->answers == 0) : ?>
            <div class="no-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
            <?php elseif ($question->answers > 0) : ?>
            <div class="has-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
            <?php endif; ?>
        </div>
        <div class="summary">
            <h3 class="post-header"><a href="<?=$this->url->create('questions/id/'.$question->belongTo)?>"><?=$question->title?></a></h3>
            <div class='post-time'>
                asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
            </div>
            <?php if($question->modified != null) : ?>
            <div class='modify-time'>
                modified <?=dateDiff(date("Y-m-d H:i:s"), $question->modified);?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php else : ?>
<div class="no-content">
    <p>This user has not yet answered any questions.<p>
</div>
<?php endif; ?>

<?php if($tools == true) : ?>
    <a href="<?=$this->url->create('users/edit') ?>" class="button default-button">Edit</a>
    <a href="<?=$this->url->create('users/password') ?>" class="button default-button">Change password</a>
<?php endif; ?>
 
<div class="back-link">
    <a href='<?=$this->url->create('users')?>'><i class="fa fa-angle-double-left"></i> Back to user list...</a>
</div>