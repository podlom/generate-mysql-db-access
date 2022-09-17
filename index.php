<?php
/**
 * Created by PhpStorm.
 * @author: Taras Shkodenko <taras@shkodenko.com>
 *
 * Date: 21.12.2017
 * Time: 13:25
 *
 * Web interface to generate MySQL database & user setup SQL queries
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
    if (!empty($data['pref'])) {
        $prefix = $data['pref'];
    }
    $num = rand(0, 9);

    $dbHost = 'localhost';
    if (!empty($data['host'])) {
        $dbHost = $data['host'];
    }

    $includeTestDb = false;
    if (!empty($data['test'])) {
        $includeTestDb = true;
    }

    $dbSuffix = 'db';
    $dbName = $prefix . $num . $dbSuffix;
    if ($includeTestDb) {
        $testDbName = $prefix . $num . $dbSuffix . '_test';
    }

    $uSuffix = 'u';
    $dbUser = $prefix . $num . $uSuffix;

    $dbPass = getDbPass();

    $charset = '/*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */';

    $testAccessInfo = '';
    if ($includeTestDb && !empty($testDbName)) {
        $testAccessInfo = <<<ETINF
<br>
CREATE DATABASE `{$testDbName}` {$charset}; <br>
GRANT ALL ON `{$testDbName}`.* TO '{$dbUser}'@{$dbHost} IDENTIFIED BY "{$dbPass}"; <br>
FLUSH PRIVILEGES; <br>

ETINF;

    }

    $accessInfo = <<<EOINF

CREATE DATABASE `{$dbName}` {$charset}; <br>
GRANT ALL ON `{$dbName}`.* TO '{$dbUser}'@{$dbHost} IDENTIFIED BY "{$dbPass}"; <br>
FLUSH PRIVILEGES;

{$testAccessInfo}
EOINF;

    return $accessInfo;
}

if (!empty($_POST)) {
    die(getMysqlAccess($_POST));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate MySQL access information</title>
</head>
<body>
<form method="post" action="" id="frm1">
    <div>
        <label for="pref1">Prefix: </label>
        <input type="text" name="pref" id="pref1" value="test">
    </div>
    <div>
        <label for="host1">Host: </label>
        <input type="text" name="host" id="host1" value="localhost">
    </div>
    <div>
        <label for="test1">Create test DB: </label>
        <input type="checkbox" name="test" id="test1" value="1">
    </div>
    <div>
        <input type="submit" value="Generate">
    </div>
</form>
</body>
</html>
