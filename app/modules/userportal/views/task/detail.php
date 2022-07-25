<?php
use yii\helpers\Url;

// TODO: Breadcrumb data
$this->params['breadcrumbs'] = [
  [ 'label' => 'Pengujian', 'link' => Url::toRoute('user-testing/index'), 'active' => false ],
  [ 'label' => 'Tugas', 'link' => Url::toRoute('task/index'), 'active' => false ],
  [ 'label' => 'Detail Tugas', 'link' => null, 'active' => true ],
];
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h4 class="card-title mb-0">Detail Tugas</h4>
    <div class="small text-medium-emphasis">GH - Antarmuka Google Drive dengan Petunjuk</div>

    <?php if(count($items) === 0): ?>
      <div class="alert alert-warning" role="alert" style="margin-top: 40px">
        <div class="d-flex">
          <svg style="height: 20px; width: 20px;"><use xlink:href="<?= Url::to('@web/coreui/vendors/@coreui/icons/svg/free.svg#cil-warning', true) ?>"></use></svg>
          <span class="ms-3">Belum ada item untuk tugas ini, silahkan hubungi fasilitator.</span>
        </div>
      </div>
    <?php else: ?>
      <table class="table" id="table-task-list" style="margin-top: 40px; font-size: .9rem">
        <thead>
          <tr class="table-light">
            <th class="text-center" scope="col">Kode</th>
            <th scope="col">Keterangan</th>
            <th class="text-center" scope="col">Status</th>
            <th scope="col">Diselesaikan pada</th>
            <!-- <th class="text-center" scope="col">Aksi</th> -->
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $item): ?>
            <tr>
              <th class="text-center" scope="row"><?= $item['code'] ?></th>
              <td>Pergi ke file dengan deskripsi: <?= $item['description'] ?></td>
              <td class="text-center">
                <?php if($item['is_complete'] === 0): ?>
                  <span class="badge text-bg-secondary text-light">Belum selesai</span></td>
                <?php else: ?>
                  <span class="badge text-bg-success text-light">Selesai</span></td>
                <?php endif; ?>
              <td><?= $item['completed_at'] ? date('j M Y H:i:s', strtotime($item['completed_at'])) : '-' ?></td>
              <!-- <td class="text-center">
                <button class="btn btn-primary btn-sm" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop">
                    Konfirmasi Selesai</button>
              </td> -->
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="staticBackdrop" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5> -->
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Konfirmasi tugas telah selesai?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>
