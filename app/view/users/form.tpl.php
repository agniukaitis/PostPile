<h1 class='content-header'><?=$header?></h1>
<div class="centered-form"><?=$main?></div>
<?php if(isset($userId)) : ?>
<div class="back-link">
    <a href='<?=$this->url->create('users/id/'.$userId)?>'><i class="fa fa-angle-double-left"></i> Back to user list...</a>
</div>
<?php endif; ?>
