<div class="row">
  <h3>ユーザー一覧</h3>
</div>
<div class="row">
  <p>
    <a href="/user/create" class="btn btn-success">作成</a>
  </p>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>名前</th>
        <th>Password</th>
        <th>管理者権限</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $row): ?>
        <tr>
            <td><a class="" href="/user/edit?id=<?php eh($row['id']);?>"><?php eh($row['name']) ?></a></td>
            <td><?php eh($row['password']) ?></td>
            <td><?php eh($row['admin']? 'あり': 'なし'); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
