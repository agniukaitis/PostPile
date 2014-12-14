<h1 class='content-header'><?=$header?></h1>
<?php if($tools == true) : ?>
    <p>
        <a href="<?=$this->url->create('users/edit') ?>">Edit</a>
        <a href="<?=$this->url->create('users/password') ?>">Change password</a>
    </p>
<?php endif; ?>

<table>
    <?php $grav = md5(strtolower(trim($user->email))); ?>
    <tr>
        <td><img src="http://www.gravatar.com/avatar/<?=$grav?>?s=128&amp;d=identicon&amp;r=PG" alt="Gravatar" width='128' height='128'></td>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
    </tr>

</table>

 
<p><a href='<?=$this->url->create('users')?>'>Back to list...</a></p>  