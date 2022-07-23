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

<div class="row mb-4">
  <div class="col-sm-6">
  <div class="card shadow-sm">
      <div class="card-body">    
        <h5 class="card-title" style="margin-bottom: 40px">Karakteristik Drive Partisipan</h5>
        <ul>
          <li>Jumlah file</li>
          <li>Kedalaman, kedalaman maksimal</li>
          <li>Branching factor</li>
          <li>Ukuran folder</li>
          <li>Grafik terakhir kali diakses</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card shadow-sm">
      <div class="card-body">    
        <h5 class="card-title" style="margin-bottom: 40px">Konfigurasi Item</h5>
    
        <form style="">
          <div class="mb-3 row">
            <label for="itemCountInput" class="col-sm-6 col-form-label">Jumlah item</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="itemCountInput" value="20">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="itemCountInput" class="col-sm-6 col-form-label">Jumlah file</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="itemCountInput" value="10">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="itemCountInput" class="col-sm-6 col-form-label">Jumlah target yang mungkin</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="itemCountInput" value="100">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="itemCountInput" class="col-sm-6 col-form-label">Distribusi</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="itemCountInput" value="5, 3, 2, 2, 2, 2, 1, 1, 1, 1">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="itemCountInput" class="col-sm-6 col-form-label">Urut berdasarkan</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="itemCountInput" value="viewedByMeTime desc">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>
        <!-- 
        TODO: 
        - set item number, default 20
        - set file number, default 10
        - probable target number, default 100 files based on last viewed by me time
        - set distribution, Zipf : 5, 3, 2, 2, 2, 2, 1, 1, 1, 1
        - generate target based on last viewed by me time 
        - 
        -->
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">    
    <h5 class="card-title" style="margin-bottom: 40px">Mapping Item</h5>
    <table class="table" id="table-list-item" style="font-size: .9rem">
      <thead>
        <tr class="table-light">
          <th scope="col">Nama file</th>
          <th scope="col">Kedalaman</th>
          <th scope="col">Jalur ke file</th>
          <th scope="col">Terakhir diakses</th>
          <th scope="col">Frekuensi</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Laporan DI Minggu 6.pdf</td>
          <td>4</td>
          <td>My Drive/Kuliah/Desain Interaksi/Tugas/Laporan DI Minggu 6.pdf</td>
          <td>2022-07-22 12:00:00</td>
          <td><input type="text" class="form-control" style="width: 60px"></td>
          <td>
            <button class="btn btn-sm btn-danger">Hapus</button>
          </td>
        </tr>
        <tr>
          <td>Laporan DI Minggu 6.pdf</td>
          <td>4</td>
          <td>My Drive/Kuliah/Desain Interaksi/Tugas/Laporan DI Minggu 6.pdf</td>
          <td>2022-07-22 12:00:00</td>
          <td><input type="text" class="form-control" style="width: 60px"></td>
          <td>
            <button class="btn btn-sm btn-danger">Hapus</button>
          </td>
        </tr>
        <tr>
          <td>Laporan DI Minggu 6.pdf</td>
          <td>4</td>
          <td>My Drive/Kuliah/Desain Interaksi/Tugas/Laporan DI Minggu 6.pdf</td>
          <td>2022-07-22 12:00:00</td>
          <td><input type="text" class="form-control" style="width: 60px"></td>
          <td>
            <button class="btn btn-sm btn-danger">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
    <button type="submit" class="btn btn-primary mt-3">Terapkan</button>
  </div>
</div>

<div class="card shadow-sm mb-4">
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