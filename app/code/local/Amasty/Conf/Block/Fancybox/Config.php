<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Conf
 */


class Amasty_Conf_Block_Fancybox_Config extends Mage_Core_Block_Template
{
    const FANCYBOX_JS_V2 = 'js/amasty/plugins/fancybox/jquery.fancybox2.js';
    const FANCYBOX_JS_V3 = 'js/amasty/plugins/fancybox/jquery.fancybox3.min.js';
    const FANCYBOX_CSS_V2 = 'js/amasty/plugins/fancybox/jquery.fancybox2.css';
    const FANCYBOX_CSS_V3 = 'js/amasty/plugins/fancybox/jquery.fancybox3.min.css';

    public function __construct()
    {
        if (Mage::getStoreConfig('amconf/lightbox/enable')) {
            $this->_includeFancyboxLibraries();
        }
        parent::__construct();
    }

    protected function _includeFancyboxLibraries()
    {
        /** @var Mage_Page_Block_Html_Head $headBlock */
        $headBlock = Mage::app()->getLayout()->getBlock('head');
        if (!$headBlock) {
            return;
        }

        switch (Mage::getStoreConfig('amconf/lightbox/fancybox_library')) {
            case Amasty_Conf_Model_Source_FancyboxLibrary::VERSION_3:
                $headBlock->addItem('skin_js', self::FANCYBOX_JS_V3);
                $headBlock->addItem('skin_css', self::FANCYBOX_CSS_V3);
                break;
            case Amasty_Conf_Model_Source_FancyboxLibrary::VERSION_2:
                $headBlock->addItem('skin_js', self::FANCYBOX_JS_V2);
                $headBlock->addItem('skin_css', self::FANCYBOX_CSS_V2);
                break;
        }
    }
}
