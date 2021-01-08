<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'portal'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => env('DB_CONNECTION2'),
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST2', '172.100.15.200'),
            'port' => env('DB_PORT2', '3306'),
            'database' => env('DB_DATABASE2', 'ppi'),
            'username' => env('DB_USERNAME2', 'root'),
            'password' => env('DB_PASSWORD2', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'api_rstb' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => '',
            'port' => env('DB_PORT2', '3306'),
            'database' => env('DB_DATABASE2', 'ppi'),
            'username' => env('DB_USERNAME2', 'root'),
            'password' => env('DB_PASSWORD2', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => 'https://yayasantelagaamal.org ',
            'port' => '3306',
            'database' => '',
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'portal' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '172.100.15.99'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'portal'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'master' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', '172.100.15.99'),
            'port' => env('DB_PORT', '1433'),
            'database' => 'MasterdataSQL',
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'master_cloud' => [
            'driver' => 'sqlsrv',
            
            'host' => '34.101.184.65',
            'port' => env('DB_PORT', '1433'),
            'database' => 'MasterdataSQL',
            'username' => 'sqlserver',
            'password' => 'telaga@123',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'out' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '172.100.15.99',
            'port' => env('DB_PORT', '1433'),
            'database' => 'PerawatanSQL',
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'in' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '172.100.15.99'),
            'port' => env('DB_PORT', '1433'),
            'database' => 'RawatInapSQL',
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'farmasi' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '172.100.15.99',
            'port' => env('DB_PORT', '1433'),
            'database' => 'Apotik_V1.1SQL',
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'lab' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '172.100.15.99'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'portal'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'rad' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '172.100.15.99'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'RadiologiSQL'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'telaga@123'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
