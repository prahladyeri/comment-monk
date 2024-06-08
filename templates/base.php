<?php
/**
* base.php
* 
* Base template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GNU General Public License, version 3
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
	<link rel="icon" type="image/x-icon" href="<?=base_url()?>favicon.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
	<link rel="stylesheet" href="<?=base_url()?>static/css/app.css?v=1">
	<title><?=$module?> - <?=APP_NAME?></title>
</head>
<body>
<!-- Image and text -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
	<a class="navbar-brand" href="<?=base_url()?>">
	<img src="<?=base_url()?>favicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
	<?=APP_NAME?>
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>  

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	<div class="navbar-nav">
	<a class="nav-link" href="<?=base_url()?>">Home</a>
	</div>

	<div class='navbar-nav mx-auto'>
	<span class='module-heading nav-link rounded text-dark bg-warning'><?=$module?></span>
	</div>
	
		<div class="navbar-nav">
	<a class="nav-link" href="<?=base_url()?>about">About</a>  
	</div>

	
<?php if (get_user()):?>
	<div class="navbar-nav">
	
	
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Actions
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="<?=base_url()?>pref">Preferences</a>
          <div class="dropdown-divider"></div>
		  <a class="dropdown-item" href="<?=base_url()?>auth/logout">Logout</a>  
        </div>
      </li>	
	</div>
	
<?php else:?>
	<div class="navbar-nav">
	<a class="nav-link" href="<?=base_url()?>auth/login">Login</a>  
	</div>
<?php endif;?>
	
	</div> 
</nav>
<div class='container mt-1'>


<?php foreach($messages as $message):?>
<div class="col-8 ml-auto mr-auto  alert alert-warning alert-dismissible fade show" role="alert">
	<?= $message?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>	
<?php endforeach;?>
<?php foreach($errors as $error):?>
<div class="col-8 ml-auto mr-auto alert alert-danger alert-dismissible fade show" role="alert">
	<?= $error?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>	
<?php endforeach;?>

<?php require($__content_file) ?>
</div>

<footer class="footer bg-dark">
  <div class="text-monospace pl-4 pr-4">
	<span class="text-muted text-left">
	Copyright (c) 2024 <a href='https://prahladyeri.github.io'>Prahlad Yeri</a>. This software is open source and licensed under <a href='https://www.gnu.org/licenses/gpl-3.0.en.html'>GNU GPL v3</a>.</span>
	  <span class='float-right text-light'>
		Version: <?=VERSION?>
	  </span>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" ></script>
</body>
</html>