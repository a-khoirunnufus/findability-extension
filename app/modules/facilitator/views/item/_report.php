<?php
use yii\helpers\Url;
?>

<a href="<?= Url::toRoute(['item/generate-report', 'task_id' => $taskId]) ?>" class="btn btn-primary btn-sm mt-3">Generate Report</a>

<table class="table mt-3" id="table-task-list" style="font-size: .9rem">
  <thead>
    <tr class="table-light">
      <th scope="col">#</th>
      <th scope="col">Kode</th>
      <th scope="col">Nama File</th>
      <th scope="col">Waktu Penyelesaian</th>
      <th scope="col">Jumlah Langkah</th>
      <th scope="col">Laporan digenerate pada</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($itemReports as $item): ?>
      <tr>
        <th scope="row"><?= $item['order'] ?></th>
        <td scope="row"><?= $item['code'] ?></td>
        <td><?= $item['file_name'] ?></td>
        <td><?= $item['time_completion'] ?></td>
        <td><?= $item['number_of_step'] ?></td>
        <td><?= $item['generate_at'] ?></td>
        <td></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>