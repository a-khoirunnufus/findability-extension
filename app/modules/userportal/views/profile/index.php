<?php
use yii\helpers\Url;

$this->title = 'Profil';
$identity = Yii::$app->user->identity;
?>

<style>
  #profile-table {
    /* margin: 0 auto; */
  }
  #profile-table td {
    padding: .5rem;
    vertical-align: top;
  }
  #profile-table tr td:nth-child(1) {
    text-align: right;
    font-weight: 700;
  }
  #profile-picture {
    height: 100px;
    width: 100px;
    object-fit: cover;
    border-radius: 50%;
    outline: 1px solid gainsboro;
    padding: .25rem;
  }
</style>

<div class="card shadow-sm">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <table id="profile-table">
          <tr>
            <td>Nama</td>
            <td><?= $identity->name ?></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><?= $identity->email ?></td>
          </tr>
          <tr>
            <td>Perizinan</td>
            <td>
              <?php if($identity->g_access_token == null): ?>
                <p>Aplikasi tidak diizinkan untuk mengakses google drive anda.</p>
                <a href="<?= Url::toRoute('auth/add-google-drive-access') ?>" class="btn btn-outline-primary btn-sm">Izinkan Akses Google Drive</a>
              <?php else: ?>
                <span>Aplikasi diizinkan untuk mengakses google drive anda.</span>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
