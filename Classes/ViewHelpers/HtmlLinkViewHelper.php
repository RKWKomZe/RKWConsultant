<?php
namespace RKW\RkwConsultant\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


$currentVersion = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
if ($currentVersion < 8000000) {

    /**
     * Class HtmlLinkViewHelper
     *
     * @author Maximilian Fäßler <maximilian@faesslerweb.de>
     * @author Steffen Kroggel <developer@steffenkroggel.de>
     * @copyright Rkw Kompetenzzentrum
     * @package RKW_RkwConsultant
     * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
     * @deprecated
     */
    class HtmlLinkViewHelper extends AbstractViewHelper
    {


        /**
         * Sets HTML-Links from plaintext
         *
         * @param string $value string to format
         * @return string the altered string.
         * @api
         */
        public function render($value = null)
        {
            return static::renderStatic(
                array(
                    'value' => $value,
                ),
                $this->buildRenderChildrenClosure(),
                $this->renderingContext
            );
        }

        /**
         * Applies preg_replace on the specified value.
         *
         * @param array $arguments
         * @param \Closure $renderChildrenClosure
         * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
         * @return string
         */
        public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext)
        {
            $value = $arguments['value'];
            if ($value === null) {
                $value = $renderChildrenClosure();
            }

            return preg_replace('/(http:\/\/([^\s]+))/i', '<a href="$1" target="_blank">$2</a>', $value);
        }
    }

} else {

    /**
     * Class HtmlLinkViewHelper
     *
     * @author Maximilian Fäßler <maximilian@faesslerweb.de>
     * @author Steffen Kroggel <developer@steffenkroggel.de>
     * @copyright Rkw Kompetenzzentrum
     * @package RKW_RkwConsultant
     * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
     */
    class HtmlLinkViewHelper extends AbstractViewHelper
    {


        /**
         * Sets HTML-Links from plaintext
         *
         * @param string $value string to format
         * @return string the altered string.
         * @api
         */
        public function render($value = null)
        {
            return static::renderStatic(
                array(
                    'value' => $value,
                ),
                $this->buildRenderChildrenClosure(),
                $this->renderingContext
            );
        }

        /**
         * Applies preg_replace on the specified value.
         *
         * @param array $arguments
         * @param \Closure $renderChildrenClosure
         * @param RenderingContextInterface $renderingContext
         * @return string
         */
        public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
        {
            $value = $arguments['value'];
            if ($value === null) {
                $value = $renderChildrenClosure();
            }

            return preg_replace('/(http:\/\/([^\s]+))/i', '<a href="$1" target="_blank">$2</a>', $value);
        }
    }
}

