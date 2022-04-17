<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Monsterlite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Monster admin lite design, Monster admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Monster Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>
        <?php 
            if(isset($titulo))
              echo $titulo;
            else
              echo "Nome da aplicação";
        ?>
    </title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/monster-admin-lite/" />
    <link rel="icon" type="image/png" sizes="16x16" href="../themes/monster-admin/assets/images/favicon.png">
    <link href="../themes/monster-admin/monster-html/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <a class="navbar-brand" href="../site">
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <img src="../themes/monster-admin/assets/images/logo-icon.png" alt="homepage" class="dark-logo" />

                        </b>
                        <span class="logo-text">
                            <img src="../themes/monster-admin/assets/images/logo-text.png" alt="homepage" class="dark-logo" />

                        </span>
                    </a>
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav me-auto mt-md-0 ">

                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item hidden-sm-down">

                        </li>

                    </ul>

                    <!--
                    <ul class="navbar-nav me-auto mt-md-0 ">
                        <li class="nav-item hidden-sm-down">
                            <form class="app-search ps-3">
                                <input type="text" class="form-control" placeholder="Search for..."> <a
                                    class="srh-btn"><i class="ti-search"></i></a>
                            </form>
                        </li>
                    </ul>
                    -->

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../themes/monster-admin/assets/images/users/1.jpg" alt="user" class="profile-pic me-2">Nome do usuário <i class="fas fa-chevron-circle-down ml-2"></i>
                            </a>
                            <?php
                                if (isset($_SESSION['logged']))
                                    if ($_SESSION['logged'] == true)
                                    {
                                        ?>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <li>
                                                <button type="button" tabindex="0" class="dropdown-item"><i class="fas fa-user-circle"></i> Meu cadastro</button>
                                            </li>
                                            <li>
                                                <form action='../controllers/Usuarios.php' method='post'>
                                                    <input type = 'hidden' name = 'operacao' value = 'form/alterarSenha'>
                                                    <button type = 'submit' tabindex="0" class="dropdown-item"><i class="fas fa-key"></i> Alterar senha</button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" tabindex="0" class="dropdown-item"><i class="fas fa-user"></i> Trocar foto</button>
                                            </li>
                                        </ul>
                                    <?php
                                }          
                            ?>      
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i class="me-3 fas fa-database fa-fw"
                                    aria-hidden="true"></i><span class="hide-menu">Cadastros</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="../tools/Auth.php" aria-expanded="false"><i class="me-3 fas fa-wrench"
                                    aria-hidden="true"></i><span class="hide-menu">Ferramentas</span></a></li>
                        
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="../tools/Auth.php" aria-expanded="false"><i class="me-3 fas fa-power-off"
                                    aria-hidden="true"></i><span class="hide-menu">Sair</span></a></li>
                    </ul>

                </nav>
            </div>
        </aside>

        <div class="page-wrapper">
            
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">
                            <?php
                                if (isset($empresa))
                                    echo $empresa;
                                else
                                    echo "Nome da empresa";
                            ?>
                        </h3>

                        <!--
                            <div class="d-flex align-items-center">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="../../site">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Blank Page</li>
                                    </ol>
                                </nav>
                            </div>
                        -->
                    </div>
                </div>
            </div>
            

            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->