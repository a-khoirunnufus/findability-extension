<?php
use yii\helpers\Url;

$this->registerJsFile(
  '@web/js/facilitator/task/setup.js',
  ['position' => \yii\web\View::POS_END],
);

$this->params['breadcrumbs'] = [
  [ 'label' => 'Pilih Partisipan', 'link' => Url::toRoute('task/select-participant'), 'active' => false ],
  [ 'label' => 'Atur Tugas', 'link' => null, 'active' => true ],
];

$csrfToken = \Yii::$app->request->csrfToken;
$session = \Yii::$app->session;
?>

<div class="mb-4">
  <h4 class="card-title mb-0">Atur Tugas</h4>
  <div class="small text-medium-emphasis">Partisipan <?= $participant['name'] ?></div>
  <div class="small text-medium-emphasis">Tugas <?= $task['code'] ?> - <?= $task['name'] ?></div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <div class="d-flex flex-row align-items-center justify-content-between" style="margin-bottom: 40px">
      <h5 class="card-title mb-0">Karakteristik Drive Partisipan</h5>
    </div>    
    
    <table class="table">
      <tbody>
        <tr>
          <th style="width: 300px" scope="row">Visualisasi hirariki file</th>
          <td><a target="_blank" href="<?= Url::toRoute([
              'participant/display-tree-view',
              'participant_id' => $participant['id']]) ?>" class="btn btn-primary btn-sm"
              >Lihat</a></td>
        </tr>
        <tr>
          <th style="width: 300px" scope="row">Jumlah seluruh file</th>
          <td><?= $numberOfFiles ?></td>
        </tr>
        <tr>
          <th style="width: 300px" scope="row">Jumlah file pada kedalaman setiap kedalaman</th>
          <td>
            <ul class="list-group list-group-horizontal">
              <?php foreach($fileCountsPerDepth as $key => $value): ?>
                <li class="list-group-item"><?=$key?>: <?=$value?></li>
              <?php endforeach; ?>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="card-title" style="margin-bottom: 40px">Pilih File Target</h5>
    
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-level-1-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-1" type="button" role="tab" aria-controls="nav-level-1" aria-selected="true">Level 1</button>
        <button class="nav-link" id="nav-level-2-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-2" type="button" role="tab" aria-controls="nav-level-2" aria-selected="false">Level 2</button>
        <button class="nav-link" id="nav-level-3-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-3" type="button" role="tab" aria-controls="nav-level-3" aria-selected="false">Level 3</button>
        <button class="nav-link" id="nav-level-4-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-4" type="button" role="tab" aria-controls="nav-level-4" aria-selected="false">Level 4</button>
        <button class="nav-link" id="nav-level-5-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-5" type="button" role="tab" aria-controls="nav-level-5" aria-selected="false">Level 5</button>
        <button class="nav-link" id="nav-level-6-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-6" type="button" role="tab" aria-controls="nav-level-6" aria-selected="false">Level 6</button>
        <button class="nav-link" id="nav-level-7-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-7" type="button" role="tab" aria-controls="nav-level-7" aria-selected="false">Level 7</button>
        <button class="nav-link" id="nav-level-8-tab" data-coreui-toggle="tab" data-coreui-target="#nav-level-8" type="button" role="tab" aria-controls="nav-level-8" aria-selected="false">Level 8</button>
      </div>
    </nav>
    <div class="tab-content p-3" id="nav-tabContent" style="height: fit-content; max-height: 200px; overflow: scroll; font-size: 14px">

      <div class="tab-pane fade show active" id="nav-level-1" role="tabpanel" aria-labelledby="nav-level-1-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_1'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-2" role="tabpanel" aria-labelledby="nav-level-2-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_2'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-3" role="tabpanel" aria-labelledby="nav-level-3-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_3'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-4" role="tabpanel" aria-labelledby="nav-level-4-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_4'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-5" role="tabpanel" aria-labelledby="nav-level-5-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_5'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-6" role="tabpanel" aria-labelledby="nav-level-6-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_6'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-7" role="tabpanel" aria-labelledby="nav-level-7-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_7'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tab-pane fade" id="nav-level-8" role="tabpanel" aria-labelledby="nav-level-8-tab" tabindex="0">
        <div class="list-group list-group-flush">
          <?php foreach($filesPerDepth['level_8'] as $file): ?>
            <label class="list-group-item">
              <div class="row">
                <div class="col-sm-1"><input class="form-check-input me-1" type="checkbox"
                    name="file_id" value="<?= $file['id'] ?>"></div>
                <div class="col-sm-4" width><?= $file['name'] ?></div>
                <div class="col-sm-5"><?= $file['pathToFile'] ?></div>
                <div class="col-sm-2"><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      
      <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
    </div>
    
    <hr>
    <div class="d-flex align-items-center mb-3">
      <p class="mb-0 me-3">Target Terpilih: <span id="selected-file-count">0<span></p>
    </div>
    <form id="form-select-files-1" action="<?= Url::toRoute('task/select-file1') ?>" method="post">
      <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
      <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
      <input type="hidden" name="selected_files" value="">
    </form>
    <button id="submit-selected-files-1" class="btn btn-primary">Terapkan</button>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">    
    <h5 class="card-title" style="margin-bottom: 40px">Mapping File</h5>

    <div class="alert alert-info" role="alert">
      <!-- 5, 3, 2, 2, 2, 2, 1, 1, 1, 1 -->
      Distribusi Default:&nbsp;&nbsp;
      <p class="mb-0 fs-5 d-inline">
        <span class="badge text-bg-primary text-white">5</span>
        <span class="badge text-bg-primary text-white">3</span>
        <span class="badge text-bg-primary text-white">2</span>
        <span class="badge text-bg-primary text-white">2</span>
        <span class="badge text-bg-primary text-white">2</span>
        <span class="badge text-bg-primary text-white">2</span>
        <span class="badge text-bg-primary text-white">1</span>
        <span class="badge text-bg-primary text-white">1</span>
        <span class="badge text-bg-primary text-white">1</span>
        <span class="badge text-bg-primary text-white">1</span>
      </p>
    </div>
    <table class="table" id="table-mapping-file" style="font-size: 14px">
      <thead>
        <tr class="table-light">
          <th scope="col">Nama File</th>
          <th scope="col">Kedalaman</th>
          <th scope="col">Jalur ke File</th>
          <th scope="col">Terakhir Diakses</th>
          <th scope="col">Frekuensi</th>
        </tr>
      </thead>
      <tbody>
        <?php if($selectedFiles1): ?>
          <?php foreach($selectedFiles1 as $file): ?>
            <tr>
              <td><?= $file['name'] ?></td>
              <td><?= $file['depth'] ?></td>
              <td><?= $file['pathToFile'] ?></td>
              <td><?= date('j M Y', strtotime($file['viewedByMeTime'])) ?></td>
              <td><input name="file_freq" data-file-id="<?= $file['id'] ?>" type="number" class="form-control" style="width: 100px"></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <hr>
    <form id="form-set-final-targets" action="<?= Url::toRoute('task/set-final-targets') ?>" method="post">
      <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
      <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
      <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
      <input type="hidden" name="final_targets" value="">
    </form>
    <button id="btn-submit-final-targets" class="btn btn-primary">Terapkan</button>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">    
    <h5 class="card-title mb-0">List File Target Final</h5>
  
    <table class="table" id="table-mapping-file" style="font-size: 14px; margin-top: 40px;">
      <thead>
        <tr class="table-light">
          <th scope="col">#</th>
          <th scope="col">Nama File</th>
          <th scope="col">Kedalaman</th>
          <th scope="col">Jalur ke File</th>
          <th scope="col">Terakhir Diakses</th>
          <th scope="col">Frekuensi</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($targets as $key => $file): ?>
          <tr>
            <td><?= $key + 1 ?></td>
            <td><?= $file['name'] ?></td>
            <td><?= $file['depth'] ?></td>
            <td><?= $file['path_to_file'] ?></td>
            <td><?= date('j M Y', strtotime($file['viewed_by_me_time'])) ?></td>
            <td><?= $file['frequency'] ?></td>
            <td>
              <div class="d-flex">
                <button class="btn btn-warning btn-sm text-white me-2">Edit</button>
                <button class="btn btn-danger btn-sm text-white">Hapus</button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>