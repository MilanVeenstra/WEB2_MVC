<?php
return [
    // DSN voor SQLite: pad naar database-bestand
    'dsn' => 'sqlite:' . __DIR__ . '/../database/database.db',
    // Optioneel: gebruikersnaam/wachtwoord (niet nodig voor SQLite)
    'user' => null,
    'password' => null,
    // PDO opties
    'options' => [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
