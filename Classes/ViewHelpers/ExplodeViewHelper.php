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

/**
 * Class ExplodeViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ExplodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * php explode
     *
     * @param string $string
     * @return array
     */
    public function render($string)
    {

        // DO NOT USE THIS GeneralUtility function! Index isn't starting by value 1 -> But this is important!
        //	return \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $string);

        $exploded = array_map('trim', explode(',', $string));

        // for fluid the index has to start by 1
        $exploded = array_combine(range(1, count($exploded)), $exploded);

        return $exploded;
        //===
    }


}