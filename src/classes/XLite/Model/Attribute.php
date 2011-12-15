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
 * @since     1.0.14
 */

namespace XLite\Model;

/**
 * Attribute 
 *
 * @see   ____class_see____
 * @since 1.0.14
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Attribute")
 * @Table  (name="attributes",
 *          uniqueConstraints={
 *              @UniqueConstraint (name="name", columns={"name"})
 *          }
 * )
 * @InheritanceType     ("JOINED")
 * @DiscriminatorColumn (name="type", type="uinteger")
 * @DiscriminatorMap    ({
 *      "0" = "XLite\Model\Attribute",
 *      "1" = "XLite\Model\Attribute\Type\Number",
 *      "2" = "XLite\Model\Attribute\Type\Text"
 * })
 */
class Attribute extends \XLite\Model\Base\I18n
{
    /**
     * Possible attribute types
     */
    const TYPE_NUMBER   = 1;
    const TYPE_TEXT     = 2;
    const TYPE_SELECTOR = 3;

    /**
     * Readable type names
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.14
     */
    protected static $typeNames = array(
        self::TYPE_NUMBER   => 'Number',
        self::TYPE_TEXT     => 'Text',
        self::TYPE_SELECTOR => 'Selector',
    );

    /**
     * Attribute unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Internal attribute name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Column (type="string", length="64")
     */
    protected $name;

    /**
     * Position in the list
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Column (type="integer")
     */
    protected $pos;

    /**
     * Relation to a group entity
     *
     * @var   \XLite\Model\Attribute\Group
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @ManyToOne  (targetEntity="XLite\Model\Attribute\Group", inversedBy="attributes")
     * @JoinColumn (name="groupId", referencedColumnName="id")
     */
    protected $group;

    /**
     * Relation to attribute choices (only for "Selector" type)
     *
     * @var   \Doctrine\ORM\PersistentCollection
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @OneToMany (targetEntity="XLite\Model\Attribute\Choice", mappedBy="attribute", fetch="LAZY", cascade={"all"})
     */
    protected $choices;

    /**
     * Return readable name for the attribute type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getTypeName()
    {
        return \Includes\Utils\ArrayManager::getIndex(static::$typeNames, $this->getType(), true) ?: 'N/A';
    }
}
