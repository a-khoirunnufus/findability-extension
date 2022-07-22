<?php
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
  [ 'label' => 'Home', 'link' => Url::toRoute('home/index'), 'active' => false ],
  [ 'label' => 'Pengujian', 'link' => null, 'active' => true ],
];
?>

<style>
  #table-task-list tbody td:not(:nth-child(2)),
  #table-task-list tbody th {
    text-align: center;
  }
</style>

<div class="card shadow-sm">
  <div class="card-body">
    <h4 class="card-title mb-0">Daftar Tugas</h4>

    <table class="table" id="table-task-list" style="margin-top: 40px">
      <thead>
        <tr class="table-light">
          <th class="text-center" scope="col">Kode</th>
          <th scope="col">Nama Tugas</th>
          <th class="text-center" scope="col">Jumlah Item</th>
          <th class="text-center" scope="col">Item Selesai</th>
          <th class="text-center" scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">GH</th>
          <td>Antarmuka Google Drive dengan Petunjuk</td>
          <td>20</td>
          <td>0/20</td>
          <td><a href="<?= Url::toRoute(['user-testing/task-detail', 'task-id' => '1']) ?>" class="btn btn-primary btn-sm">Detail</a></td>
        </tr>
        <tr>
          <th scope="row">G</th>
          <td>Antarmuka Google Drive tanpa Petunjuk</td>
          <td>20</td>
          <td>0/20</td>
          <td><button class="btn btn-primary btn-sm">Detail</button></td>
        </tr>
        <tr>
          <th scope="row">QH</th>
          <td>Antarmuka QuickNav dengan Petunjuk</td>
          <td>20</td>
          <td>0/20</td>
          <td><button class="btn btn-primary btn-sm">Detail</button></td>
        </tr>
        <tr>
          <th scope="row">Q</th>
          <td>Antarmuka QuickNav tanpa Petunjuk</td>
          <td>20</td>
          <td>0/20</td>
          <td><button class="btn btn-primary btn-sm">Detail</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>