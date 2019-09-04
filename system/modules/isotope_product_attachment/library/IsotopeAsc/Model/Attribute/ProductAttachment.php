<?php

/**
 * Isotope Product Attachment
 *
 * Copyright (C) 2019 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_product_attachment
 * @link       https://andrewstevens.consulting
 */
 


namespace IsotopeAsc\Model\Attribute;

use Isotope\Interfaces\IsotopeProduct;
use Isotope\Model\Attribute;

/**
 * Attribute to provide an file attachment in the product details
 *
 */
class ProductAttachment extends Attribute
{
    /**
     * @inheritdoc
     */
    public function saveToDCA(array &$arrData)
    {
        parent::saveToDCA($arrData);

        $arrData['fields'][$this->field_name]['sql'] = "blob NULL";
        $arrData['fields'][$this->field_name]['eval']['fieldType'] = 'checkbox';
        $arrData['fields'][$this->field_name]['eval']['multiple'] = true;
        $arrData['fields'][$this->field_name]['eval']['files'] = true;
        $arrData['fields'][$this->field_name]['eval']['filesOnly'] = true;
    }

    /**
     * @inheritdoc
     */
    public function getBackendWidget()
    {
        return $GLOBALS['BE_FFL']['fileTree'];
    }

    /**
     * @inheritdoc
     */
    public function generate(IsotopeProduct $objProduct, array $arrOptions = array())
    {
        $strPoster = null;
        $arrFiles = deserialize($objProduct->{$this->field_name}, true);
		
		die($arrFiles);
		
        // Return if there are no files
        if (empty($arrFiles) || !\is_array($arrFiles)) {
            return '';
        }

        // Get the file entries from the database
        $objFiles = \FilesModel::findMultipleByIds($arrFiles);

        if (null === $objFiles) {
            return '';
        }

        // Find poster
        while ($objFiles->next()) {
            if (\in_array($objFiles->extension, trimsplit(',', $GLOBALS['TL_CONFIG']['validImageTypes']))) {
                $strPoster = $objFiles->uuid;
                $arrFiles = array_diff($arrFiles, array($objFiles->uuid));
            }
        }


        $objContentModel = new \ContentModel();
        $objContentModel->type = 'downloads';
        $objContentModel->cssID = serialize(array('', $this->field_name));
        $objContentModel->objFiles = $objFiles;

        $objElement = new \ContentDownloads($objContentModel);
        return $objElement->generate();
    }
}
