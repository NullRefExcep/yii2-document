<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace app\components\importers;

use app\modules\catalog\models\Catalog;
use nullref\documents\components\ExcelImporter;
use nullref\documents\models\Document;
use nullref\documents\models\DocumentItem;
use app\modules\product\models\Product;
use app\modules\storage\models\Storage;
use app\traits\Importer;
use Yii;
use yii\helpers\Console;

class CatalogImporter extends ExcelImporter
{
    use Importer;

    public $optionFields = ['vendor', 'category', 'size', 'color', 'vendor_country', 'gender', 'season'];
    public $valueFields = ['price', 'sku', 'material', 'description', 'qty', 'keywords'];
    protected $storage = [];

    /**
     * @param Document $document
     */
    public function beforeImport(Document $document)
    {
        parent::beforeImport($document);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Yii::t('documents', 'Catalog importer');
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'create_if_not_exist' => [
                'type' => self::OPTION_TYPE_CHECKBOX,
                'label' => Yii::t('documents', 'Create if not exist'),
            ],
        ]);
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            'sku' => Yii::t('documents', 'Sku'),
            'name' => Yii::t('documents', 'Name'),
            'vendor' => Yii::t('documents', 'Vendor'),
            'category' => Yii::t('documents', 'Category'),
            'size' => Yii::t('documents', 'Size'),
            'color' => Yii::t('documents', 'Color'),
            'vendor_country' => Yii::t('documents', 'Vendor country'),
            'gender' => Yii::t('documents', 'Gender'),
            'season' => Yii::t('documents', 'Season'),
            'material' => Yii::t('documents', 'Material'),
            'description' => Yii::t('documents', 'Description'),
            'qty' => Yii::t('documents', 'Quantity'),
            'price' => Yii::t('documents', 'Price'),
            'stocks' => Yii::t('documents', 'Stocks'),
            'keywords' => Yii::t('documents', 'Keywords'),
        ];
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->storage = Storage::find()->all();
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \Exception
     */
    protected function load(Document $document)
    {
        if ($document->getDocumentItems()->count()) {
            DocumentItem::deleteAll(['document_id' => $document->id]);
        }
        return parent::load($document);
    }

    /**
     * @param $record
     * @param $index
     * @param Document $document
     */
    protected function modifyItem($record, $index, Document $document)
    {
        $data = $record->data;

        if (isset($data['sku']) && isset($data['size'])) {
            $sku = $data['sku'];
            $size = $data['size'];
            if ($sku && $size) {
                $identifier = $this->buildIdentifier($data);
                $data['identifier'] = $identifier;
                try {
                    /** @var Product $product */
                    $product = Product::findOne(['identifier' => $identifier]);
                    if ($product) {
                        $product = $this->updateProduct($document, $product, $data);
                    } else {
                        $product = $this->createProduct($document, $data);
                    }
                    if (!$product->save()) {
                        $record->error = implode(array_filter($product->getFirstErrors()), '; ');
                        $record->status = DocumentItem::STATUS_ERROR;
                        $record->save(false, ['status', 'error']);
                    }
                    if (isset($data['stocks']) && $data['stocks']) {
                        $this->updateStocks($document, $product, $data['stocks']);
                    }
                } catch (\Exception $e) {
                    Console::error($e->getMessage());
                }
            }
        }
    }

    /**
     * @param Document $document
     * @param Product $product
     * @param $data
     * @return Product
     */
    protected function updateProduct(Document $document, Product $product, $data)
    {
        Console::output('Update product ID:' . $product->id . ' Sku:' . $data['sku']);

        foreach ($this->valueFields as $item) {
            if (isset($data[$item]) && $data[$item]) {
                $value = $data[$item];
                if (in_array($item, ['price', 'qty'])) {
                    $value = trim($data[$item], 't');
                }
                $product->$item = $value;
            }
        }

        foreach ($this->optionFields as $item) {
            if (isset($data[$item]) && $data[$item]) {
                $value = $this->filterValue($data[$item]);
                $valueModel = $this->getAttributeOption($item, $value);
                $product->$item = $valueModel->id;
            }
        }

        $product->catalog_id = $this->getOptionValue($document, 'catalog');

        return $product;
    }

    /**
     * @param $value
     * @return string
     */
    protected function filterValue($value)
    {
        return trim($value);
    }


    /**
     * @param Document $document
     * @param $data
     * @return Product
     */
    protected function createProduct(Document $document, $data)
    {
        $product = new Product();
        Console::output('Create product Sku:' . $data['sku']);

        $product->identifier = $data['identifier'];
        if (isset($data['name'])) {
            $product->name = $data['name'];
        }

        foreach ($this->valueFields as $item) {
            if (isset($data[$item]) && $data[$item]) {
                $value = $data[$item];
                if (in_array($item, ['price', 'qty'])) {
                    $value = trim($data[$item], 't');
                }
                $product->$item = $value;
            }
        }

        foreach ($this->optionFields as $item) {
            if (isset($data[$item]) && $data[$item]) {
                $value = $this->filterValue($data[$item]);
                $valueModel = $this->getAttributeOption($item, $value);
                $product->$item = $valueModel->id;
            }
        }

        $product->catalog_id = $this->getOptionValue($document, 'catalog');

        return $product;
    }

    /**
     * @param $document
     * @param $product
     * @param $stocks
     * @return bool
     */
    protected function updateStocks($document, $product, $stocks)
    {
        $stocksList = explode($this->getOptionValue($document, 'delimiter'), $stocks);

        Console::output('Product ID:' . $product->id);
        Console::output('Set qty:');
        $isUpdateStock = false;
        foreach ($stocksList as $index => $stock) {
            if (isset($this->storage[$index]) && $stock) {
                /** @var Storage $storage */
                $storage = $this->storage[$index];

                Console::output($storage->id . ':' . $storage->name . ' => ' . $stock);
                $storage->setQty($product, intval(trim($stock)), false);
                $isUpdateStock = true;
            }
        }
        if ($isUpdateStock) {
            return Storage::recalculateTotal($product);
        }
        return true;
    }
}