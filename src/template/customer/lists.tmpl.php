<div class="row">
  <h3>PHP CRUD Grid</h3>
</div>
<div class="row">
  <p>
    <a href="/customer/create" class="btn btn-success">Create</a>
  </p>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email Address</th>
        <th>Mobile Number</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $row): ?>
        <?php e('<tr>'); ?>
        <?php e('<td>'. $row['name'] . '</td>'); ?>
        <?php e('<td>'. $row['email'] . '</td>'); ?>
        <?php e('<td>'. $row['mobile'] . '</td>'); ?>
        <?php e('<td><a class="btn btn-primary" href="/customer/update?id='.$row['id'].'">Read</a></td>'); ?>
        <?php e('</tr>'); ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
