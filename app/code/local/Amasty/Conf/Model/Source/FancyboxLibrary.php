<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Conf
 */


class Amasty_Conf_Model_Source_FancyboxLibrary extends Varien_Object
{
    const VERSION_3 = '3';
    const VERSION_2 = '2';
    const THEME_VERSION = '0';

    public function toOptionArray()
    {
        $hlp = Mage::helper('amconf');
        return array(
            array('value' => self::VERSION_3, 'label' => $hlp->__('FancyBox 3 (default)')),
            array('value' => self::VERSION_2, 'label' => $hlp->__('FancyBox 2')),
            array('value' => self::THEME_VERSION, 'label' => $hlp->__('FancyBox version from the custom theme')),
        );
    }

}
