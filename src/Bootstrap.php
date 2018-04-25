<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents;


use nullref\documents\behaviors\Formatter;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        if ((($module = $app->getModule(Module::MODULE_ID)) == null) || !($module instanceof Module)) {
            return;
        };

        $app->getFormatter()->attachBehavior(Module::MODULE_ID, [
            'class' => Formatter::class,
        ]);
    }
}