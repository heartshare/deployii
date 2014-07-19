<?php
/**
 * DeploYii - SftpConnectCommand
 *
 * @link https://github.com/giovdk21/deployii
 * @copyright Copyright (c) 2014 Giovanni Derks
 * @license https://github.com/giovdk21/deployii/blob/master/LICENSE
 */

namespace app\lib\commands;

use app\lib\BaseCommand;
use app\lib\TaskRunner;
use yii\console\Exception;
use yii\helpers\Console;
use Yii;
use Net_SFTP;

class SftpConnectCommand extends BaseCommand {

    public static function run(& $cmdParams, & $params) {
        $controller = TaskRunner::$controller;

        $res = false;
        $connectionId = (!empty($cmdParams[0]) ? $cmdParams[0] : '');

        $host = $controller->getParamVal('sftpHost', $params);
        $username = $controller->getParamVal('sftpUsername', $params);
        $password = $controller->getParamVal('sftpPassword', $params);
        $port = $controller->getParamVal('sftpPort', $params);
        $authMethod = $controller->getParamVal('sftpAuthMethod', $params);
        $keyFile = $controller->getParamVal('sftpKeyFile', $params);
        $keyPassword = $controller->getParamVal('sftpKeyPassword', $params);


        if (empty($connectionId)) {
            throw new Exception('sftpConnect: Please specify a valid connection ID and host');
        }

        $controller->stdout('Opening connection with '.$username.'@'.$host.':'.$port." ...\n");

        if (!$controller->dryRun) {
            $sftp = new Net_SFTP($host, $port);

            switch ($authMethod) {

                case 'password':
                    $res = $sftp->login($username, $password);
                    break;

                case 'key':
                    die('Not yet implemented!');
                    break;
            }

            if ($res) {
                $controller->connections[$connectionId] = $sftp;
            }
        }
        else {
            $controller->stdout(' [dry run]', Console::FG_YELLOW);
        }

        $controller->stdout("\n");
        return $res;
    }

} 