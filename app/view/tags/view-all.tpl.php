<h1 class='content-header'><?=$header?></h1>

<p>
    A tag is a keyword or label that categorizes your question with other, similar questions. Using the right tags makes it easier for others to find and answer your question.
</p>
<?php if($tags != null) : ?>
<table class="tags-table taglist">
    <tr>
        <?php $i=0; foreach ($tags as $tag) : $i++?>
        <td>
            <a href="<?=$this->url->create('questions/tagged/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a> <span class="quiet">x <?=$tag->count?></span>
        </td>
    <?php if($i%4 == 0) : ?>
    </tr><tr>
    <?php endif; ?>
        <?php endforeach; ?>

</table>
<?php else : ?>
<div class="no-content">
    <p>Aww, There are no tags... Be the first one to <a href="<?=$this->url->create('questions/ask')?>"><i class="fa fa-question"></i> ask</a> a question, and create some tags.<p>
</div>
<?php endif; ?>
