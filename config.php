<?php
/**
* config.php
*
* App Configuration
*
* @author Prahlad Yeri <prahladyeri@yahoo.com>
* @license MIT
*/

$config = [];

//smtp email
$config['email'] = [];
$config['email']['enabled'] = true;
$config['email']['host'] = '';
$config['email']['username'] = '';
$config['email']['password'] = '';
$config['email']['secure'] = 'tls'; //ssl
$config['email']['port'] = 587;

$config['email']['from'] = ['cm@example.com', 'Comment-Monk'];
