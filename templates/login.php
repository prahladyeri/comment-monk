<?php
/**
* login.php
* 
* Login template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/
?>

<div class='row mt-2'>
<div class='col-8 ml-auto mr-auto'>
<p class='fw-bold text-muted'>Login:</p>
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

	<button class='btn btn-primary'>Login</button>
</form>	
</div>
</div>