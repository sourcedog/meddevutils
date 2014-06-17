<?php
/**
 * This class is a demo view helper for the Fluid templating engine.
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class Tx_Meddevutils_ViewHelpers_TypoLinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    /**
     * This function renders a value with TypoScript
     *
     * @param string $value The text / image
     * @param string $parameter The link target
     * @param string $additionalParams additionalParams
     * @return parsed value through TypoScript
     */
    public function render($value, $parameter, $additionalParams = '') {

        if(file_exists($value)) {

            $typolinkConf = array(
                'file' => $value,
                'stdWrap.' => array(
                    'typolink.' => array(
                        'parameter' => $parameter,
                        'additionalParams' => $additionalParams
                    )
                )
            );

            return $GLOBALS['TSFE']
                ->cObj
                ->IMAGE($typolinkConf);

        } else {

            if(empty($value))
                $value = $this->renderChildren();

            $typolinkConf = array(
                'value' => $value,
                'stdWrap.' => array(
                    'typolink.' => array(
                        'parameter' => $parameter,
                        'additionalParams' => $additionalParams
                    ),
                ),
            );

            return $GLOBALS['TSFE']
                ->cObj
                ->TEXT($typolinkConf);

        }

    }
}

?>