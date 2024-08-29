<?php 
$service = (array) $service;
$service = (array) $service['187'];
$names = explode("/", $service['name']);
$cost = $d->money_format($r->valuedPrice($key, $service['cost']));