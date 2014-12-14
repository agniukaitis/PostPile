<h1 class='content-header'><?=$header?></h1>
<table class="users-list">
    <tr>
        <?php $i=0; foreach ($users as $user) : $grav = md5(strtolower(trim($user->email))); $i++; ?>
        <td class="quadrant">
            <div class='user-info post-signature'>
                <div class='post-time'>
                    joined <?=$user->registered?>
                </div>
                <div class='user-gravatar'>
                    <a href="<?=$this->url->create('users/id/' . $user->id) ?>"><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=32&amp;d=identicon&amp;r=PG" alt="Gravatar" width='32' height='32'></a>
                </div>
                <div class='user-details'>
                    <a href="<?=$this->url->create('users/id/' . $user->id) ?>"><?=$user->username?></a>
                </div>
            </div>
        </td>
    <?php if($i%4 == 0) : ?>
    </tr><tr>
    <?php endif; ?>
<?php endforeach; ?>
</table>