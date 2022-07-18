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
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      font-size: 13px;
    }
    table {
      table-layout: fixed;
      width: 100%;
    }
  </style>
</head>
<body>
  <table>
    <?php foreach($shortcuts as $shortcut): ?>
      <tr>
        <td><?= $shortcut['name'] ?></td>
        <td><?= $shortcut['viewedByMeTime'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <hr>
  <!-- static view list -->
  <table>
    <thead>
      <tr>
        <th class="w-60">Nama</th>
        <th class="w-20">Terakhir diubah</th>
        <th class="w-20">Ukuran file</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($files as $file): ?>
        <tr>
          <td><?= $file['name'] ?></td>
          <td><?= date('j M Y', strtotime($file['modifiedByMeTime'])) ?></td>
          <td><?= floor($file['size']/1000) ?> KB</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>