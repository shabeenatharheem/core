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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Category
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table (name="categories")
 */
class XLite_Model_Category extends XLite_Model_Base_I18n
{
    /**
     * Node unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $category_id;

    /**
     * Node left value 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $lpos;

    /**
     * Node right value 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $rpos;

    /**
     * Node status
     * 1 - enabled, 0 - disabled
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $enabled = 0;

    /**
     * Node views counter
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $views_stats = 0;

    /**
     * Node membership level
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", nullable=false)
     */
    protected $membership_id = 0;

    /**
     * Threshold bestsellers
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $threshold_bestsellers = 1;

    /**
     * Node clean (SEO-friendly) URL
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255", nullable=false)
     */
    protected $clean_url = '';

    /**
     * Many-to-many relation with products table
     * 
     * @var    Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany(targetEntity="XLite_Model_CategoryProducts", mappedBy="categories", cascade={"persist","remove"})
     */
    protected $products;

    /**
     * Many-to-one relation with memberships table
     * 
     * @var    Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne(targetEntity="XLite_Model_Membership")
     * @JoinColumn(name="membership_id", referencedColumnName="membership_id")
     */
    protected $membership;

    /**
     * One-to-one relation with category_images table
     * 
     * @var    Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne(targetEntity="XLite_Model_CategoryImage")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $image;

    /**
     * The number of products assigned to the category
     * (Real-time calculated)
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $products_count = 0;

    /**
     * Category depth within categories tree
     * (Real-time calculated)
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $depth = 0;

    /**
     * The number of subcategories of the category
     * (Real-time calculated)
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $subCategoriesCount = 0;

    /**
     * Check if category has image 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasImage()
    {
        return !is_null(XLite_Core_Database::getRepo('XLite_Model_CategoryImage')->getImageById($this->category_id));
    }

    /**
     * Get category image object
     * 
     * @return XLite_Model_CategoryImage
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        return XLite_Core_Database::getRepo('XLite_Model_CategoryImage')->getImageById($this->category_id);
    }

    /**
     * Get subcategories plain list of the category
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubcategories()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoriesPlainList($this->category_id);
    }

    /**
     * Check if category has subcategories
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasSubcategories()
    {
        $data = XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoryFromHash($this->category_id);

        return ($data->subCategoriesCount > 0);
    }

    /**
     * Gets full path to the category as a string: <parent category>/.../<category name>
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStringPath()
    {
        $path = XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoryPath($this->category_id);

        $location = "";

        for ($i = 0; $i < count($path); $i++) {
            $location .= ($i > 0 ? '/' : '') . $path[$i]->name;
        }

        return $location;
    }

    /**
     * Check if category has neither products nor subcategories
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isEmpty()
    {
        $data = XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoryFromHash($this->category_id);

        return (0 == $data->products_count && 0 == $data->subCategoriesCount);
    }

    /**
     * Check if category exists
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExists()
    {
        isset($this->category_id);
    }

    /**
     * Get the number of products assigned to the category
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductsNumber()
    {
        $data = XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoryFromHash($this->category_id);

        return $data->products_count;
    }

    // TODO: rewrite function - this should be based on XLite_Model_Product
    public function getProducts()
    {
        $query = XLite_Core_Database::getQB()
            ->select('cp.product_id')
            ->from('XLite_Model_Category', 'c')
            ->leftJoin('c.products', 'cp')
            ->where('c.category_id = :categoryId')
            ->setParameter('categoryId', $this->category_id);

        $result = $query->getQuery()->getScalarResult();

        $pids = array();
        if (is_array($result)) {
            foreach ($result as $item) {
                if (isset($item['product_id'])) {
                    $pids[] = $item['product_id'];
                }
            }
        }

        $return = null;

        if (!empty($pids)) {
            $product = new XLite_Model_Product;
            $return = $product->findAll("product_id IN (" . implode(',', $pids) . ")");
        } 

        return $return;
    }

}
