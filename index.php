<?php
require_once('src/Entities/Film.php');
require_once('autoloader.php');

$user = new Film();
$user->setTitle('Matrix');
$user->setReleaseDate(1999);
$user->setDuration(120);
$user->save();