<?php
defined('BASEPATH') or exit('No direct script access allowed');

// $config['protocol'] = 'smtp';

// $config['smtp_host'] = 'ssl://smtp.example.com';
// $config['smtp_port'] = 465;
// $config['smtp_user'] = 'no-reply@example.com';
// $config['smtp_pass'] = '***';

$config['sender'] = 'no-reply@example.com';
$config['sender_name'] = 'My Application';

$config['newline'] = "\r\n";
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = true;
$config['wordwrap'] = true;