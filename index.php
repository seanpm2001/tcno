<?php 

require 'Tcno.php';

$tc = new Tcno;

$tcno = $tc->create();

$validate = $tc->validate($tcno);

var_dump($tcno, $validate);