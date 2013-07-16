<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Tobias Schenk <schenk@medialis.net>, medialis.net UG (haftungsbeschränkt)
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
 * @package meddevutils
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Meddevutils_Utils_Div
{

    public static function debug($var)
    {
        ob_start();
        var_dump($var);
        echo "<pre>".ob_get_clean()."</pre>";
    }

    public static function log($var)
    {
        ob_start();
        var_dump($var);

        $debug = array_reverse(
            explode(
                "\n",
                ob_get_clean()
            )
        );

        $backtrace = debug_backtrace();
        $debug[] = $backtrace[0]['file'].' @ line '.$backtrace[0]['line'].':';

        foreach($debug as $debugLine) {
            $GLOBALS['TYPO3_DB']->exec_INSERTquery(
                'sys_log',
                array(
                    'userid' => 0,
                    'type' => 13,
                    'action' => 37,
                    'details' => $debugLine,
                    'tstamp' => time(),
                )
            );
        }

        $maxAge = time()-60*30;
        $GLOBALS['TYPO3_DB']->exec_DELETEquery(
            "sys_log",
            "type=13 AND action=37 AND timestamp < ".$maxAge
        );

    }

}