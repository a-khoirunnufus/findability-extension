<?php
use yii\helpers\Url;

// $this->registerJsFile(
//   '@web/js/facilitator/task/list.js',
//   ['position' => \yii\web\View::POS_END],
// );

$this->params['breadcrumbs'] = [
  [ 'label' => 'Pilih Partisipan', 'link' => Url::toRoute('task/select-participant'), 'active' => false ],
  [ 'label' => 'Detail Tugas', 'link' => null, 'active' => true ],
];

$csrfToken = \Yii::$app->request->csrfToken;
$session = \Yii::$app->session;
?>

<div class="mb-4">
  <h4 class="card-title mb-0">Detail Tugas</h4>
  <div class="small text-medium-emphasis">Partisipan <?= $participant['name'] ?></div>
  <div class="small text-medium-emphasis">Tugas <?= $task['code'] ?> - <?= $task['name'] ?></div>
</div>

<div class="card shadow-sm mb-3">
  <div class="card-body">    
    <h5 class="card-title mb-0">Konfigurasi Item</h5>

  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body">    
    <h5 class="card-title mb-0">List Item Final</h5>
    <table class="table" id="table-list-item" style="margin-top: 40px; font-size: .9rem">
      <thead>
        <tr class="table-light">
          <th scope="col">#</th>
          <th scope="col">Kode</th>
          <th scope="col">Deskripsi</th>
          <th scope="col">Nama File</th>
          <th scope="col">Jalur ke File</th>
          <th scope="col">Status</th>
          <th scope="col">Diselesaikan pada</th>
          <th class="text-center" scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>GH-01</td>
          <td>Contoh deskripsi</td>
          <td>Contoh nama file</td>
          <td>Contoh jalur ke file</td>
          <td>Contoh status</td>
          <td>-</td>
          <td></td>
        </tr>
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