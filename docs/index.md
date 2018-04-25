Workers
======

All workers should extends from base worker classes:

    - \nullref\documents\components\BaseExporter.
    - \nullref\documents\components\BaseImporter.

And need to implements appropriate abstract methods, e.g., `getName()` method to define worker name.

You could override `\nullref\documents\components\Worker::getOptions()` method to define options that will have worker instance.

```php
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'create_if_not_exist' => [
                'type' => self::OPTION_TYPE_CHECKBOX,
                'label' => Yii::t('documents', 'Create if not exist'),
            ],
        ]);
    }
```
Also, you could override `\nullref\documents\components\Worker::getDocumentOptions()` method to define options that will have each document instance that would precessed by worker.

```php
    public function getDocumentOptions()
    {
        return array_merge(parent::getDocumentOptions(), [
            'catalog' => [
                'type' => self::OPTION_TYPE_DROPDOWN,
                'label' => Yii::t('documents', 'Catalog'),
                'items' => Catalog::getMap(),
            ],
        ]);
    }
```

In worker code all options values will be able by `getOptionValue()` method.

```php
$catalogId = $this->getOptionValue($document, 'catalog');
```




Usage
=====

Creating worker
--------------

After install and configuring module you need to create instance of worker.

You can do it at route `/documents/admin/import-config/create`.

![Create Worker Screenshot](https://raw.githubusercontent.com/NullRefExcep/yii2-documents/master/docs/images/worker-create.png)

This form cloud contains options that was defined in `getOptions` method.


After that I could add document based on this worker instance:

![Documents Import](https://raw.githubusercontent.com/NullRefExcep/yii2-documents/master/docs/images/documents-import.png)

On document/create form you will be able to set values for options that was defined in `getDocumentOptions()` method.


![Documents Import](https://raw.githubusercontent.com/NullRefExcep/yii2-documents/master/docs/images/document-create.png)