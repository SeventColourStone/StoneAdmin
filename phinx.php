<?php
return [
    "paths" => [
        "migrations" => "database/migrations",
        "seeds"      => "database/seeds"
    ],
    "environments" => [
        "default_migration_table" => "phinx_migrations",
        "default_database"        => 'dev',
        "default_environment"     => "dev",
        "dev" => [
            "adapter" => "mysql",
            "host"    => "129.28.186.67",
            "name"    => 'stone',
            "user"    => 'stone',
            "pass"    => "hSDwYZ2fnSRGjJhR",
            "port"    => 3306,
            "charset" => "utf8mb4"
        ],
        "test" => [
            "adapter" => "mysql",
            "host"    => "127.0.0.1",
            "name"    => 'stone',
            "user"    => 'root',
            "pass"    => "root",
            "port"    => 3306,
            "charset" => "utf8mb4"
        ]
    ]
];