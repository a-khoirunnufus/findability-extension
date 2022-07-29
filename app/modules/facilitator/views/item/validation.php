<?php

use yii\helpers\Url;

$this->params['breadcrumbs'] = [
  [ 'label' => 'Pilih Partisipan', 'link' => Url::toRoute('task/select-participant'), 'active' => false ],
  [ 'label' => 'Daftar Tugas', 'link' => Url::toRoute(['task/list', 'participant_id' => $participant['id']]), 'active' => false ],
  [ 'label' => 'Daftar Item Tugas', 'link' => Url::toRoute(['item/index', 'participant_id' => $participant['id'], 'task_id' => $task['id']]), 'active' => false ],
  [ 'label' => 'Validasi Item Tugas', 'link' => null, 'active' => true ],
];

$drive = new app\components\DriveFileUt($participant['id']);
$csrfToken = \Yii::$app->request->csrfToken;
?>

<div class="mb-4">
  <h4 class="card-title mb-0">Validasi Item Tugas</h4>
  <div class="small text-medium-emphasis">Partisipan <?= $participant['name'] ?></div>
  <div class="small text-medium-emphasis">Tugas <?= $task['code'] ?> - <?= $task['name'] ?></div>
  <div class="small text-medium-emphasis">Item <?= $item['code'] ?> <?= $item['file_name'] ?></div>
  <a href="<?= Url::toRoute(['participant/display-tree-view', 'participant_id' => $participant['id']]) ?>" target="_blank" class="btn btn-primary btn-sm mt-3">Tampilkan hieraki file</a>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">    
    <form action="<?= Url::toRoute('item/validate') ?>" method="post">
      <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
      <input type="hidden" name="task_item_id" value="<?= $item['id'] ?>">
      <table class="table">
        <thead>
          <tr class="table-light">
            <th scope="col">Pilih</th>
            <th scope="col">Aksi</th>
            <th scope="col">Objek</th>
            <th scope="col">Waktu</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($logs as $key => $log): ?>
            <tr>
              <td><input class="form-check-input me-1" type="checkbox" name="task_item_log_id[]" 
                  value="<?= $log['id'] ?>"></td>
              <td><?= $log['action'] ?></td>
              <td>
                <?php 
                if($item['interface'] == 'GOOGLE_DRIVE') {
                  // $tempArr = explode('/', $log['object']);
                  // $folder_id = end($tempArr);
                  $path = parse_url($log['object'], PHP_URL_PATH);
                  $path = explode('/', $path);
                  $folder_id = end($path);
                }
                elseif($item['interface'] == 'QUICKNAV') {
                  $url = $log['object'];
                  if($log['object'] == 'PREVIOUS') {
                    $url = $logs[$key-1]['object'];
                  }
                  $url = parse_url($url, PHP_URL_QUERY);
                  $output;
                  parse_str($url, $output);
                  $folder_id = $output['folder_id'];
                }
                $file = $drive->getFileById($folder_id);
                echo $file['name'];
                ?>
              </td>
              <td><?= $log['time'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <button type="submit" class="btn btn-success btn-lg text-white mt-3">Tandai selesai</button>
    </form>
    <form  action="<?= Url::toRoute('item/unvalidate') ?>" method="post">
      <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
      <input type="hidden" name="task_item_id" value="<?= $item['id'] ?>">
      <button type="submit" class="btn btn-secondary btn-lg text-white mt-3">Tandai belum selesai</button>
    </form>
  </div>
</div>