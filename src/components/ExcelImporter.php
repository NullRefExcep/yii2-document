<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\components;


use nullref\documents\models\Document;
use nullref\documents\models\DocumentItem;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactory;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class ExcelImporter
 *
 * Basic class for
 * @package nullref\documents\components
 */
abstract class ExcelImporter extends FileImporter
{
    /**
     * @var Document
     */
    protected $document;

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'first_row' => [
                'type' => self::OPTION_TYPE_TEXT,
                'label' => Yii::t('documents', 'First row'),
            ],
            'delimiter' => [
                'type' => self::OPTION_TYPE_TEXT,
                'label' => Yii::t('documents', 'Delimiter'),
            ],
        ];
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \Exception
     */
    public function import(Document $document)
    {
        $this->beforeLoad($document);

        $this->load($document);

        $this->afterLoad($document);

        $items = $this->getItems($document);

        $this->beforeImport($document);

        foreach ($items as $index => $record) {
            $this->modifyItem($record, $index, $document);
        }

        $this->afterImport($document);

        return true;
    }

    /**
     * @param Document $document
     */
    protected function beforeLoad(Document $document)
    {
        ini_set('memory_limit', '2048M');
    }

    /**
     * @param Document $document
     * @return bool
     * @throws \Exception
     */
    protected function load(Document $document)
    {
        $obj = $this->loadExcel($document);

        $sheetNumber = max(intval($this->getOptionValue($document, 'sheet_number')) - 1, 0);

        $obj->setActiveSheetIndex($sheetNumber);

        $firstRow = intval($this->getOptionValue($document, 'first_row'));

        $sheetData = array_slice($obj->getActiveSheet()
            ->toArray(null, true, true, true), $firstRow);

        unset($obj);

        $savedRows = 0;

        try {
            foreach ($sheetData as $row) {
                $data = $this->extractAttributes($document, $row);
                if ($this->loadItem($document, $data)) {
                    $savedRows++;
                }
            }
        } catch (\Exception $e) {
            Console::error('Get error:' . $e->getMessage());
            Console::error($e->getTraceAsString());
            $document->status = Document::STATUS_ERROR;
            $document->save(false);
            throw $e;
        }
        Console::output('Get ' . $savedRows . ' row(s) from document');

        $document->status = Document::STATUS_DONE;
        $document->save(false);

        return true;
    }

    /**
     * @param Document $document
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadExcel(Document $document)
    {
        $document->status = Document::STATUS_LOADING;
        //  $document->progress = 0;
        $document->save(false);

        $filePath = $this->getOptionValue($document, 'file_path');
        Console::output('Loading excel file: ' . $filePath);

        $objReader = IOFactory::createReaderForFile($filePath);

        /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $obj */
        $obj = $objReader->load($filePath);
        unset($objReader);

        return $obj;
    }

    /**
     * @param $document Document
     * @param $row
     * @return array
     */
    protected function extractAttributes($document, $row)
    {
        $resultRow = [];
        foreach ($document->config->columns as $field) {
            if ($field['source']) {
                $sourceColumns = explode(',', $field['source']);
                $sourceValues = array_map(function ($item) use ($row) {
                    return isset($row[$this->normalizeColName($item)]) ? $row[$this->normalizeColName($item)] : '';
                }, $sourceColumns);

                if (isset($field['filter']) && boolval($field['filter'])) {

                    $sourceValues = array_filter($sourceValues);
                }

                $resultRow[$field['target']] = implode($this->getOptionValue($document, 'delimiter'), $sourceValues);
            } else {
                $resultRow[$field['target']] = null;
            }
        }

        return $resultRow;
    }

    /**
     * @param $name
     * @return string
     */
    protected function normalizeColName($name)
    {
        $name = trim($name);
        if (is_numeric($name)) {
            return Coordinate::stringFromColumnIndex((int)$name);
        }
        return $name;
    }

    /**
     * @param $document Document
     * @param $data
     * @param array $attributes
     * @return bool
     */
    protected function loadItem($document, $data, $attributes = [])
    {
        $docItem = new DocumentItem();
        $newAttributes = ArrayHelper::merge([
            'document_id' => $document->id,
            'data' => $data,
        ], $attributes);
        $docItem->setAttributes($newAttributes);
        if (!$docItem->validate()) {
            Console::error('Can\'t save item:');
            Console::error(print_r($docItem->errors, true));
            return false;
        }
        if ($docItem->status === null) {
            $docItem->status = DocumentItem::STATUS_NEW;
        }
        return $docItem->save(false);
    }

    /**
     * @param Document $document
     */
    protected function afterLoad(Document $document)
    {

    }

    /**
     * @param Document $document
     * @return \yii\db\BatchQueryResult
     */
    protected function getItems(Document $document)
    {
        $query = $document->getDocumentItems();

        return $query->each();
    }

    /**
     * @param Document $document
     */
    public function beforeImport(Document $document)
    {
        $document->status = Document::STATUS_LOADING;
        // $document->progress = 0;
        $document->save(false);
    }

    protected abstract function modifyItem($record, $index, Document $document);

    /**
     * @param Document $document
     */
    public function afterImport(Document $document)
    {
        if ($document->status !== Document::STATUS_ERROR) {
            $document->status = Document::STATUS_DONE;
        }
        // $document->progress = 0;
        $document->save(false);
    }

    /**
     * @return array
     */
    public function getDocumentOptions()
    {
        return array_merge(parent::getDocumentOptions(), [
            'sheet_number' => [
                'type' => self::OPTION_TYPE_TEXT,
                'label' => Yii::t('documents', 'Sheet number'),
                'value' => 1,
            ],
        ]);
    }
}