<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace app\components\exporters;


use nullref\documents\components\BaseExporter;
use nullref\documents\models\Document;
use nullref\eav\models\Attribute;
use app\modules\product\models\Product;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\helpers\Console;

/**
 * Class TotalExporter
 *
 * Example implementation of export worker
 *
 * @package app\components\exporters
 */
class TotalExporter extends BaseExporter
{
    /**
     * @param Document $document
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportInternal(Document $document)
    {
        $name = time() . '_' . $this->getName();
        $dir = Yii::getAlias('@webroot/uploads');

        $dist = $dir . DIRECTORY_SEPARATOR . $name . '.xlsx';
        $src = Yii::getAlias('@webroot/list.xlsx');

        copy($src, $dist);

        $excel = IOFactory::createReader('Excel2007');

        /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet $obj */
        $obj = $excel->load($dist);
        $obj->setActiveSheetIndex(0);

        $worksheet = $obj->getActiveSheet();
        $offset = 6;


        $length = Product::find()->count();
        Console::output('Find ' . $length . ' products');

        $worksheet->insertNewRowBefore($offset, $length);


        $products = Product::find()->each(100);

        $index = 0;

        $vendor = Attribute::findOne(['code' => 'vendor']);
        $vendors = $vendor->getOptions()->indexBy('id')->select('value')->column();

        $category = Attribute::findOne(['code' => 'category']);
        $categories = $category->getOptions()->indexBy('id')->select('value')->column();

        $size = Attribute::findOne(['code' => 'size']);
        $sizes = $size->getOptions()->indexBy('id')->select('value')->column();

        $color = Attribute::findOne(['code' => 'color']);
        $colors = $color->getOptions()->indexBy('id')->select('value')->column();

        $season = Attribute::findOne(['code' => 'season']);
        $seasons = $season->getOptions()->indexBy('id')->select('value')->column();

        $vendor_country = Attribute::findOne(['code' => 'vendor_country']);
        $vendor_countries = $vendor_country->getOptions()->indexBy('id')->select('value')->column();

        $gender = Attribute::findOne(['code' => 'gender']);
        $genders = $gender->getOptions()->indexBy('id')->select('value')->column();

        foreach ($products as $item) {
            /** @var $item Product */
            Console::output('SKU: ' . $item->sku);


            $worksheet->setCellValue('A' . ($offset + $index), $index + 1);
            $worksheet->setCellValue('B' . ($offset + $index), $item->sku);
            $worksheet->setCellValue('C' . ($offset + $index), 'None');
            $worksheet->setCellValue('D' . ($offset + $index), $item->name);
            $worksheet->setCellValue('E' . ($offset + $index), isset($vendors[$item->vendor]) ? $vendors[$item->vendor] : '');
            $worksheet->setCellValue('F' . ($offset + $index), isset($categories[$item->category]) ? $categories[$item->category] : '');
            $worksheet->setCellValue('G' . ($offset + $index), isset($vendors[$item->vendor]) ? $vendors[$item->vendor] : '');


            $worksheet->setCellValue('I' . ($offset + $index), isset($colors[$item->color]) ? $colors[$item->color] : '');
            $worksheet->setCellValue('H' . ($offset + $index), isset($sizes[$item->size]) ? $sizes[$item->size] : '');
            $worksheet->setCellValue('L' . ($offset + $index), isset($seasons[$item->season]) ? $seasons[$item->season] : '');
            $worksheet->setCellValue('J' . ($offset + $index), isset($vendor_countries[$item->vendor_country]) ? $vendor_countries[$item->vendor_country] : '');
            $worksheet->setCellValue('K' . ($offset + $index), isset($genders[$item->gender]) ? $genders[$item->gender] : '');

            $worksheet->setCellValue('N' . ($offset + $index), $item->description);
            $worksheet->setCellValue('M' . ($offset + $index), $item->material);

            $worksheet->setCellValue('O' . ($offset + $index), $item->qty, true)->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

            $columnIndex = 15;

            $worksheet->setCellValue(Coordinate::stringFromColumnIndex($columnIndex) . ($offset + $index), $item->price, true)->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);


            $index++;
        }

        $objWriter = IOFactory::createWriter($obj, 'Excel2007');
        $objWriter->save($dist);

        $document->file_path = $dist;
        $document->save(false, ['file_path']);
    }

    public function getName()
    {
        return Yii::t('documents', 'Total exporter');
    }
}