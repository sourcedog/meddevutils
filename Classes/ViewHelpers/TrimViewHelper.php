<?php

class Tx_Meddevutils_ViewHelpers_TrimViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

    /**
      * @return string
    */
    public function render() {
        return trim($this->renderChildren());
    }

}

?>