<?php
use yii\helpers\Url;
use app\models\User;

$csrfToken = \Yii::$app->request->csrfToken;
$identity = \Yii::$app->user->identity;

$this->params['breadcrumbs'] = [
  [ 'label' => 'Home', 'link' => Url::toRoute('home/index'), 'active' => false ],
  [ 'label' => 'Pengujian', 'link' => null, 'active' => true ],
];

// is user already be participant
$isParticipant = User::isUTParticipant($identity->id);
?>

<style>
  #table-task-list tbody td:not(:nth-child(2)),
  #table-task-list tbody th {
    text-align: center;
  }
</style>

<?php if($isParticipant): ?>
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
<?php else: ?>
  <div class="card shadow-sm" style="width: fit-content; margin: 0 auto;">
    <div class="card-body">
      <h4 class="card-title mb-0">Daftar sebagai partisipan</h4>

      <form action="#" method="post" style="width: 400px; margin-top: 40px">
        <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
        <div class="mb-3">
          <label for="name" class="form-label">Nama</label>
          <input type="text" class="form-control" id="name" value="<?= $identity->name ?>">
        </div>
        <div class="mb-3">
          <label for="age" class="form-label">Umur</label>
          <input type="number" class="form-control" id="age">
        </div>
        <div class="mb-3">
          <label for="job" class="form-label">Pekerjaan</label>
          <input type="text" class="form-control" id="job">
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </form>
    </div>
  </div>
<?php endif; ?>