<?php

class Tx_Meddevutils_ViewHelpers_ImplodeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objectStorage', 'mixed', 'ObjectStorage of items to be combined', TRUE);
        $this->registerArgument('attribute', 'mixed', 'Attribute to be used', TRUE);
    }

    public function render()
    {
        $returnArr = array();

        foreach($this->arguments['objectStorage'] as $object)
        {
            $method = 'get' . ucfirst($this->arguments['attribute']);

            $returnArr[] = $object->$method();
        }

        return implode(', ', $returnArr);
    }

}

?>