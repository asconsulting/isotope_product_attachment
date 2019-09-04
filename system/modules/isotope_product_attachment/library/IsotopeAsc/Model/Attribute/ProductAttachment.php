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
		
        $objContentModel = new \ContentModel();
        $objContentModel->type = 'downloads';
        $objContentModel->multiSRC = $this->getValue($objProduct);
        $objContentModel->sortBy = $this->sortBy;
        $objContentModel->cssID = serialize(array('', $this->field_name));

        $objElement = new \ContentDownloads($objContentModel);
        return $objElement->generate();
		
    }
}
