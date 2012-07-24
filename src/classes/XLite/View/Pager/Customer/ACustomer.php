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

namespace XLite\View\Pager\Customer;

/**
 * ACustomer
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ACustomer extends \XLite\View\Pager\APager
{
    /**
     * getItemsPerPageDefault
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getItemsPerPageDefault()
    {
        return 10;
    }

    /**
     * Return number of pages to display
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagesPerFrame()
    {
        return 5;
    }
}
