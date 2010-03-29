<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LC viewer
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * LC viewer; not a regular widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Controller extends XLite_View_Abstract
{
    /**
     * Content of the currnt page
     * NOTE: this is a text, so it's not passed by reference; do not wrap it into a getter (or pass by reference)
     * NOTE: until it's not accessing via the function, do not change its access modifier
     * 
     * @var    string
     * @access public
     * @since  3.0.0
     */
    public static $bodyContent = null;


    /**
     * Send headers 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function startPage()
    {
        // send no-cache headers
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: text/html');
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SILENT       => new XLite_Model_WidgetParam_Bool('Silent', false),
            self::PARAM_DUMP_STARTED => new XLite_Model_WidgetParam_Bool('Dump started', false)
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('body.tpl');
    }

    /**
     * isSilent 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSilent()
    {
        return $this->getParam(self::PARAM_SILENT);
    }

    /**
     * isDumpStarted 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isDumpStarted()
    {
        return $this->getParam(self::PARAM_DUMP_STARTED);
    }

    /**
     * getContentWidget 
     * 
     * @return XLite_View_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getContentWidget()
    {
        return $this->getWidget(array(XLite_View_Abstract::PARAM_TEMPLATE => $this->template), 'XLite_View_Content');
    }

    /**
     * prepareContent 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function prepareContent()
    {
        self::$bodyContent = $this->getContentWidget()->getContent();
    }

    /**
     * useDefaultDisplayMode 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function useDefaultDisplayMode()
    {
        return $this->isExported() || XLite::isAdminZone();
    }

    /**
     * displayPage 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function displayPage()
    {
        if ($this->useDefaultDisplayMode()) {
            $this->getContentWidget()->display();
        } else {
            $this->prepareContent();
            $this->startPage();
            parent::display();
        }
    }

    /**
     * refreshEnd 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function refreshEnd()
    {
        func_refresh_end();
    }

    /**
     * postProcess 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function postProcess()
    {
        XLite::getController()->postprocess();
    }


    /**
     * __construct 
     * 
     * @param array  $params          widget params
     * @param string $contentTemplate central area template
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array(), $contentTemplate = null)
    {
        parent::__construct($params);

        $this->template = $contentTemplate;
    }

    /**
     * Show current page and, optionally, footer  
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function display()
    {
        if (!$this->isSilent()) {
            $this->displayPage();
        }

        if ($this->isDumpStarted()) {
            $this->refreshEnd();
        }

        $this->postProcess();
    }
}

