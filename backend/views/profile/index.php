<?php
use yii\helpers\Url;

?>

<style>
  #profile-table {
    margin: 0 auto;
  }
  #profile-table td {
    padding: .5rem;
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
      <!-- <div class="col-3">
        <div class="d-flex justify-content-center">
          <img src="<?= Url::to('@web/img/profile-default.png') ?>" id="profile-picture">
        </div>
      </div> -->
      <div class="col-12">
        <table id="profile-table">
          <tr>
            <td></td>
            <td>
              <img src="<?= Url::to('@web/img/profile-default.png') ?>" id="profile-picture">
            </td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>Ahmad Khoirunnufus</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>a.khoirunnufus@gmail.com</td>
          </tr>
          <tr>
            <td>Perizinan</td>
            <td>Aplikasi diizinkan untuk mengakses Google Drive.</td>
          </tr>
          <tr>
            <td></td>
            <td><button class="btn btn-danger btn-sm">Hapus Akun</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
