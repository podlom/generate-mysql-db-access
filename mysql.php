<?php

/**
 * Created by PhpStorm.
 * @author: Shkodenko Taras <taras@shkodenko.com>
 * Date: 21.12.2017
 * Time: 16:34
 *
 * Command line interface to generate MySQL database & user setup SQL queries
 * @example usage $ php mysql.php -ptest
 * @example usage $ php mysql.php -ptest -hdbhost
 */

require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use GuzzleHttp\Client;

/**
 * Get a random password of random length 10 - 16 characters
 *
 * @return string
 */
function getDbPass()
{
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://rndpwd.info/',
        // You can set any number of default request options.
        'timeout'  => 2.0,
    ]);

    $passLen = rand(10, 16);

    $response = $client->request('POST', '/index.php', [
        'form_params' => [
            'len' => $passLen,
            'lower' => 1,
            'upper' => 1,
            'digit' => 1,
            'special' => 1,
            'bracket' => 1,
        ]
    ]);

    $body = (string) $response->getBody();

    $jsonArr = json_decode($body, 1);

    return $jsonArr['randomPassword'];
}

/**
 * Generate setup MySQL database and user SQL queries
 *
 * @param $data array of options pref - prefix, host - mysql db host name
 * @return string
 */
function getMysqlAccess($data)
{
    $prefix = '';
    if (!empty($data['p'])) {
        $prefix = $data['p'];
    }
    $num = rand(0, 9);

    $dbHost = 'localhost';
    if (!empty($data['h'])) {
        $dbHost = $data['h'];
    }

    $dbSuffix = 'db';
    $dbName = $prefix . $num . $dbSuffix;

    $uSuffix = 'u';
    $dbUser = $prefix . $num . $uSuffix;

    $dbPass = getDbPass();

    $accessInfo = <<<EOINF

CREATE DATABASE `{$dbName}` /*!40100 DEFAULT CHARACTER SET utf8 */;
GRANT ALL ON `{$dbName}`.* TO '{$dbUser}'@{$dbHost} IDENTIFIED BY "{$dbPass}";
FLUSH PRIVILEGES;

EOINF;

    return $accessInfo;
}

if (count($argv) == 1) {
    echo 'Script usage: php ' . $argv[0] . ' -pPrefix [ -hMyDbHost]' . PHP_EOL;
    exit(1);
} else {
    $opts = getopt("p::h::");
    echo getMysqlAccess($opts) . PHP_EOL;
    exit(0);
}
