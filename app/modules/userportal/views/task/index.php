<?php
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
  [ 'label' => 'Pengujian', 'link' => Url::toRoute('user-testing/index'), 'active' => false ],
  [ 'label' => 'Tugas', 'link' => null, 'active' => true ],
];
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h4 class="card-title mb-0">Daftar Tugas</h4>

    <table class="table" id="table-task-list" style="margin-top: 40px">
      <thead>
        <tr class="table-light">
          <th class="text-center" scope="col">#</th>
          <th class="text-center" scope="col">Kode</th>
          <th scope="col">Nama Tugas</th>
          <th scope="col">Jumlah Item</th>
          <th class="text-center" scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $task): ?>
          <tr>
            <td class="text-center"><?= $task['order'] ?></td>
            <td class="text-center"><?= $task['code'] ?></td>
            <td><?= $task['name'] ?></td>
            <td><?php
              $count = app\modules\facilitator\models\UtTaskItem::find()
                        ->where(['task_id' => $task['id']])
                        ->count();
              if($count !== null) echo $count;
            ?></td>
            <td class="text-center">   
              <a href="<?= Url::toRoute([
                  'task/detail', 
                  'participant_id' => $participant['id'], 
                  'task_id' => $task['id']]) ?>" 
                  class="btn btn-primary btn-sm <?= intval($task['is_lock']) === 1 ? 'disabled' : '' ?>"
                  <?= intval($task['is_lock']) === 1 ? 'aria-disabled="true"' : '' ?>
                  >Buka</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>