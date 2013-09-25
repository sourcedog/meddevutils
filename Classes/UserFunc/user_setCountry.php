<?php

class_alias('Tx_Meddevutils_Utils_Div', 'Dev');

class user_setCountry
{

    function main($content, $conf)
    {

        // Land für Suche setzen
        if(is_null(Dev::getSession('userCountry'))) {
            Dev::setSession(
                'userCountry',
                Dev::getUserCountry()
            );
        }

        // Sprache setzen, falls nicht gesetzt
        if(is_null(Dev::getSession('userLanguage'))) {
            if(Dev::getCurrentLanguage() != Dev::getUserLanguage()) {
                Dev::setSession(
                    'userLanguage',
                    (isset($conf[Dev::getUserLanguage()])) ? Dev::getUserLanguage() : 'en'
                );
            } else {
                Dev::setSession(
                    'userLanguage',
                    Dev::getCurrentLanguage()
                );
            }

            $redirectUrl =  $GLOBALS['TSFE']
                ->cObj
                ->typolink_URL(array(
                    parameter => $GLOBALS['TSFE']->id,
                    additionalParams => '&L=3',
                    addQueryString => 1
                ));

        } else {
            Dev::setSession(
                'userLanguage',
                Dev::getCurrentLanguage()
            );
        }
    }

}