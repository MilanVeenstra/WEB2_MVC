<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\Database\Connection;
use Framework\Database\Query;
use Framework\Database\IdentityMap;
use App\DataMapper\UserMapper;
use App\Repository\UserRepository;
use App\Model\User;

// 1) Setup DB-connection
$dbConfig = require __DIR__ . '/../config/database.php';
$conn     = new Connection(
    $dbConfig['dsn'], $dbConfig['user'], $dbConfig['password'], $dbConfig['options']
);

// 2) Maak mapper, identity map en repository
$mapper      = new UserMapper($conn);
$identityMap = new IdentityMap();
$repo        = new UserRepository($mapper, $identityMap);

// 3) Insert via repository
// genereer een unieke username per run
$username = 'tester_' . uniqid();
$user = new \App\Model\User(null, $username, password_hash('pw', PASSWORD_DEFAULT));
$repo->save($user);
echo "Saved via repo, ID={$user->getId()}\n\n";

// na het aanmaken van $mapper en eventueel $repo:
echo "\nFIND BY USERNAME:\n";
$userByName = $mapper->findByUsername('tester');
if ($userByName !== null) {
    echo "Gevonden: ID={$userByName->getId()}, username={$userByName->getUsername()}\n";
} else {
    echo "Gebruiker niet gevonden\n";
}


// 4) Zoek via Query
$query = (new Query())->where('id', $user->getId());
$found = $repo->find($query);

// 5) Toon resultaat
var_dump($found);
