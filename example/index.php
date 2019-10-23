<?php

require __DIR__ . '/../vendor/autoload.php';


// $file = new mon\store\File;
// echo $file->formatByte(100000000, 4);


// $session = new \mon\store\Session;
// $session->set('aaa', 'asasf');
// echo $session->get('aaa');

// $cookie = new \mon\store\cookie;
// $cookie->set('aaa', ['aa' => 1, 'bb' => 'asdf']);
// var_dump($cookie->get('aaa'));


$cache = new \mon\store\Cache(['path' => __DIR__]);

$c = $cache->set('aa', '123456');
$c = $cache->get('aa');
var_dump($c);