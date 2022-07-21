<?php
use yii\helpers\Url;

// TODO: Breadcrumb data
$this->params['breadcrumbs'] = [
  [ 'label' => 'Home', 'link' => Url::toRoute('home/index'), 'active' => false ],
  [ 'label' => 'Pengujian', 'link' => Url::toRoute('user-testing/index'), 'active' => false ],
  [ 'label' => 'Detail Tugas', 'link' => null, 'active' => true ],
];
?>

<div class="card shadow-sm">
  <div class="card-body">
    <h4 class="card-title mb-0">Detail Tugas</h4>
    <div class="small text-medium-emphasis">GH - Antarmuka Google Drive dengan Petunjuk</div>

    <table class="table" id="table-task-list" style="margin-top: 40px; font-size: .9rem">
      <thead>
        <tr class="table-light">
          <th class="text-center" scope="col">Kode</th>
          <th scope="col">Nama File</th>
          <th scope="col">Keterangan</th>
          <th class="text-center" scope="col">Status</th>
          <th scope="col">Diselesaikan pada</th>
          <th class="text-center" scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="text-center" scope="row">GH-1</th>
          <td>DI Minggu 6.pdf</td>
          <td>Laporan tugas desain interaksi minggu ke 6</td>
          <td class="text-center"><span class="badge text-bg-danger text-light">Belum selesai</span></td>
          <td>-</td>
          <td class="text-center">
            <button class="btn btn-primary btn-sm" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop">
                Konfirmasi Selesai</button>
          </td>
        </tr>
        <tr>
          <th class="text-center" scope="row">GH-1</th>
          <td>DI Minggu 6.pdf</td>
          <td>Laporan tugas desain interaksi minggu ke 6</td>
          <td class="text-center"><span class="badge text-bg-danger text-light">Belum selesai</span></td>
          <td>-</td>
          <td class="text-center"><button class="btn btn-secondary btn-sm" disabled>Konfirmasi Selesai</button></td>
        </tr>
        <tr>
          <th class="text-center" scope="row">GH-1</th>
          <td>DI Minggu 6.pdf</td>
          <td>Laporan tugas desain interaksi minggu ke 6</td>
          <td class="text-center"><span class="badge text-bg-danger text-light">Belum selesai</span></td>
          <td>-</td>
          <td class="text-center"><button class="btn btn-secondary btn-sm" disabled>Konfirmasi Selesai</button></td>
        </tr>
        <tr>
          <th class="text-center" scope="row">GH-1</th>
          <td>DI Minggu 6.pdf</td>
          <td>Laporan tugas desain interaksi minggu ke 6</td>
          <td class="text-center"><span class="badge text-bg-danger text-light">Belum selesai</span></td>
          <td>-</td>
          <td class="text-center"><button class="btn btn-secondary btn-sm" disabled>Konfirmasi Selesai</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop">
  Launch static backdrop modal
</button> -->

<!-- Modal -->
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
