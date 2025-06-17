<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\Database\Connection;
use App\DataMapper\UserMapper;
use App\Model\User;

// 1) Laad DB-config
$dbConfig   = require __DIR__ . '/../config/database.php';
// 2) Maak een Connection
$connection = new Connection(
    $dbConfig['dsn'],
    $dbConfig['user'],
    $dbConfig['password'],
    $dbConfig['options']
);
// 3) Maak UserMapper
$mapper = new UserMapper($connection);

// 4) Vervang deze waarden door wat jij wil
$username = 'milan';
$password = 'secret';

// 5) Hash het wachtwoord en insert
$user = new User(null, $username, password_hash($password, PASSWORD_DEFAULT));
$mapper->insert($user);

echo "Account aangemaakt:\n";
echo "   ID:       " . $user->getId() . "\n";
echo "   Username: " . $user->getUsername() . "\n";
echo "   Password: " . $password . "\n";
