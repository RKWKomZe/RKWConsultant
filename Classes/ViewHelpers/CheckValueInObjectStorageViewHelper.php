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
 * Class CheckValueInObjectStorageViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CheckValueInObjectStorageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Is a VALUE of a certain PROPERTY set in given ObjectStorage?
     * Return FALSE if there isn't an ObjectStorage given (on create)
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $objectStorage |NULL
     * @param string $property
     * @param mixed $value
     * @return boolean
     */
    public function render($objectStorage, $property, $value)
    {

        if ($objectStorage) {
            $getter = 'get' . ucfirst($property);

            foreach ($objectStorage as $item) {

                if ($value == $item->$getter()) {
                    return true;
                    //===
                }
            }
        }

        return false;
        //===
    }


}