<?php
/**
* pref.php
* 
* Pref template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GNU General Public License, version 3
*/
?>

<div class='row mt-2'>
<div class='col-8 ml-auto mr-auto'>
<p class='fw-bold text-muted'>Preferences:</p>
<hr>
<form method='post'>
	<div class='form-row'>
		<div class='form-group col-md-6'>
			<label for='email'>Email:</label>
			<input disabled type='email' id='email' name='email' class='form-control' value='<?=$item['email']?>'>
		</div>
		<div class='form-group col-md-6'>
			<label for='name'>Name:</label>
			<input type='text' id='name' name='name' class='form-control' required value='<?=$item['name']?>'>
		</div>
		<div class='w-100'></div>
		<div class='form-group col-md-12'>
			<label for='website'>Website:</label>
			<input type='url' id='website' name='website' class='form-control' value='<?=$item['website']?>' required>
		</div>
	</div>

	<button class='btn btn-primary'>Save</button>
</form>	
</div>
</div>