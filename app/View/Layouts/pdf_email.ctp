<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= isset($title_for_layout) ? $title_for_layout : 'Ticket' ?></title>
  <style>
    /* Estilos do PDF (cores, fontes, bordas, etc) */
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }
  </style>
</head>
<body>
  <?= $content_for_layout ?>
</body>
</html>
