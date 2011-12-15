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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Plugin\Multilangs;

/**
 * Routines for Doctrine library
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * List of <file, code> pairs (code replacements)
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $replacements = array();

    /**
     * Autogenerated "translate" property
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $templateTranslation = <<<CODE
    /**
     * Translations (relation). AUTOGENERATED
     *
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     *
     * @OneToMany (targetEntity="____TRANSLATION_CLASS____", mappedBy="owner", cascade={"persist","remove"})
     */
    protected \$translations;
CODE;

    /**
     * Autogenerated getter
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $templateGet = <<<CODE
    /**
     * Translation getter. AUTOGENERATED
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function ____GETTER____()
    {
        return \$this->getSoftTranslation()->____GETTER____();
    }
CODE;

    /**
     * Autogenerated setter
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $templateSet = <<<CODE
    /**
     * Translation setter. AUTOGENERATED
     *
     * @param string \$value value to set
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function ____SETTER____(\$value)
    {
        return \$this->getTranslation(\$this->editLanguage)->____SETTER____(\$value);
    }
CODE;

    /**
     * Autogenerated "owner" property
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $templateOwner = <<<CODE
    /**
     * Translation owner (relation). AUTOGENERATED
     *
     * @var    ____OWNER_CLASS____
     * @access protected
     *
     * @ManyToOne  (targetEntity="____MAIN_CLASS____", inversedBy="translations")
     * @JoinColumn (name="id", referencedColumnName="____MAIN_CLASS_ID____")
     */
    protected \$owner;
CODE;


    /**
     * Execute certain hook handler
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepSecond()
    {
        // It's the metadata collected by Doctrine
        foreach ($this->getMetadata() as $main) {

            // Process only certain classes
            if (is_subclass_of($main->name, '\XLite\Model\Base\I18n')) {
                $translation = $this->getTranslationClass($main);

                // If the "translation" field is not added manually
                if (empty($translation)) {
                    $translation = $this->getTranslationClassDefault($main);
                }

                if (!empty($translation)) {
                    $this->addReplacement($main, 'translation', $this->getTranslationSubstitutes($main, $translation));

                    // Iterate over all translatable fields
                    foreach ($this->getTranslationFields($translation) as $field) {

                        // Two iteartions: "getter" and "setter"
                        foreach ($this->getAutogeneratedMethodsList() as $method => $entry) {
                            $this->addReplacement($main, $method, $this->getMethodSubstitutes($main, $entry, $method, $field));
                        }
                    }

                    // Add the "owner" field to the main class (if not defined manually)
                    if (!property_exists($translation, 'owner')) {
                        $this->addReplacement($translation, 'owner', $this->getOwnerSubstitutes($main));
                    }
                }
            }
        }

        // Populate changes
        $this->writeData();
    }


    // ------------------------------ Replacements -

    /**
     * Add code to replace
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $data        Class metadata
     * @param string                              $template    Template to use
     * @param array                               $substitutes List of entries to substitude
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addReplacement(\Doctrine\ORM\Mapping\ClassMetadata $data, $template, array $substitutes)
    {
        if (!empty($substitutes)) {

            $file = \Includes\Utils\Converter::getClassFile($data->reflClass->getName());

            if (!isset($this->replacements[$file])) {
                $this->replacements[$file] = '';
            }

            $this->replacements[$file] .= $this->substituteTemplate($template, $substitutes) . PHP_EOL . PHP_EOL;
        }
    }


    // ------------------------------ "Translation"-related methods -

    /**
     * Check if the "translation" field is defined manually
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $main Main class metadata
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTranslationClass(\Doctrine\ORM\Mapping\ClassMetadata $main)
    {
        $class = null;

        if (property_exists($main, 'associationMappings') && isset($main->associationMappings['translations'])) {
            $class = $this->getMetadata($main->associationMappings['translations']['targetEntity']);
        }

        return $class;
    }

    /**
     * Return default name for translation class
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $main Main class metadata
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTranslationClassDefault(\Doctrine\ORM\Mapping\ClassMetadata $main)
    {
        return $this->getMetadata($main->name . 'Translation');
    }

    /**
     * Return the array of substitutes for the "translation" template
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $main        Current multilang model class metadata
     * @param \Doctrine\ORM\Mapping\ClassMetadata $translation Translation class metadata
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTranslationSubstitutes(
        \Doctrine\ORM\Mapping\ClassMetadata $main,
        \Doctrine\ORM\Mapping\ClassMetadata $translation
    ) {
        $result = array();

        if (!$main->reflClass->hasProperty('translations')) {
            $result['____TRANSLATION_CLASS____'] = $translation->name;
        }

        return $result;
    }

    /**
     * Return list of the translatabe fields for a class
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $translation Translation class metadata
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTranslationFields(\Doctrine\ORM\Mapping\ClassMetadata $translation)
    {
        return array_diff(
            $translation->fieldNames,
            call_user_func(array($translation->name, 'getInternalProperties'))
        );
    }


    // ------------------------------ Methods to generate getters and setters -

    /**
     * Return list of getters/setters patterns
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAutogeneratedMethodsList()
    {
        return array('get' => '____GETTER____', 'set' => '____SETTER____');
    }

    /**
     * Return the array of substitutes for the getters/setters templates
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $main   Current multilang model class metadata
     * @param string                              $entry  Entry to substitute
     * @param string                              $method "get" or "set"
     * @param string                              $field  Name of field to get or set
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMethodSubstitutes(\Doctrine\ORM\Mapping\ClassMetadata $main, $entry, $method, $field)
    {
        $result = array();

        if (!$main->reflClass->hasMethod($method .= \Includes\Utils\Converter::convertToCamelCase($field))) {
            $result[$entry] = $method;
        }

        return $result;
    }


    // ------------------------------ "Owner"-related methods -

    /**
     * Return the array of substitutes for the "owner" template
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $main Current multilang model class metadata
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOwnerSubstitutes(\Doctrine\ORM\Mapping\ClassMetadata $main)
    {
        return array(
            '____OWNER_CLASS____'   => $main->name,
            '____MAIN_CLASS____'    => $main->name,
            '____MAIN_CLASS_ID____' => array_shift($main->identifier),
        );
    }


    // ------------------------------ Methods to write changes -

    /**
     * Put prepared code into the files
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function writeData()
    {
        foreach ($this->replacements as $file => $code) {
            \Includes\Utils\FileManager::write(
                $file = LC_DIR_CACHE_CLASSES . $file,
                \Includes\Decorator\Utils\Tokenizer::addCodeToClassBody($file, $code)
            );
        }
    }

    /**
     * Substitute entries in code template
     *
     * @param string $template template to prepare
     * @param array  $entries  list of <entry, value> pairs
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function substituteTemplate($template, array $entries)
    {
        return str_replace(array_keys($entries), $entries, $this->{'template' . ucfirst($template)});
    }


    // ------------------------------ Auxiliary methods -

    /**
     * Alias
     *
     * @param string $class Class name OPTIONAL
     *
     * @return array|\Doctrine\ORM\Mapping\ClassMetadata
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMetadata($class = null)
    {
        return \Includes\Decorator\Plugin\Doctrine\Utils\EntityManager::getAllMetadata($class);
    }
}
