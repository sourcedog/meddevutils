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
class Tx_Meddevutils_Domain_Model_GoogleApi extends Tx_Extbase_DomainObject_AbstractEntity
{

    protected $googleLookUpUrl = 'http://maps.googleapis.com/maps/api/geocode/xml';


    public function getLocation($address)
    {
        $address = (array)$address;
        foreach($address as $addressKey => $addressVal) {
            $address[$addressKey] = urlencode(preg_replace ( "![^a-zA-Z0-9 äöüß]+!", "", utf8_encode($addressVal)));
        }
        $address = implode(', ', array_filter($address));

        $request = $this->googleLookUpUrl.'?address=' . urlencode(urldecode($address)) . '&sensor=false&key=AIzaSyDzpOTbunhPIbBmJ1Gf51H9rJcNkSLRr8I';

        $objSimpleXml = new SimpleXMLElement(
            utf8_encode(
                file_get_contents($request)
            )
        );

        if($objSimpleXml->status != 'OK') {
            return false;
        }

        $_g = (array)$objSimpleXml->result->geometry->location;
        $geo = array(
            'latitude' => $_g['lat'],
            'longitude' => $_g['lng']
        );
        return $geo;
    }

}
?>