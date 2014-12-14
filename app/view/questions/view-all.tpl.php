<h1 class='content-header'><?=$header?></h1>

<?php if ($questions != null) : ?>
<?php foreach ($questions as $question) : 
    $grav = md5(strtolower(trim($question->email))); 
    $extract = getExtract($question->content);
    $answerString = $question->answers == 1 ? "answer" : "answers";
?>
<div class="post">
    <div class="statscontainer">
        <?php if ($question->answers == 0) : ?>
        <div class="no-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
        <?php elseif ($question->answers > 0) : ?>
        <div class="has-answers"><h3 class="answer-count-header"><?=$question->answers?></h3><?=$answerString?></div>
        <?php endif; ?>
    </div>
    <div class="summary">
        <h3 class="post-header"><a href="<?=$this->url->create('questions/id/'.$question->questionId)?>"><?=$question->title?></a></h3>
        <div class="extract">
            <?=$extract?>
        </div>
        <div class="tags taglist fl">
            <?php foreach ($question->tags as $tag) : ?>
            <a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
            <?php endforeach; ?>
        </div>
        <div class='user-info post-signature fr'>
            <div class='post-time'>
                asked <?=dateDiff(date("Y-m-d H:i:s"), $question->posted);?>
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
    </div>
</div>
<?php endforeach; ?>
<?php else : ?>
<div class="no-content">
    <p>Aww, There are no questions... Be the first one to <a href="<?=$this->url->create('questions/ask')?>"><i class="fa fa-question"></i> ask</a> a question.<p>
</div>
<?php endif; ?>