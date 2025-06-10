<?php
$db = require __DIR__ . '/db.php';
$db['dsn'] = 'mysql:host=host.docker.internal;port=3307;dbname=app_db_test';

return $db;
