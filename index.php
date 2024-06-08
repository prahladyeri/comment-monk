<?php
/**
* index.php
* 
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license GNU General Public License, version 3
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");

require('core/router.php');
require('core/util.php');
require("lib/auth.php");
require("config.php");
session_start();

//@todo: initialize constants and vars

//for easy access to your static paths with base_url()
//Router::$base_url = "http://localhost:8000/src/"; 

//set blank if you rewrite index.php in .htaccess
//Router::$index_file = 'index.php'; 

const VERSION = "1.0";
const APP_NAME = "Comment Monk";

//@todo: initialize database
$dbh = new PDO("sqlite:cm.db");

function create_post ($uri, $user) {
	global $dbh;
	$data= ['uri'=>$uri, 'user_id'=>$user['id']];
	[$sql, $values] = build_insert_query("posts", $data);
	$sth = $dbh->prepare($sql);
	$sth->execute($values);
	return $dbh->lastInsertId();
};


Router::$pre_dispatch = function() use ($dbh) {
	//check install and any other common initialization or redirection
	try {
		$dbh->query("select id from users limit 1;");
	} catch (PDOException $e) {
		//echo "uri_segment:" . uri_segment(-2);
		if (uri_segment(-1) !== "install") {
			header("Location: " . site_url("install"));
			exit();
		}
	}
	error_log("get_uri():" . get_uri());
	if (get_user()) return;
	if (uri_segment(-2) === 'auth') return;
	if (uri_segment(-2) === 'api') return;
	if (uri_segment(-1) === 'install') return; 
	if (strpos(get_uri(), '/fetch_static/') !== false) return;
	header("Location: " . site_url("auth/login"));
	exit();
};

Router::post('/api/fetch_comments', function() use ($dbh) {
	$comments = [];
	$sth = $dbh->prepare("select a.* from comments a, posts b where a.post_id=b.id and b.uri=:uri");
	$sth->execute(['uri'=>$_POST['uri']]);
	foreach($sth->fetchAll() as $row) {
		$row['email_hash'] = md5($row['email']);
		$comments[] = $row;
	}
	echo json_encode($comments);
});

Router::post("/action/delete_comment", function() use ($dbh) {
	//error_log("POST[id]: " . print_r($_POST['id'],true));
	//error_log("GETTYPE:" . print_r(gettype($_POST['id']), true));
	//explode(',', $_POST['id'][0])
	foreach($_POST['id'] as $tid) {
		//error_log("DELETING: $tid...");
		$sth = $dbh->prepare("delete from comments where id=:id");
		$sth->execute(array(':id'=>$tid));
		//$sth->fetch();
		//error_log("error: " . print_r($dbh->errorInfo(), true));
	}
	echo "Record(s) Deleted.";
});

Router::post("/api/comment", function() use ($dbh) {
	$action = uri_segment(-1);
	$uri = $_POST["cm_uri"];
	$parts = parse_url($uri); //validate first
	$host = $parts['host'];
	$host .= ($parts['port']==80 ? "" : ":".$parts['port']);
	$host = $parts['scheme'].'://'.$host.'/';
	$sth = $dbh->prepare("select id from users where website=:host");
	$sth->execute(["host"=> $host]);
	$user = $sth->fetch();
	if (!$user) die("Unrecognized host: ". $host);
	
	$sth = $dbh->prepare("select id from posts where user_id=:user_id and uri=:uri");
	$sth->execute(["user_id"=>$user['id'],
		"uri"=>$parts['path'],
	]);
	$row = $sth->fetch();
	if ($row) {
		$post_id = $row['id'];
		//echo "Post found.\n";
	}
	else {
		$post_id = create_post($parts['path'], $user);
		//echo "Created post $post_id.\n";
	}
	$data= [];
	$data['post_id'] = $post_id;
	$data['name'] = $_POST["cm_name"];
	$data['email'] = $_POST["cm_email"];
	$data['message'] = $_POST["cm_message"];
	$data['ip'] = $_SERVER["REMOTE_ADDR"];
	$data['website'] = $_POST["cm_website"];
	[$sql, $values] = build_insert_query("comments", $data);
	$sth= $dbh->prepare($sql);
	$sth->execute($values);
	//sleep(2);
	echo "Comment added!";
});

Router::get("/fetch_static/*", function(){
	//$fname = uri_segment(-1);
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$fname =  substr($uri, strpos($uri, "fetch_static/") + 13);
	echo file_get_contents("static/$fname");
});

Router::get("/install", function() use ($dbh) {
	$vars = default_vars("Install");
	try {
		//echo 'now querying..';
		$dbh->query("select id from users limit 1;");
		$vars['messages'][] = "Application is already installed..";
		$vars['installed'] = true;
	} catch (PDOException $e) {
		$vars['installed'] = false;
	}
	
	load_template("templates/install.php", $vars);
});

Router::post("/install", function() {
	global $dbh;
	$vars = default_vars("Install");
	$log = '';
	try {
		$dbh->query("select id from users limit 1;");
		$log .= "Application already installed..";
	} catch (PDOException $e) {
		$sql = clean_sql( file_get_contents("init.sql") );
		//file_put_contents("init-clean.sql", $sql);
		$parts = explode(";", $sql);
		for($i=0; $i<count($parts); $i++) {
			$ss = $parts[$i];
			if ($ss === "") continue;
			$log.= "$ss;\n\n";
			try{
				$dbh->query($ss);
			} catch (PDOException $e) { $log.=$e->getMessage() . "\n\n"; }
		}
		$log .= "creating user:\n\n";
		if ( substr($_POST['website'], -1) !== '/' )
			$_POST['website'] .= '/';
		
		$data=  array(
			"email"=>$_POST['email'],
			'pwd_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT ),
			"name"=>$_POST['name'],
			"website"=>$_POST['website'],
		);
		$q = build_insert_query("users", $data);
		[$sql, $values] = $q;
		$sth = $dbh->prepare($sql);
		$sth->execute($values);
		$log .= "$sql\n";
		//$log .= $sth::rowCount . " rows affected.\n";
		//$log .= print_r($values, true) . "\n";
		$log .= "lastInsertId: ".$dbh->lastInsertId()."\n";
		$log .= "Install complete.\n";		
	}
	$vars['install_log'] = $log;
	$vars['installed'] = true;
	load_template("templates/install.php", $vars);
});
// home
Router::get("/", function(){
	global $dbh;
	$vars = default_vars("Home");
	$sth = $dbh->prepare("select a.id, b.uri, name, message, email, website, ip, approved from comments a, posts b where a.post_id=b.id and b.user_id=:user_id order by a.created_at desc;");
	$sth->execute(['user_id' => get_user()['id']]);
	$vars['comments'] = $sth->fetchAll();
	load_template('templates/home.php', $vars);
});
Router::get("/pref", function(){
	$vars = default_vars("Preferences");
	//$sth = $dbh->prepare("select * ");
	//$row = $sth->fetch();
	$vars['item'] = $_SESSION["user"]; //$row;
	load_template("templates/pref.php", $vars);
});
Router::post("/pref", function() use ($dbh) {
	$vars = default_vars("Preferences");
	//error_log("WEBSITE is ". $_POST['website'] );
	//error_log("last char is ".substr($_POST['website'], -1));
	if ( substr($_POST['website'], -1) !== '/' )
		$_POST['website'] .= '/';
	$_SESSION['user']['name'] = $_POST['name'];
	$_SESSION['user']['website'] = $_POST['website'];
	$data = ['name'=>$_POST['name'],
		'website'=>$_POST['website'],
	];
	[$sql, $vals] = build_update_query("users", $data, $_SESSION['user']['id']);
	$sth = $dbh->prepare($sql);
	$sth->execute($vals);
	$vars['item'] = $_SESSION["user"]; //$row;
	$vars['messages'][] = "Record updated.";
	load_template("templates/pref.php", $vars);
});

Router::get("/about", function() use($dbh) {
	$vars = default_vars("About");
	$vars['sqlite_ver'] = $dbh->query('select sqlite_version()')->fetch()[0];
	load_template("templates/about.php", $vars);
});


// auth
Router::get("/auth/logout", function(){
	unset($_SESSION['user']);
	header("Location: ". base_url());
});
Router::get("/auth/login", function(){
	$vars = default_vars("Login");
	load_template("templates/login.php", $vars);
});
Router::post("/auth/login", function(){
	global $dbh;
	$vars = default_vars("Login");
	$sth= $dbh->prepare("select * from users where email=:email");
	$sth->execute([$_POST['email']]);
	$rows = $sth->fetchAll();
	if (count($rows) > 0) {
		$pwdh = $rows[0]['pwd_hash'];
		if (password_verify($_POST["password"], $pwdh)) {
			$_SESSION['user'] = $rows[0];
			//@todo: 
			exit( header("Location: ". base_url()) );
		}
		$vars['errors'][] = "Incorrect email or password.";
	} else {
		$vars['errors'][] = "Incorrect email or password.";
	}
	load_template("templates/login.php", $vars);
});


Router::dispatch();