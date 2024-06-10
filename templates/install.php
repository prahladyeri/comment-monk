<?php
/**
* install.php
* 
* Install template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/
?>
<div class='row mt-2'>
<div class='col-8 ml-auto mr-auto'>
<?php if (get_method() === "GET" && !$installed):?>
<p class='fw-bold text-muted'>Let's create the Admin user:</p>
<hr>
<form method='post'>
	<div class='form-row'>
		<div class='form-group col-md-6'>
			<label for='email'>Email:</label>
			<input type='email' id='email' name='email' class='form-control' required>
		</div>
		<div class='form-group col-md-6'>
			<label for='password'>Password:</label>
			<input type='password' id='password' name='password' class='form-control' required>
		</div>
	</div>
	<div class='form-row'>
		<div class='form-group col-md-6'>
			<label for='name'>Full Name:</label>
			<input type='text' id='name' name='name' class='form-control'  required>
		</div>
		<div class='form-group col-md-6'>
			<label for='website'>Website:</label>
			<input type='url' id='website' name='website' class='form-control' required>
		</div>
	</div>
	<button class='btn btn-primary'>Register</button>
</form>
<?php elseif (get_method() === 'POST'): ?>
	<div class='row mb-2'>
	<div class='col-6'>
	Install Log:
	</div>
	<div class='col-6 text-right'>
	<a class='btn btn-success' href="<?=base_url()?>auth/login">Proceed to Login</a>
	</div>
	</div>
<pre class='bg-secondary text-light rounded px-3 py-3' style='height: 450px;'>
<?= $install_log ?>
</pre>
<?php endif;?>
</div>
</div>