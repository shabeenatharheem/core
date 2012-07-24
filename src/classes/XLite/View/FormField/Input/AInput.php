<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\FormField\Input;


/**
 * \XLite\View\FormField\Input\AInput
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AInput extends \XLite\View\FormField\AFormField
{
    /**
     * Return field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFieldTemplate()
    {
        return 'input.tpl';
    }

    /**
     * getCommonAttributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonAttributes()
    {
        return parent::getCommonAttributes() + array(
            'type'  => $this->getFieldType(),
            'value' => $this->getValue(),
        );
    }

    /**
     * Register some data that will be sent to template as special HTML comment
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommentedData()
    {
        return array();
    }
}
