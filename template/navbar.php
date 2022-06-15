<!-- navbar  -->
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <!-- navbar toggler -
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
  </ul>
  -->
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </form>

  <ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      <img alt="image" src="<?= base_url('/asset/img/'); ?>/<?= getDataUser('foto_profile'); ?>" class="rounded-circle mr-1">
      <div class="d-sm-none d-md-inline-block">Hi, <?= getDataUser('nama_user'); ?></div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= base_url(); ?>/profile/index.php" class="dropdown-item has-icon">
          <i class="far fa-user"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <!--
        <a href="<?= base_url(); ?>/logout/index.php" class="dropdown-item has-icon text-danger" onclick="return confirm('yakin ingin keluar??')">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        -->
        <button type="button" data-href="<?= base_url(); ?>/logout/index.php" class="dropdown-item has-icon text-danger d-block  d-flex align-items-center" onclick="confirmLogout(this)">
          <i class="fas fa-sign-out-alt "></i>  Logout
        </button>
      </div>
    </li>
  </ul>
</nav>


<!-- slidebar -->
<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="">
        <?= $nama_apilkasi_global; ?>
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="">Ln</a>
    </div>
    <ul class="sidebar-menu">
      <!-- dashboard -->
      <li class="menu-header">
        Dashboard
      </li>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/dashboard/index.php">
        <i class="fas fa-tachometer-alt"></i> 
        <span>Dashboard</span>
        </a>
     </li>
      
      <?php if(getDataUser("level") == "admin" || getDataUser("level") == "kasir") : ?>
      <!-- master -->
      <li class="menu-header">
        Master Data
      </li>
      <?php endif; ?>
      
      <?php if(getDataUser("level") == "admin") : ?>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/outlet/index.php">
        <i class="fas fa-fire"></i> 
        <span>Outlet</span>
        </a>
     </li>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/paket/index.php">
        <i class="fas fa-box"></i> 
        <span>Paket</span>
        </a>
     </li>
     <?php endif; ?>
     
      <?php if(getDataUser("level") == "admin" || getDataUser("level") == "kasir") : ?>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/pelanggan/index.php">
        <i class="fas fa-users"></i> 
        <span>Pelanggan</span>
        </a>
     </li>
     
     
      <!-- TRANSAKSI -->
      <li class="menu-header">
        Transaksi
      </li>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/transaksi/index.php">
        <i class="fas fa-plus"></i> 
        <span>Tambah Transaksi</span>
        </a>
     </li>
     
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/pesanan/index.php">
        <i class="fas fa-tshirt"></i> 
        <span>Cucian</span>
        </a>
     </li>
     <?php endif; ?>
     
      <?php if(getDataUser("level") == "admin") : ?>
      <!-- users -->
      <li class="menu-header">
        Users
      </li>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/users/index.php">
        <i class="fas fa-user"></i> 
        <span>Users</span>
        </a>
     </li>
     <?php endif; ?>

      
      <!-- laporan -->
      <li class="menu-header">
        Laporan
      </li>
      <li>
        <a class="nav-link" href="<?= base_url(); ?>/laporan/index.php">
        <i class="fas fa-file"></i> 
        <span>Laporan</span>
        </a>
     </li>
      
    </ul>
  </aside>
</div>