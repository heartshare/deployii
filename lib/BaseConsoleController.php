<?php
/**
 * DeploYii - BaseConsoleController
 *
 * @link https://github.com/giovdk21/deployii
 * @copyright Copyright (c) 2014 Giovanni Derks
 * @license https://github.com/giovdk21/deployii/blob/master/LICENSE
 */

namespace app\lib;

use yii\console\Controller;
use yii\helpers\Console;

class BaseConsoleController extends Controller {


    /**
     * @var bool Do not perform any real action.
     */
    public $dryRun = false;

    /** @var string path to the project workspace */
    public $workspace;

    /** @var array extra command line options values */
    public $extraParams = [];
    /** @var array list of options provided from command line */
    private $_providedOptions = [];

    /** @var array log messages */
    private static $_log = [];


    /**
     * Extending runAction to handle extra params loaded dynamically
     * by (task) commands requirements or defined in the build script
     *
     * @inheritdoc
     */
    public function runAction($id, $params = []) {

        if (!empty($params)) {
            $options = $this->options($id);
            foreach ($params as $name => $value) {
                if (!is_int($name)) {

                    if ($value !== '') {
                        if (!isset($this->_providedOptions[$name])) {
                            $this->_providedOptions[$name] = 1;
                        }
                        else {
                            $this->_providedOptions[$name]++;
                        }
                    }

                    if (!in_array($name, $options, true)) {
                        if ($value !== '') {
                            $this->extraParams[$name] = $value;
                        }
                        unset($params[$name]);
                    }
                }
            }
        }

        parent::runAction($id, $params);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {

            $this->stdout(" Welcome to DeploYii ");
            $this->stdout(DEPLOYII_VERSION, Console::FG_YELLOW);
            $this->stdout("\n");
            if (!DEPLOYII_STABLE) {
                $this->stdout("Note: ", Console::FG_PURPLE);
                $this->stdout("this version is not ready for production! Use it only for learning / testing purposes.");
                $this->stdout("\n");
            }

            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function options($actionId) {

        $options = parent::options($actionId);

        $options[] = 'dryRun';

        return $options;
    }

    /**
     * Getter for $this->_providedOptions
     *
     * @return array list of options provided from command line
     */
    public function getProvidedOptions() {
        return $this->_providedOptions;
    }

    /**
     * Return the path of the folder containing all the scripts needed to init, build and/or
     * deploy the current project.
     *
     * @return string Path of the script folder relative to $this->workspace
     */
    public function getScriptFolder() {
        return 'deployii';
    }

    public function getCommandOptions() {
        die('This method must be called statically from the command requirements behavior');
    }

    public function checkRequirements() {
        die('This method must be called statically from the command requirements behavior');
    }

} 