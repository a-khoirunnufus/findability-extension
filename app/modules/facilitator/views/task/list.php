<?php
/** @var $participant array */

use yii\helpers\Url;

$this->registerJsFile(
  '@web/js/facilitator/task/list.js',
  ['position' => \yii\web\View::POS_END],
);

$this->params['breadcrumbs'] = [
  [ 'label' => 'Pilih Partisipan', 'link' => Url::toRoute('task/select-participant'), 'active' => false ],
  [ 'label' => 'Daftar Tugas', 'link' => null, 'active' => true ],
];

$csrfToken = \Yii::$app->request->csrfToken;
$session = \Yii::$app->session;
?>

<div class="card shadow-sm">
  <div class="card-body">    
    <div class="d-flex flex-row justify-content-between align-items-center">
      <div>
        <h4 class="card-title mb-0">Daftar Tugas</h4>
        <div class="small text-medium-emphasis">Partisipan <?= $participant['name'] ?></div>
      </div>
      <div>
        <button class="btn btn-primary btn-sm text-white me-2" data-coreui-toggle="modal" data-coreui-target="#LoadDefaultTask">
            Load Default</button>
        <button class="btn btn-success btn-sm text-white" data-coreui-toggle="modal" data-coreui-target="#addTask">
            Tambah Tugas</button>
      </div>
    </div>

    <table class="table" id="table-task-list" style="margin-top: 40px">
      <thead>
        <tr class="table-light">
          <th scope="col">#</th>
          <th scope="col">Kode</th>
          <th scope="col">Nama</th>
          <th class="text-center" scope="col">Aksi</th>
          <th class="text-center" scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $task): ?>
          <tr>
            <td><?= $task['order'] ?></td>
            <td><?= $task['code'] ?></td>
            <td><?= $task['name'] ?></td>
            <td class="text-center">
              <button 
                  data-coreui-toggle="modal" 
                  data-coreui-target="#updateTask"
                  data-order="<?= $task['order'] ?>"
                  data-code="<?= $task['code'] ?>"
                  data-name="<?= $task['name'] ?>"
                  data-task-id="<?= $task['id'] ?>" 
                  class="btn-open-edit-modal text-white btn btn-warning btn-sm"
                  >Edit</button>
              <button 
                  data-coreui-toggle="modal" 
                  data-coreui-target="#deleteTask"
                  data-task-id="<?= $task['id'] ?>" 
                  class="btn-open-delete-modal text-white btn btn-danger btn-sm"
                  >Hapus</button>
            </td>
            <td class="text-center">    
              <a href="<?= Url::toRoute([
                  'task/detail', 
                  'participant_id' => $participant['id'], 
                  'task_id' => $task['id']]) ?>" class="btn btn-primary btn-sm"
                  >Detail</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTask" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskLabel">Tambah Tugas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-task" action="<?= Url::toRoute('task/add-task') ?>" method="post">
          <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
          <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
          <div class="mb-3">
            <label for="inputCode" class="form-label">Kode Tugas</label>
            <input type="text" class="form-control" name="code" id="inputCode">
          </div>
          <div class="mb-3">
            <label for="inputName" class="form-label">Nama Tugas</label>
            <input type="text" class="form-control" name="name" id="inputName">
          </div>
          <div class="mb-3">
            <label for="inputOrder" class="form-label">Urutan Tugas</label>
            <input type="number" class="form-control" name="order" id="inputOrder">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-coreui-dismiss="modal">Batal</button>
        <button id="btn-submit-add-form" type="button" class="btn btn-success text-white">Tambah</button>
      </div>
    </div>
  </div>
</div>

<!-- Update Task Modal -->
<div class="modal fade" id="updateTask" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="updateTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateTaskLabel">Update Tugas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-update-task" action="<?= Url::toRoute('task/update-task') ?>" method="post">
          <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
          <input type="hidden" name="task_id">
          <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
          <div class="mb-3">
            <label for="inputCode" class="form-label">Kode Tugas</label>
            <input type="text" class="form-control" name="code" id="inputCode">
          </div>
          <div class="mb-3">
            <label for="inputName" class="form-label">Nama Tugas</label>
            <input type="text" class="form-control" name="name" id="inputName">
          </div>
          <div class="mb-3">
            <label for="inputOrder" class="form-label">Urutan Tugas</label>
            <input type="number" class="form-control" name="order" id="inputOrder">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-coreui-dismiss="modal">Batal</button>
        <button id="btn-submit-update-form" type="button" class="btn btn-warning text-white">Update</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Task Modal -->
<div class="modal fade" id="deleteTask" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="deleteTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTaskLabel">Hapus Tugas</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah anda yakin ingin menghapus task ini?
        <form id="form-delete-task" action="<?= Url::toRoute('task/delete-task') ?>" method="post">
          <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
          <input type="hidden" name="task_id">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-coreui-dismiss="modal">Batal</button>
        <button id="btn-submit-delete-form" type="button" class="btn btn-danger text-white">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- Load Default Task Modal -->
<div class="modal fade" id="LoadDefaultTask" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="LoadDefaultTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="LoadDefaultTaskLabel">Load Tugas Default</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah anda yakin ingin meload tugas default?
        <form id="form-load-default-task" action="<?= Url::toRoute('task/load-default-task') ?>" method="post">
          <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
          <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-coreui-dismiss="modal">Batal</button>
        <button id="btn-submit-load-default-form" type="button" class="btn btn-primary text-white">Load</button>
      </div>
    </div>
  </div>
</div>