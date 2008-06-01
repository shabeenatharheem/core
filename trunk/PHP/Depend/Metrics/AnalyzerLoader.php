<?php
/**
 * This file is part of PHP_Depend.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008, Manuel Pichler <mapi@pmanuel-pichler.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Metrics
 * @author     Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright  2008 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.manuel-pichler.de/
 */

/**
 * This class provides a simple way to load all required analyzers by class,
 * implemented interface or parent class.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Metrics
 * @author     Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright  2008 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.manuel-pichler.de/
 */
class PHP_Depend_Metrics_AnalyzerLoader implements IteratorAggregate
{
    /**
     * All matching analyzer instances.
     *
     * @type array<PHP_Depend_Metrics_AnalyzerI>
     * @var array(PHP_Depend_Metrics_AnalyzerI) $_analyzers
     */
    protected $_analyzers = array();
    
    public function __construct(array $acceptedTypes)
    {
        $dirs = new DirectoryIterator(dirname(__FILE__));
        foreach ($dirs as $dir) {
            if (!$dir->isDir() || $dir->isDot()) {
                continue;
            }
            $files = new DirectoryIterator($dir->getPathname());
            foreach ($files as $file) {
                if ($file->getFilename() !== 'Analyzer.php') {
                    continue;
                }
                include_once $file->getPathname();
                
                $package   = $dir->getFilename();
                $className = sprintf('PHP_Depend_Metrics_%s_Analyzer', $package);

                $providedTypes = array_merge(
                    array($className),
                    class_implements($className, false),
                    class_parents($className)
                );
                
                if (count(array_intersect($acceptedTypes, $providedTypes)) > 0) {
                    $this->_analyzers[] = new $className();                    
                }
            }
        }
    }
    
    /**
     * Returns a countable iterator of {@link PHP_Depend_Metrics_AnalyzerI}
     * instances that match against the given accepted types. 
     *
     * @return Iterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_analyzers);
    }
}