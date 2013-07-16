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
class Tx_Meddevutils_Domain_Repository_AbstractRepository extends Tx_Extbase_Persistence_Repository {

    protected $orderings = null;
    protected $respectEnableFields = true;
    protected $respectStoragePage = true;
    protected $limit = null;

    public function findByFilter($findArray)
    {
        $query = $this->createQuery();

        if(!is_null($this->orderings)) {
            $query->setOrderings($this->orderings);
        }

        if(!is_null($this->limit)) {
            $query->setLimit($this->limit);
        }

        $query->getQuerySettings()
              ->setRespectEnableFields($this->respectEnableFields)
              ->setRespectStoragePage($this->respectStoragePage);

        if(empty($findArray))
            return array();

        foreach($findArray as $findArrayInner) {

            if(empty($findArrayInner))
                continue;

            $filterArrayInner = array();
            foreach($findArrayInner as $findValue) {
                $operator = $findValue[1];
                $filterArrayInner[] = $query->$operator($findValue[0], $findValue[2]);
            }
            $filterArray[] = $query->logicalOr($filterArrayInner);

        }

        $query->matching(
            $query->logicalAnd($filterArray)
        );

        $result = $query->execute();
        return $result;
    }

    public function setLimit($limit)
    {
        if((int)$limit > 0)
            $this->limit = (int)$limit;

        return $this;
    }

    public function setOrderings($orderings)
    {
        $this->orderings = $orderings;

        return $this;
    }

    public function setRespectEnableFields($respectEnableFields)
    {
        $this->respectEnableFields = $respectEnableFields;

        return $this;
    }

    public function setRespectStoragePage($respectStoragePage)
    {
        $this->respectStoragePage = $respectStoragePage;

        return $this;
    }

}