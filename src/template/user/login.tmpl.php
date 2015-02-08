<div class="span10 offset1">
    <div class="row">
        <h3>ログイン</h3>
        <form class="form-horizontal" action="/user/login" method="post">
            <input name="id" type="hidden" value="<?php eh($id);?>">

            <div class="form-group <?php bst_has_error($errors, 'userId');?> has-feedback">
                <label for="userId" class="col-sm-2 control-label">User ID</label>
                <div class="col-sm-10">
                    <input name="userId" type="text" class="form-control" placeholder="User ID" value="<?php eh2($userId, '');?>">
                    <?php bst_error_msg($errors, 'userId') ?>
                </div>
            </div>
            <div class="form-group <?php bst_has_error($errors, 'password');?> has-feedback">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input name="password" type="text" class="form-control" placeholder="英数のみ" value="<?php eh2($password, '');?>">
                    <?php bst_error_msg($errors, 'password') ?>
                </div>
            </div>
            <div class="form-group well">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">ログイン</button>
                </div>
            </div>
            <?php $this->inc('/user/form'); ?>
        </form>
    </div>
</div>
