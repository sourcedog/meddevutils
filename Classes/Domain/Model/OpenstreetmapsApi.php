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
class Tx_Meddevutils_Domain_Model_OpenstreetmapsApi extends Tx_Extbase_DomainObject_AbstractEntity
{

    //protected $lookUpUrl = 'http://nominatim.openstreetmap.org/search/';
    protected $lookUpUrl = 'http://open.mapquestapi.com/nominatim/v1/search/';


    public function getLocation($address)
    {
        $address = (array)$address;
        /*foreach($address as $addressKey => $addressVal) {
            $address[$addressKey] = urlencode(preg_replace ( "![^a-zA-Z0-9 äöüß]+!", "", utf8_encode($addressVal)));
        }*/
        $address = implode(', ', array_filter($address));

        $request = $this->lookUpUrl . urlencode($address) . '?format=json&polygon=0&addressdetails=1';
        //$request = str_replace("+", "%20", $request);

        $data = json_decode(file_get_contents($request));

        $geo = array(
            'latitude' => $data[0]->lat,
            'longitude' => $data[0]->lon
        );
        return $geo;
    }

}
?>