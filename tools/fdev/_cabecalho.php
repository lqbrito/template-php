<!doctype html>
<html lang="pt-br" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Luciano Brito Querido">
  <meta name="generator" content="Jekyll v4.1.1">
  <title>
  <?php 
    if(isset($titulo))
      echo $titulo;
    else
      echo "FDev - Fast Development in Laravel PHP";
  ?>
  </title>
  <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sticky-footer-navbar/">

  <!-- Bootstrap core CSS -->
  <link href="../../themes/default/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../fonts/css/fontawesome.css" rel="stylesheet">
  <link href="../../fonts/css/brands.css" rel="stylesheet">
  <link href="../../fonts/css/solid.css" rel="stylesheet">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <!-- Custom styles for this template -->
  <link href="sticky-footer-navbar.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
  <header>
    <!-- Fixed navbar -->
    <!--nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm"-->
    <!--nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm"-->
    <!--nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm"-->
    <!--nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark"-->
    <nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd;">
      <a class="navbar-brand" href="index.php">FDevPHP</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item <?php if ($pag == 2) echo "active" ?>">
            <a class="nav-link" href="layouts.php">Layouts</a>
          </li>
          <li class="nav-item <?php if ($pag == 3) echo "active" ?>">
            <a class="nav-link" href="namespaces.php">Namespaces</a>
          </li>
          <li class="nav-item <?php if ($pag == 4) echo "active" ?>">
            <a class="nav-link" href="scripts.php">Scripts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../public">Sair</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Begin page content -->
  <main role="main" class="flex-shrink-0">
    <div class="container pt-2">
      <h1><?php echo $titulo; ?></h1>