<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Conf
 */
class Amasty_Conf_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_USE_SIMPLE_PRICE     = 'amconf/price/use_simple_price';
    const XML_PATH_OPTIONS_IMAGE_SIZE   = 'amconf/list/listimg_size';
    const ADMIN_OPTION_ITEMS_LIMIT      = 20;
    const SHOULD_LOAD_MODEL             = 'should_load';
    const PATH_TO_SWATCH_DIRECTORY      = 'amconf/images/';

    static public $swatchData = null;

    static public function getSwatchData()
    {
        if (self::$swatchData == null) {
            self::$swatchData = Mage::getModel('amconf/swatch')->getCollection()->getCollectionAsArray();
            if (!self::$swatchData) {
                self::$swatchData = array();
            }
        }

        return self::$swatchData;
    }

    public function getImageUrl($optionId, $width, $height, $swatchModel = self::SHOULD_LOAD_MODEL)
    {
        $uploadDir = Mage::getBaseDir('media') . DIRECTORY_SEPARATOR . self::PATH_TO_SWATCH_DIRECTORY;
        $url = "";

        $extension = $this->_getExtension($swatchModel, $optionId);
        $ioFile = new Varien_Io_File();
        if ($ioFile->fileExists($uploadDir . $optionId . '.' . $extension)) {
            $url =  self::PATH_TO_SWATCH_DIRECTORY. $optionId . '.' . $extension;
        } else if ($ioFile->fileExists($uploadDir . $optionId . '.jpg')) {
            $url = self::PATH_TO_SWATCH_DIRECTORY . $optionId . '.jpg';
        }

        if ($url) {
            if ($width && $height) {
                $url = Mage::helper('amconf/image')->init($url)->resize($width, $height);
            } else {
                $url = Mage::getBaseUrl('media') . $url;
            }
        }

        return $url;
    }

    protected function _getExtension($swatchModel, $optionId)
    {
        $result = null;
        if ($swatchModel == self::SHOULD_LOAD_MODEL) {
            // back compatibility
            $swatchModel = Mage::getModel('amconf/swatch')->load($optionId);
        }

        if ($swatchModel) {
            $result = $swatchModel->getExtension();
        }

        return $result;
    }

    public function getLimit()
    {
        return self::ADMIN_OPTION_ITEMS_LIMIT;
    }

    public function getPlaceholderUrl($attributeId, $width, $height)
    {
        $uploadDir = Mage::getBaseDir('media') . DIRECTORY_SEPARATOR .
            'amconf' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        if (file_exists($uploadDir . '/attr_' . $attributeId . '.jpg')) {
            $url = 'amconf' . '/' . 'images' . '/attr_' . $attributeId . '.jpg';
            if ($width && $height) {
                return Mage::helper('amconf/image')->init($url)->resize($width, $height);
            } else {
                return Mage::getBaseUrl('media') . $url;
            }
        }
        return "";
    }

    public function getConfigUseSimplePrice()
    {
        return Mage::getStoreConfig(self::XML_PATH_USE_SIMPLE_PRICE);
    }

    public function getOptionsImageSize()
    {
        return Mage::getStoreConfig(self::XML_PATH_OPTIONS_IMAGE_SIZE);
    }

    public function getHtmlBlock($product, $html)
    {
        $blockForForm = Mage::app()->getLayout()-> createBlock(
            'amconf/catalog_product_view_type_configurablel',
            'product.info.options.configurable',
            array('product' => $product)
        );

        $blockForForm->setTemplate("amasty/amconf/configurable.phtml");
        $html .= '<div class="amconf-block" id="amconf-block-' . $product->getId() . '">' .
            $blockForForm->toHtml() .
            '</div>';

        return $html;
    }

    /*
    *   set configurable price as min from simple price
    * templates:
    * app\design\frontend\base\default\template\catalog\product\view\tierprices.phtml
    * app\design\frontend\base\default\template\catalog\product\price.phtml
    * $product = Mage::helper('amconf')->getSimpleProductWithMinPrice($product);
    */
    public function getSimpleProductWithMinPrice($product)
    {
        $flag = true;

        $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
        $collection = $conf->getUsedProductCollection()
            ->addAttributeToSelect('*')
            ->addFilterByRequiredOptions()
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addStoreFilter(Mage::app()->getStore()->getId());

        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents();
        $collection->getSelect()->reset('order');
        $collection->getSelect()->order('minimal_price', 'asc');

        $first =  $collection->getFirstItem();
        $last  =  $collection->getLastItem();
        if ($first->getMinimalPrice() == $last->getMinimalPrice()) {
            $flag = false;
        }
        return array($first, $flag);

    }

    public function getAjaxUrl()
    {
        $url = Mage::getUrl('amconf/ajax/ajax');
        if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "") {
            $url = str_replace('http:', 'https:', $url);
        }

        return $url;
    }

    /**
     * @param string $string
     *
     * @return array|null
     */
    public function unserialize($string)
    {
        if (!@class_exists('Amasty_Base_Helper_String')) {
            $message = $this->getUnserializeError();
            Mage::logException(new Exception($message));
            if (Mage::app()->getStore()->isAdmin()) {
                Mage::helper('ambase/utils')->_exit($message);
            } else {
                Mage::throwException($this->__('Sorry, something went wrong. Please contact us or try again later.'));
            }
        }

        return \Amasty_Base_Helper_String::unserialize($string);
    }

    /**
     * @return string
     */
    public function getUnserializeError()
    {
        return 'If there is the following text it means that Amasty_Base is not updated to the latest 
                             version.<p>In order to fix the error, please, download and install the latest version of 
                             the Amasty_Base, which is included in all our extensions.
                        <p>If some assistance is needed, please submit a support ticket with us at: '
            . '<a href="https://amasty.com/contacts/" target="_blank">https://amasty.com/contacts/</a>';
    }
}
