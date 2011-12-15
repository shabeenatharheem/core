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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\ItemsList\Product\Customer;

/**
 * Category products list widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer", weight="200")
 */
class Category extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /**
     * Widget parameter names
     */
    const PARAM_CATEGORY_ID = 'category_id';

    /**
     * Allowed sort criterions
     */
    const SORT_BY_MODE_DEFAULT = 'cp.orderby';

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';

        return $result;
    }


    /**
     * Return target to retrive this widget from AJAX
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getWidgetTarget()
    {
        return 'category';
    }

    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->sortByModes = array(static::SORT_BY_MODE_DEFAULT => 'Default') + $this->sortByModes;
    }

    /**
     * Returns CSS classes for the container element
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getListCSSClasses()
    {
        return parent::getListCSSClasses() . ' category-products';
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Customer\Product\Category';
    }

    /**
     * Get requested category object
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCategory()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->find($this->getCategoryId());
    }

    /**
     * Get requested category ID 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getCategoryId()
    {
        return \XLite\Core\Request::getInstance()->{static::PARAM_CATEGORY_ID}
            ?: \XLite\Model\Repo\Category::CATEGORY_ID_ROOT;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category('
                Category ID', \XLite\Model\Repo\Category::CATEGORY_ID_ROOT
            ),
        );
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = static::PARAM_CATEGORY_ID;
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortByModeDefault()
    {
        return static::SORT_BY_MODE_DEFAULT;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $category = $this->getCategory();

        return $category ? $category->getProducts($cnd, $countOnly) : null;
    }

    /**
     * Get widget parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getWidgetParameters()
    {
        $list = parent::getWidgetParameters();
        $list['category_id'] = \XLite\Core\Request::getInstance()->category_id;

        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getCategory() && $this->getCategory()->isVisible();
    }
}
