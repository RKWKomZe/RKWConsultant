<?php

namespace RKW\RkwConsultant\Hooks;
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
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TceMainHooks
{

    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference)
    {
        try {
            // modification of longitude and latitude via hook
            if ($table == 'tx_rkwconsultant_domain_model_consultant') {

                $record_tx_rkwconsultant_domain_model_consultant = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwconsultant_domain_model_consultant', $id);

                if ($record_tx_rkwconsultant_domain_model_consultant) {

                    $street = $record_tx_rkwconsultant_domain_model_consultant['address'];
                    $zip = $record_tx_rkwconsultant_domain_model_consultant['zip'];


                    // geolocation long lat
                    /** @var \RKW\RkwGeolocation\Service\Geolocation $geolocation */
                    $geolocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');
                    $geolocation->setAddress($zip . ',' . $street);

                    /** @var \RKW\RkwGeolocation\Domain\Model\Geolocation $geoData */
                    $geoData = $geolocation->fetchGeoData();

                    if ($geoData) {
                        $fieldArray['longitude'] = $geoData->getLongitude();
                        $fieldArray['latitude'] = $geoData->getLatitude();

                        $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully fetched geodata for "%s".', $zip . ',' . $street));
                    } else {
                        $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::WARNING, sprintf('Could not fetch geodata for "%s".', $zip . ',' . $street));
                    }
                }
            }

        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Could fetch geodata. Reason: %s', $e->getMessage()));
        }
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
        //===
    }
}
