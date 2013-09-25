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

    public static function getCurrentLanguage()
    {
        return strtoupper($GLOBALS['TSFE']->config['config']['language']);
    }


    public static function getUserLanguage($default = 'en')
    {
        $lang = reset(explode("_",$_SERVER["HTTP_ACCEPT_LANGUAGE"]));
        if(empty($lang))
            return $default;
        else
            return $lang;
    }

    public static function getUserCountry($default = 'DE')
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        // Debug Setting für Entwicklungsserver
        if(reset(explode('.', $ip)) == '10')
            $ip = '217.91.168.134';

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

        if($ip_data && $ip_data->geoplugin_countryCode != null && $ip_data->geoplugin_countryCode != '')
        {
            return $ip_data->geoplugin_countryCode;
        } else {
            return $default;
        }
    }

    public static function setSession($name, $value)
    {
        $GLOBALS["TSFE"]->fe_user->setKey("ses", $name, $value);
    }

    public static function getSession($name)
    {
        return $GLOBALS["TSFE"]->fe_user->getKey("ses", $name);
    }

    public static function clearLogFile()
    {
        $logFile = PATH_site . '/devutils.log';

        file_put_contents($logFile, '');
    }

    public static function logToFile($data)
    {
        $logFile = PATH_site . '/devutils.log';

        if(is_object($data) || is_array($data)) {
            ob_start();
            print_r($data);
            $data = ob_get_clean();
        }

        file_put_contents($logFile, $data."\n", FILE_APPEND);
    }

    public static function deleteLogFile()
    {
        $logFile = PATH_site . '/devutils.log';


        unlink($logFile);
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
     public static function indentJson($json) {

        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '  ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i=0; $i<=$strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }


}