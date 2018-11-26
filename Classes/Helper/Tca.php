<?php

namespace RKW\RkwConsultant\Helper;

use \RKW\RkwBasics\Helper\Common;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

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
 * Class Tca
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tca
{

    /**
     * Fetches feUsers for TCA-fields
     *
     * @params array &$params
     * @params object $pObj
     * @return void
     */
    public function getFeUsers(array &$params, $pObj)
    {

        $settings = Common::getTyposcriptConfiguration('RkwConsultant', \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

        /** @var \RKW\RkwConsultant\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        $frontendUserRepository = $objectManager->get('RKW\RkwConsultant\Domain\Repository\FrontendUserRepository');

        $frontendUserGroupList = GeneralUtility::trimExplode(',', $settings['consultant']['allowedOwnerGroupsList'], true);
        if ($frontendUserList = $frontendUserRepository->findAllByFrontendGroup($frontendUserGroupList)) {

            /** @var \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser */
            foreach ($frontendUserList as $frontendUser) {
                if ($frontendUser->getEmail()) {
                    $params['items'][] = array($frontendUser->getLastName() . ', ' . $frontendUser->getFirstName() . ' (' . $frontendUser->getUsername() . ')', $frontendUser->getUid());
                }
            }
        }
    }
}