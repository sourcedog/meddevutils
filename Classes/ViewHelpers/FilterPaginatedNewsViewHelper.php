<?php

class Tx_Meddevutils_ViewHelpers_FilterPaginatedNewsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

    /**
     * Render everything
     *
     * @param Tx_Extbase_Persistence_QueryResultInterface $objects
     * @param string $language
     * @return string
     */
    public function render(Tx_Extbase_Persistence_QueryResultInterface $objects, $language = 0) {
        // Get query
        $query = $objects->getQuery();

        // Get all items
        //$query->getQuerySettings()->setRespectEnableFields(FALSE);

        // Limit items to language
        $constraints[] = $query->like('sys_language_uid',$language);
        $constraints[] = $query->getConstraint();

        // Remove wrong language items
        if(count($constraints)>0) {
            $query->matching($query->logicalAnd($constraints));
        }

        // Execute query
        $modifiedObjects = $query->execute();

        //Tx_Extbase_Utility_Debugger::var_dump($modifiedObjects->count());

        return $modifiedObjects;
    }

}

?>