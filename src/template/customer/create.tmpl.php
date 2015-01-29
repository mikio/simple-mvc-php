<div class="span10 offset1">
  <div class="row">
    <h3>Create a Customer</h3>

    <form class="form-horizontal" action="/customer/create" method="post">
      <div class="form-group <?php has_error($errors, 'name');?> has-feedback">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input name="name" type="text" class="form-control" placeholder="Name" value="<?php e2($name, '');?>">
          <?php error_msg($errors, 'name') ?>
        </div>
      </div>
      <div class="form-group <?php has_error($errors, 'email');?> has-feedback">
        <label for="email" class="col-sm-2 control-label">Email Address</label>
        <div class="col-sm-10">
          <input name="email" type="text" class="form-control" placeholder="Email Address" value="<?php e2($email, '');?>">
          <?php error_msg($errors, 'email') ?>
        </div>
      </div>
      <div class="form-group <?php has_error($errors, 'mobile');?> has-feedback">
        <label for="mobile" class="col-sm-2 control-label">Mobile Number</label>
        <div class="col-sm-10">
          <input name="mobile" type="text"  class="form-control" placeholder="Mobile Number" value="<?php e2($mobile, '');?>">
          <?php error_msg($errors, 'mobile') ?>
        </div>
      </div>
      <div class="form-group well">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-success">Create</button>
          <a class="btn" href="/">Back</a>
        </div>
      </div>
    </form>
  </div>
</div>
