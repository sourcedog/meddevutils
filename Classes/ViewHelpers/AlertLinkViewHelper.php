<?php

class Tx_Meddevutils_ViewHelpers_AlertLinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

    /**
     * This function renders a value with TypoScript
     *
     * @param string $text The text
     * @return string
     */
    public function render($text)
    {
        $pattern = '/(<link)(.*?)(>)/i';
        $replacement = '$1$2 - alert-link$3';
        $text = preg_replace($pattern, $replacement, $text);

        return $text;
    }
}

?>