<div class="form-group <?php bst_has_error($errors, 'name');?> has-feedback">
    <label for="name" class="col-sm-2 control-label">名前</label>
    <div class="col-sm-10">
        <input name="name" type="text" class="form-control" placeholder="表示用" value="<?php eh2($name, '');?>">
        <?php bst_error_msg($errors, 'name') ?>
    </div>
</div>
<div class="form-group <?php bst_has_error($errors, 'userId');?> has-feedback">
    <label for="user_id" class="col-sm-2 control-label">User ID</label>
    <div class="col-sm-10">
        <input name="user_id" type="text" class="form-control" placeholder="英数, _ のみ" value="<?php eh2($userId, '');?>">
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
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                <input name="admin" type="checkbox" <?php e($admin? 'checked': '');?>> 管理者権限
            </label>
        </div>
    </div>
</div>
<div class="form-group well">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-success">保存</button>
        <a class="btn" href="/user/list">戻る</a>
    </div>
</div>
