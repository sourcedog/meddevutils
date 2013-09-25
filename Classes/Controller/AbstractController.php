<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Tobias Schenk <schenk@medialis.net>, medialis.net UG (haftungsbeschränkt)
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package medretailerdatabase
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Meddevutils_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {

    protected $logFile;

    public function __construct()
    {
        $this->logFile = PATH_site . '/devutils.log';
        parent::__construct();
    }

    protected function clearLogFile()
    {
        file_put_contents($this->logFile, '');

        return $this;
    }

    protected function log($data)
    {
        if(is_object($data) || is_array($data)) {
            ob_start();
            print_r($data);
            $data = ob_get_clean();
        }

        file_put_contents($this->logFile, $data."\n", FILE_APPEND);

        return $this;
    }

    protected function deleteLogFile()
    {
        unlink($this->logFile);

        return $this;
    }

    protected function __($key, $vars = array()) {
        if(empty($vars)) {
            return Tx_Extbase_Utility_Localization::translate($key, $this->extensionName);
        } else {
            return vsprintf(Tx_Extbase_Utility_Localization::translate($key, $this->extensionName), (array)$vars);
        }
    }

    protected function initializeView()
    {
        $this->view->assign('settings', $this->settings);
    }

    protected function setPageTitle($newTitle)
    {
        $GLOBALS['TSFE']->page['title'] = $newTitle;
        $GLOBALS['TSFE']->content = preg_replace(
            '#<title>.+<\/title>#',
            '<title>'. $newTitle .'</title>',
            $GLOBALS['TSFE']->content
        );
    }

    protected function setMetaDescription($newDescription)
    {
        $GLOBALS['TSFE']->content = str_replace(
            '</head>',
            '<meta name="description" content="'.$newDescription.'" /></head>',
            $GLOBALS['TSFE']->content
        );
        $GLOBALS['TSFE']->page['description'] = $newDescription;
    }

    protected function setMetaKeywords($keywords)
    {
        $GLOBALS['TSFE']->content = str_replace(
            '</head>',
            '<meta name="keywords" content="'.$keywords.'" /></head>',
            $GLOBALS['TSFE']->content
        );
        $GLOBALS['TSFE']->page['keywords'] = $keywords;
    }

    protected function setSession($name, $value)
    {
        $GLOBALS["TSFE"]->fe_user->setKey("ses", $name, $value);
    }

    protected function getSession($name)
    {
        return $GLOBALS["TSFE"]->fe_user->getKey("ses", $name);
    }
}
