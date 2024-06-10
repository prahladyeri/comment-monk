/**
* cm-client.js
*
* Front-end code for comment-monk
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/

// Make sure to place cm-front.js in the base_url
// Make sure to start with "defer" script

var t_url = document.currentScript.src;
var base_url = t_url.substring(0, t_url.lastIndexOf("/static/")) + "/";
var site_url = base_url	+ "index.php/";
var pause = false; // timer

function dom(sel, elem) { //@todo: move to util
	var items;
	if (!elem) {
		items = document.querySelectorAll(sel);
	} else {
		items = elem.querySelectorAll(sel);
	}
	return items;
}

function fetchCommentBox(node, cb) {
	var html = "<div class='cm-comment-container'></div>";
	node.insertAdjacentHTML('beforeend', html);
	
	var turl = site_url + 'fetch_static/partials/comment-box.html?v=1';
	fetch(turl, {method:'get', action: turl})
	.then(resp => resp.text())
	.then(data => {
		node.insertAdjacentHTML('beforeend', data);
		cb();
	});
}

function refreshComments()  {
	if (pause) return;
	var data = new FormData();
	data.append("uri", location.pathname);
	fetch(site_url + "api/fetch_comments", {'method':'POST',
		'body': data
	})
	.then(resp => resp.text())
	.then(data => {
		data = JSON.parse(data);
		if (data.length == 0) return;
		//console.log("data-received:", data);
		for(var i=0; i<data.length; i++) {
			var cmt; var items;
			items = dom(".comment-box.data-id-" + data[i]['id']);
			if (items.length > 0) continue; //already exists
				
			cmt = dom(".comment-box-init")[0].cloneNode(true);
			cmt.classList.remove("comment-box-init");
			cmt.classList.add("data-id-" + data[i]['id']);
			if (data[i].website == '') {
				dom('.cm-author', cmt)[0].textContent = data[i].name;
			}
			else {
				var html = "<a href='"+data[i].website+"'>"+data[i].name+"</a>";
				dom('.cm-author', cmt)[0].innerHTML = html;
			}
			dom('.cm-message', cmt)[0].textContent = data[i].message;
			//+ data[i].email_hash;
			var d = new Date(data[i].created_at);
			var datestr = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes()  + " (GMT)";
			dom('.cm-created_at', cmt)[0].textContent = datestr;
			//items = dom(".comment-box")
			dom(".cm-comment-container")[0].appendChild(cmt);
			console.log("added cmt to root node:", cmt);
		}
	})
	;
}


function import_style() {
	var file = location.pathname.split( "/" ).pop();
	var link = document.createElement( "link" );
	link.href = base_url + "static/css/cm-client.css";
	link.type = "text/css";
	link.rel = "stylesheet";
	link.media = "screen,print";
	document.getElementsByTagName( "head" )[0].appendChild( link );	
}

function init(node) {
	//add css styling
	import_style(base_url);
	
	refreshComments();
	
	// create a box to submit a comment.
	//var turl = base_url + "static/partials/submit-comment.html";
	var turl = site_url + 'fetch_static/partials/submit-comment.html';
	console.log("turl:", turl);
	fetch(turl, {'method':'GET'}) //'mode': 'no-cors'
	.then(response => response.text())
	.then(data => {
		node.insertAdjacentHTML('beforeend', data);
		var box = dom(".cm-submit-comment")[0];
		dom(".cm-comment-form",box)[0].setAttribute("action", site_url + "api/comment");
		dom("#cm_uri")[0].value = location.href;
		dom(".submit-button",box)[0].addEventListener('click', function() {
			console.log("fired:.submit-button.click()");
			var valid = dom(".cm-comment-form",box)[0].reportValidity();
			if (!valid) return;
			dom(".submit-button", box)[0].setAttribute("disabled", true);
			var formNode = this.parentNode;
			console.log("FORM being sent: ", formNode);
			console.log("ACTION: ", formNode.getAttribute('action'));
			console.log("DATA being sent: ", new FormData(formNode));
			fetch(formNode.getAttribute('action'), {
			  method: formNode.getAttribute('method'),
			  body: new FormData(formNode)
			}).then(res=>res.text())
			  .then(function (data) {
					//console.log("data received:" + data);
					dom(".cm-alert",box)[0].textContent = data;
					refreshComments();
					dom(".submit-button",box)[0].removeAttribute("disabled");
					dom(".cm-submit-comment input,.cm-submit-comment textarea", box).forEach((item) => 
					{ 
					if (item.type !== 'hidden')
						item.value = "";
					});
					setTimeout(()=>{
						console.log("now calling setTimeout:");
						dom(".cm-alert", box)[0].textContent = "";
					}, 10000);
			  });
		});
	});
}

var node = document.currentScript.parentNode;
//@todo: fetch existing comments for this url inside a div:
fetchCommentBox(node, function() {
	init(node);	
});
