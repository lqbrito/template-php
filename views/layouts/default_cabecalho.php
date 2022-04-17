<!DOCTYPE html>
<html lang="pt-br" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Luciano Brito Querido">
  <meta name="description" content="">
  <meta name="keywords" content="kw1, kw2, kw3, kwN">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>
  <?php 
    if(isset($titulo))
      echo $titulo;
    else
      echo "Nome da aplicação";
  ?>
  </title>
  <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sticky-footer-navbar/">

  <!-- Bootstrap core CSS -->
  <link href="../themes/default/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../fonts/css/fontawesome.css" rel="stylesheet">
  <link href="../fonts/css/brands.css" rel="stylesheet">
  <link href="../fonts/css/solid.css" rel="stylesheet">

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
  <link rel="icon" href="" type="image/gif" sizes="16x16"> 
</head>
<body class="d-flex flex-column h-100">
  <header>
    <!-- Fixed navbar -->
    <!--<nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd;">-->
    <!--<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">-->
    <!--<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">-->
    <!--<nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">-->
    <!--<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">-->
    <nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd;">
      <a class="navbar-brand" href="../site">Nome da aplicação</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <?php
            if (isset($_SESSION['logged']))
              if ($_SESSION['logged'] == true)
              {
                  ?>
                      <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-database"></i> Cadastros
                            </a>

                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="../controllers/Usuarios.php">
                                    Usuários
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../controllers/Tpclasses.php">
                                    Tipos de classificação
                                </a>
                            </div>
                      </li>
                      
                      <li class="nav-item dropdown ml-3">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-tools"></i> Ferramentas
                            </a>

                      </li>

                      <li class='nav-item ml-3'>
                        <form action='../controllers/Usuarios.php' method='post'>
                          <input type = 'hidden' name = 'operacao' value = 'form/alterarSenha'>
                          <button type = 'submit' class='btn btn-link nav-link'><i class="fas fa-key"></i> Alterar senha</button>
                        </form>
                      </li>
                      <li class='nav-item ml-3'>
                        <form action='../tools/Auth.php' method='post'>
                          <input type = 'hidden' name = 'operacao' value = 'action/logout'>
                          <button type = 'submit' class='btn btn-link nav-link'><strong><i class="fas fa-power-off"></i> Sair</strong></button>
                        </form>
                      </li>

                      <li class="nav-item">
                      
                      </li>

                  <?php
              }          
          ?>          
        </ul>
      </div>
    </nav>
  </header>

  <!-- Begin page content -->
  <main role="main" class="flex-shrink-0">
    <div class="container pt-2">
      <h2>
        <?php
          if (isset($empresa))
            echo $empresa;
          else
            echo "Nome da empresa";
        ?>
      </h2>