<div class="span10 offset1">
    <div class="row">
        <h3>ユーザー編集</h3>
        <form class="form-horizontal" action="/user/edit" method="post">
            <input name="id" type="hidden" value="<?php eh($id);?>">
            <?php $this->inc('/user/form'); ?>
        </form>
    </div>
</div>
