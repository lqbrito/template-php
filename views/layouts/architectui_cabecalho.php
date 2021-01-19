<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Luciano Brito Querido">
    <meta name="description" content="">
    <meta name="keywords" content="kw1, kw2, kw3, kwN">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>
    <?php 
        if(isset($titulo))
          echo $titulo;
        else
          echo "Nome da aplicação";
    ?>        
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="../themes/architectui/main.css" rel="stylesheet">
    <link rel="icon" href="" type="image/gif" sizes="16x16"> 
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <a href="../public"><div class="logo-src"></div></a>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content">
                <div class="app-header-left">
                    <?php
                        if(isset($campoBusca))
                        {
                            ?>
                            <form action="../controllers/<?php echo $controller; ?>" method="post">
                                <div class="input-group input-group-sm">
                                    <input type = "hidden" name = "operacao" value = "action/pesquisar">
                                    <input type="text" class="form-control" id="textobusca" name="textobusca" placeholder="Informe <?php echo $campoBusca; ?>" title="Digite pelo menos <?php echo $tamanhoStringBusca; ?> caracteres" autofocus value="<?php echo $textobusca; ?>">
                                    <span class="input-group-append">
                                        <button type = "submit" class="btn btn-primary btn-flat" id="pesquisar" name = "pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                                
                            </form>
                            <?php
                        }
                    ?>
                    <ul class="header-menu nav">
                        <li class="nav-item">
                            
                        </li>
                        
                    </ul>        
                </div>
                    <?php
                        if (isset($_SESSION['logged']))
                            if ($_SESSION['logged'] == true)
                            {
                                ?>
                                
                                <div class="app-header-right">
                                    <div class="header-btn-lg pr-0">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="btn-group">
                                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                                            <img width="42" class="rounded-circle" src="../themes/architectui/assets/images/avatars/avatar5.png" alt="">
                                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                                        </a>
                                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right p-2">
                                                            <button type="button" tabindex="0" class="dropdown-item">Meu cadastro</button>
                                                            <form action='../controllers/usuarios.php' method='post'>
                                                              <input type = 'hidden' name = 'operacao' value = 'form/alterarsenha'>
                                                              <button type = 'submit' tabindex="0" class="dropdown-item">Alterar senha</button>
                                                            </form>
                                                            <button type="button" tabindex="0" class="dropdown-item">Trocar foto</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget-content-left  ml-3 header-user-info">
                                                    <div class="widget-heading">
                                                        Nome do usuário
                                                    </div>
                                                    <div class="widget-subheading">
                                                        Cargo/Função
                                                    </div>
                                                </div>                                
                                            </div>
                                        </div>
                                    </div>        
                                </div>

                                <?php
                            }          
                    ?>      
            </div>
        </div>        
        <div class="app-main">
                
                <div class="app-sidebar sidebar-shadow">
                    <div class="app-header__logo">
                        <div class="logo-src"></div>
                        <div class="header__pane ml-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>

                    <?php
                        if (isset($_SESSION['logged']))
                            if ($_SESSION['logged'] == true)
                            {
                                ?>

                                <div class="scrollbar-sidebar">
                                    <div class="app-sidebar__inner">
                                        <ul class="vertical-nav-menu">
                                            <li class="app-sidebar__heading">Menu principal</li>
                                            <li <?php if (in_array($pag, [2,3])) echo "class='mm-active'"; ?>>
                                                <a href="#">
                                                    <i class="metismenu-icon pe-7s-server"></i>
                                                    Cadastros
                                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                                </a>
                                                <ul <?php if (in_array($pag, [2,3])) echo "class='mm-show'"; ?>>
                                                    <li>
                                                        <a <?php if ($pag == 2) echo "class='mm-active'"; ?> href="../controllers/usuarios.php"><i class="metismenu-icon"></i>Usuários</a>
                                                    </li>
                                                    <li>
                                                        <a <?php if ($pag == 3) echo "class='mm-active'"; ?> href="../controllers/tpaquisicoes.php"><i class="metismenu-icon"></i>Tipos de aquisições</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="metismenu-icon pe-7s-config"></i>
                                                    Ferramentas
                                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                                </a>
                                                <ul>
                                                    
                                                </ul>
                                            </li>
                                            <li class="app-sidebar__heading">Mais opções</li>
                                            <li>
                                                <a href="../tools/auth.php"><i class="metismenu-icon pe-7s-power"></i>Sair</a>
                                            </li>
                                    </div>
                                </div>

                                <?php
                            }          
                    ?>      
                </div>

                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon p-1">
                                        <?php
                                            if (isset($icone))
                                                echo "<i class='pe-7s-$icone icon-gradient bg-mean-fruit'></i>";
                                            else
                                                echo "<i class='pe-7s-home icon-gradient bg-mean-fruit'></i>";
                                        ?>
                                    </div>
                                    <div>
                                        <?php
                                            if (isset($empresa))
                                                echo $empresa;
                                            else
                                                echo "Nome da empresa";
                                        ?>
                                        <div class="page-title-subheading">
                                        <?php
                                            if (isset($mensagem))
                                                echo $mensagem;
                                            else
                                                echo "Informe aqui uma mensagem qualquer.";
                                        ?>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>            
