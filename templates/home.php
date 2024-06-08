<?php
/**
* home.php
* 
* Home template
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GNU General Public License, version 3
*/
?>

<div class='row'>
	<div class='col-12'>
		<button class='mb-2 btn btn-danger btn-delete'><span class='fa fa-remove'></span> Delete selected</button>
		<button class='mb-2 btn btn-secondary btn-snippet'><span class='fa fa-code'></span> Client Snippet</button>
		<table class='table table-sm table-bordered tbl-main'>
			<tr>
				<th></th>
				<th>URI</th>
				<th>Message</th>
				<th>Posted By</th>
				<th>Website</th>
				<th>IP</th>
			</tr>
<?php if (count($comments) == 0):?>
	<span>No comments found for the site <a href="<?= $_SESSION['user']['website']?>"><?= $_SESSION['user']['website']?></a></span>
<?php else: ?>
	<span>Comments found for the site <a href="<?= $_SESSION['user']['website']?>"><?= $_SESSION['user']['website']?></a></span>
	<?php foreach($comments as $cmt): ?>
			<tr>
				<td><input class='' value='foo' type='checkbox' id='<?=$cmt['id']?>'></td>
				<td><?=$cmt['uri']?></td>
				<td><?=$cmt['message']?></td>
				<td><?= ($cmt['email']=="" ? $cmt['name'] : "<a href='mailto:".$cmt['email']."'>".$cmt['name']."</a>"  ) ?></td>
				<td><?= ($cmt['website']=="" ? "" : "<a href='".$cmt['website']."'>".$cmt['website']."</a>"  ) ?></td>
				<td><?=$cmt['ip']?></td>
			</tr>
	<?php endforeach; ?>
<?php endif; ?>
		</table>
	</div>
</div>

<script type='module'>
	function showSnippet() {
		$(".modal-snippet").modal();
	}

	$("body").on("click", ".btn-snippet", function() {
		if ($(".modal-snippet").length == 0) {
			console.log("importing dialog content");
			fetch("<?=base_url()?>static/partials/snippet.html?v=<?=VERSION?>")
			.then(resp => resp.text())
			.then(data => {
				console.log("content received.");
				data = data.replace("{{src}}", "<?=base_url()?>static/js/cm-client.js");
				$("body").append(data);
				showSnippet();
			});
		} else {
			showSnippet();
		}
	});

	$("body").on("click", ".btn-delete", function() {
		if ($(".tbl-main tr input:checked").length == 0) {
			alert("No records selected.");
			return;
		}
		var c = confirm("Are you sure you want to delete these records?");
		if (!c) return;
		var data = new FormData();
		var $this = $(this);
		$(".tbl-main tr").each(function(){
			var chk = $(this).find("input[type='checkbox']").is(':checked');
			console.log('chk:' ,chk);
			//console.log('control:', $(this).find("input[type='checkbox']"));
			if (chk) {
				//var id = $(this).find("input[type='checkbox']").attr('id');
				data.append('id[]', $(this).find("input[type='checkbox']").attr('id'));
				//alert("id:" + id);
			}
		});
		//data.append("id[]", id);
		fetch("<?=base_url()?>action/delete_comment", {
			"method": 'post',
			"body": data
		})
		.then(resp => resp.text())
		.then(rdata => {
			alert(rdata);
			//$this.remove();
			data.forEach((v) => {
				//console.log("now removing ", v);
				//console.log("selector: ", ".tbl-main input[id='"+v+"']");
				$(".tbl-main input[id='"+v+"']").parent().parent().remove();
			});
		})
		;
	});
</script>