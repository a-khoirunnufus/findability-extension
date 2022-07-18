<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuickNav</title>
  <title>QuickNav</title>
  <!-- Bootstrap 5.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="http://localhost:8081/css/navigation.css">
</head>
<body>
  <!-- adaptive view -->
  <ul id="adaptive-view">
    <?php foreach($shortcuts as $shortcut): ?>
      <li>
      <i class="bi bi-file-earmark-fill"></i>&nbsp;&nbsp;<?= $shortcut['name'] ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <hr>
  <!-- breadcrumb / path -->
  <!-- static view -->
  <table id="static-view">
    <thead>
      <tr>
        <th style="width: 65%">Nama</th>
        <th style="width: 20%">Terakhir diubah</th>
        <th style="width: 15%">Ukuran file</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($files as $file): ?>
        <tr>
          <td>
            <?php if($file['mimeType'] == 'application/vnd.google-apps.folder'): ?>
              <i class="bi bi-folder-fill"></i>
            <?php else: ?>
              <i class="bi bi-file-earmark-fill"></i>
            <?php endif; ?>
            &nbsp;&nbsp;<?= $file['name'] ?>
          </td>
          <td><?= date('j M Y', strtotime($file['modifiedByMeTime'])) ?></td>
          <td>
            <?= floor(intval($file['size'])/1000) ?> KB
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>