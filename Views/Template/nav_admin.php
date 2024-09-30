<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media(); ?>/images/avatar.png" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"> <?= $_SESSION['userData']['nombre']; ?></p>
          <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['nombre_rol']; ?></p>
        </div>
      </div>
      <ul class="app-menu">
        <li>
          <?php if(!empty($_SESSION['permisos'][1]['r'])){ ?>
            <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][2]['r'])){ ?>
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">                
                <i class="app-menu__icon fa fa-users" aria-hidden="true"></i>
                <span class="app-menu__label">Usuarios</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle-o"></i> Roles</a></li>
          </ul>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][3]['r'])){ ?>       
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/clientes">                
                <i class="app-menu__icon fa fa-user" aria-hidden="true"></i>
                <span class="app-menu__label">Clientes</span>
            </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][4]['r']) || !empty($_SESSION['permisos'][6]['r'])) { ?>
          <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">                
                <i class="app-menu__icon fa-solid fa-shop" aria-hidden="true"></i>
                <span class="app-menu__label">Tienda</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
          <ul class="treeview-menu">
          <?php if(!empty($_SESSION['permisos'][4]['r'])){ ?>
            <li><a class="treeview-item" href="<?= base_url(); ?>/uniformes"><i class="icon fa fa-circle-o"></i> Uniformes</a></li>
            <?php } ?>
            <?php if(!empty($_SESSION['permisos'][6]['r'])){ ?>
            <li><a class="treeview-item" href="<?= base_url(); ?>/modelos"><i class="icon fa fa-circle-o"></i> Modelos</a></li>
            <?php } ?>
            <?php if(!empty($_SESSION['permisos'][6]['r'])){ ?>
            <li><a class="treeview-item" href="<?= base_url(); ?>/tipopago"><i class="icon fa fa-circle-o"></i> Tipos de pago</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/pedidos">               
                <i class="app-menu__icon fa-solid fa-truck-fast" aria-hidden="true"></i>
                <span class="app-menu__label">Pedidos</span>
            </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/ventas">               
            <i class="app-menu__icon fa fa-shopping-cart" aria-hidden="true"></i>                                   
                <span class="app-menu__label">Ventas</span>
            </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][5]['r'])){ ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/auditoria">               
            <i class="app-menu__icon fa-solid fa-eye" aria-hidden="true"></i>                            
                <span class="app-menu__label">Auditoria</span>
            </a>
        </li>
        <?php } ?>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/logout">                
                <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="app-menu__label">Cerrar Sesión</span>
            </a>
        </li>
      </ul>
    </aside>