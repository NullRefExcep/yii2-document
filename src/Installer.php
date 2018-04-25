<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace nullref\documents;


use nullref\core\components\ModuleInstaller;
use yii\helpers\Console;

class Installer extends ModuleInstaller
{
    public function getModuleId()
    {
        return 'documents';
    }

    public function install()
    {
        parent::install();
        if (Console::confirm('Create upload folder?')) {
            try {
                $this->createFolder('@webroot/uploads');
                Console::output('Folder @webroot/uploads was created');
            } catch (\Exception $e) {
                Console::output($e->getMessage());
            }
        }
    }
} 