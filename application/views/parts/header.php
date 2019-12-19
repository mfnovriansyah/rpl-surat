<!DOCTYPE html>
<html>
<?php require('head.php'); ?>

<body class="hold-transition skin-blue <?=(!isset($simple) ? 'sidebar-mini' : '')?> <?=(isset($add_body_class) ? $add_body_class : '');?>">
<?php if(!isset($simple)) { ?>
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?=base_url();?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D'</b>Office</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>D'</b>Office</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!--img src="<?=asset_url('dist/img/user2-160x160.jpg');?>" class="user-image" alt="User Image"-->
							<i class="fa fa-user"></i>
              <span class="hidden-xs"><?=$this->session->name;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!--img src="<?=asset_url('dist/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image"-->
								<i class="fa fa-user" style="color: #fff;font-size: 55pt;"></i>
                <p>
                  <?=$this->session->name;?>
									<br><?=$this->session->role;?>
                  <small><?=$this->session->email;?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?=base_url('users/profile');?>" class="btn btn-default btn-flat">Profile<?php if($this->session->role_SSO==''){echo ' & Password';}?></a>
                </div>
								<div class="pull-right">
                  <a href="<?=base_url('logout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!--div class="user-panel">
        <div class="pull-left image">
          <img src="<?=asset_url('dist/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$this->session->name;?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div-->
      <!-- search form -->
      <!--form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <!--li class="header">MAIN NAVIGATION</li-->
				<li><a href="<?=base_url('dashboard');?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
				<?php if($this->session->role=='admin'){ ?>
        <!-- <li><a href="<?=base_url('rooms');?>"><i class="fa fa-building"></i> <span>Rooms</span></a></li><?php } ?> -->
				<!-- <li><a href="<?=base_url('calendar');?>"><i class="fa fa-calendar"></i> <span>Bookings Calendar</span></a></li> -->
			<!-- 	<li class="treeview ">
					<a href="#"><i class="fa fa-users"></i> <span>Meetings</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="<?=base_url('meetings');?>"><i class="fa fa-users"></i> <span> -->
						<?php 
							// if($this->session->role=='admin'){ echo 'Meetings &amp; Room Bookings';}
							// elseif($this->session->role=='dosen'){echo 'Meetings';}
							// else{echo 'Room Bookings';}
						?>
						</span></a></li>
						<?php // if($this->session->role=='admin'){ ?>
						<!--li><a href="<?=base_url('meetings/list_undangan');?>"><i class="fa fa-envelope"></i> <span>Invitations</span></a></li-->
						<!-- <li><a href="<?=base_url('meetings/list_mom');?>"><i class="fa fa-file"></i> <span>Minutes of Meetings</span></a></li> -->
						<?php // } ?>
			<!-- 		</ul>
				</li> -->
				<?php { ?>
				<li class="treeview ">
					<a href="#"><i class="fa fa-inbox"></i> <span>Surat Keterangan</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<?php if($this->session->role=='admin'){ ?>
						<li><a href="<?=base_url('keterangan/jenis');?>"><i class="fa fa-list"></i> <span>Template</span></a></li>
            <li><a href="<?=base_url('keterangan/activity_log');?>"><i class="fa fa-inbox"></i> <span>Activity Log</span></a></li>
						<?php } ?>
						<li><a href="<?=base_url('keterangan');?>"><i class="fa fa-inbox"></i> <span>Pengajuan</span></a></li>

					</ul>
				</li>
				
		<!-- 	<li class="treeview ">
					<a href="#"><i class="fa fa-graduation-cap"></i> <span>KP/Seminar/TA</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu"> -->
						<!--li><a href="<?=base_url('ta/kp');?>"><i class="fa fa-wrench"></i> <span>Pengajuan KP</span></a></li>
						<li><a href="<?=base_url('ta/seminar');?>"><i class="fa fa-copy"></i> <span>Pengajuan Seminar</span></a></li>
						<li><a href="<?=base_url('ta/skripsi');?>"><i class="fa fa-copy"></i> <span>Pengajuan Skripsi</span></a></li>
						<li><a href="<?=base_url('ta/tesis');?>"><i class="fa fa-graduation-cap"></i> <span>Pengajuan Tesis</span></a></li>
						<li><a href="<?=base_url('ta/disertasi');?>"><i class="fa fa-graduation-cap"></i> <span>Pengajuan Disertasi</span></a></li-->
<!-- 						<li><a href="<?=base_url('ta/paper');?>"><i class="fa fa-sticky-note"></i> <span>Penilaian Paper</span></a></li>
						<li><a href="<?=base_url('ta/transfer');?>"><i class="fa fa-exchange-alt"></i> <span>Transfer SKS</span></a></li>
					</ul>
				</li> -->
				<!--li class="treeview ">
					<a href="#"><i class="fa fa-inbox"></i> <span>Cuti, Ujian, Peminjaman</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="<?=base_url('cuti');?>"><i class="fa fa-list"></i> <span>Pengajuan Cuti</span></a></li>
						<li><a href="<?=base_url('ujian_susulan');?>"><i class="fa fa-list"></i> <span>Pengajuan Ujian Susulan</span></a></li>
					</ul>
				</li-->
				
			<!-- 	<li class="treeview ">
					<a href="#"><i class="fa fa-table"></i> <span>Inventaris</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="<?=base_url('pinjam/barang');?>"><i class="fa fa-wrench"></i> <span>Daftar Inventaris</span></a></li>
						<li><a href="<?=base_url('pinjam');?>"><i class="fa fa-copy"></i> <span>Peminjaman</span></a></li>
					</ul>
				</li>
				
				<li class="treeview ">
					<a href="#"><i class="fa fa-group"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="<?=base_url('users/jabatan');?>"><i class="fa fa-user"></i> <span>Jabatan</span></a></li>
						<li><a href="<?=base_url('users');?>"><i class="fa fa-group"></i> <span>Semua</span></a></li>
						<li><a href="<?=base_url('users/dosen');?>"><i class="fa fa-graduation-cap"></i> <span>Dosen</span></a></li>
						<li><a href="<?=base_url('users/pegawai');?>"><i class="fa fa-user"></i> <span>Staff</span></a></li>
					</ul>
				</li> -->
				<?php } ?>
				
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$title;?>
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?=$title;?></li>
      </ol>
    </section>
<?php } ?>