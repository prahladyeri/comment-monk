<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="#aboutApp" data-toggle='tab'>About App</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#versionHistory" data-toggle='tab'>Version History</a>
  </li>
</ul>

<div class='tab-content'>
<div role='tabpanel' id='aboutApp' class='mt-4 tab-pane active' >
BUILD VERSION: <?= VERSION ?><br>
Timezone: <?= date_default_timezone_get(); ?><br><br>
<strong><?=APP_NAME ?> is built on top of following open source technologies:</strong>
<br><br>
<table class='table table-sm table-striped table-bordered'>
<tr>
<th>Component</th>
<th>Version</th>
<th>Website</th>
<th></th>
</tr>
<?php foreach($deps as $dep):?>
<tr>
<td><?=$dep[0]?></td>
<td><?=$dep[1]?></td>
<td><a href="<?=$dep[2]?>"><?=$dep[2]?></a></td>
<td><button data-dep="<?=$dep[0]?>" class='btn btn-sm btn-secondary btn-view-license'>View License</button></td>
</tr>
<?php endforeach;?>
</table>

MMVC VERSION: <?=MMVC_VER?><br>
PHP VERSION: <?= phpversion(); ?><br>
SQLITE VERSION: <?=$sqlite_ver?><br>
</div>

<div role='tabpanel' id='versionHistory' class='mt-4 tab-pane'>
<pre><?= file_get_contents("CHANGE.log"); ?></pre>
</div>
</div>

<script type='module'>
$(".btn-view-license").click(function(){
	var dep = $(this).attr('data-dep');
	var url = "<?=base_url()?>licenses/" + dep.toLowerCase() + ".txt";
	$.get(url, function(data){
		//alert(data);
		$(".modal-view-license .modal-body code").text(data);
		$(".modal-view-license").modal();
	});
	
});
</script>

<div class="modal modal-view-license" tabindex="-1" >
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View License</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<pre class='pl-2 pr-2 border' style='white-space: pre-wrap; background-color: honeydew; max-height: 350px' >
		<code></code>
		</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>