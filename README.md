Yii2 Documents
===============

WIP

Module for processing documents

Introducing
-----------

This module allows you create own document's workers with few levels of customization:
 - worker code
 - worker instance 
 - document instance


All workers run under default yii2 [queue](https://github.com/yiisoft/yii2-queue)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist nullref/yii2-documents "*"
```

or add

```
"nullref/yii2-documents": "*"
```

to the require section of your `composer.json` file.

Then you have run console command for install this module and run migrations:

```
php yii module/install nullref/yii2-documents
```

This command will add current module to config/installed_modules.php file.

This file need be included in application config.

If you don't want to use `module/install` command that you could add module to you application config manually:

```php
'modules' => [
    //...
    'documents' => [
        'class' => nullref\documents\Module::class,
    ],
    //...
],
```

Than you need run 
```
php yii modules-migrate --moduleId=documents
```

Also, you have install [yiisoft/yii2-queue](https://github.com/yiisoft/yii2-queue) following it own manual.


Configuration
-------------

After installation you need create own classes that implement export/import logic.

For example you can check demo folder.

After creating document worker you need register it in module config:


```php
    'documents' => [
        'class' => nullref\documents\Module::class,
        'importers' => [
            // List of importer classes
            'catalog' => [
                'class' => \app\components\importers\CatalogImporter::class,
            ],
        ],
        'exporters' => [
            // List of exporter classes
            'total' => [
                'class' => app\components\exporters\TotalExporter::class,
            ],
        ],
    ],
```

Usage
-----

Please, check [docs](https://github.com/NullRefExcep/yii2-documents/blob/master/docs/index.md) and [demo](https://github.com/NullRefExcep/yii2-documents/tree/master/demo) for more info.

Customization
-------------

And [translations](https://github.com/NullRefExcep/yii2-core#translation-overriding)

