<?php

/**
 * Formats a Timestamp or DateTime-Object in strftime()
 * @api
 */
class Tx_Meddevutils_ViewHelpers_DateFormatGmtViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper
{
    /**
     * Render the supplied DateTime object as a formatted date using strftime.
     *
     * @param mixed $date either a DateTime object or a string (UNIX-Timestamp)
     * @param string $format Format String which is taken to format the Date/Time
     * @return string Formatted date
     * @api
     */
    public function render($date = NULL, $format = '%H:%i')
    {
        if ($date === NULL) {
            $date = $this->renderChildren();
            if ($date === NULL) {
                return '';
            }
        }
        return gmdate($format, $date);
    }
}