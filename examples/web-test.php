<?php
/**
 * Web Example
 *
 * @author Sven Kuegler <sven.kuegler@gmail.com>
 */

require '../src/HtpasswdGenerator.php';

$g = new HtpasswdGenerator("secure/.htpasswd");

$g->add("testuser1", "123456");
$g->add("testuser2", "123456");
$g->add("testuser3", "123456");

$g->delete("testuser2");

$g->add("testuser3", "newpassword");

echo "<pre>" . print_r($g, true) . "</pre>";