<?php

namespace RKW\RkwConsultant\Helper;

use \RKW\RkwBasics\Helper\Common;
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
 * Misc
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Misc implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;


    /**
     * function createSha1
     *
     * @param int $someIndividualValue
     * @return string
     */
    public function createSha1($someIndividualValue)
    {
        return sha1($someIndividualValue);
        //====
    }

    /**
     * createFileReference
     *
     * @param array $file
     * @param string $fieldName
     * @param object $ownerObject
     * @return void
     * @throws \TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function createFileReference($file, $fieldName, $ownerObject)
    {
        $settingsDefault = $this->getSettings();

        // file uploaden
        /** @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
        $storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');

        /** @var \TYPO3\CMS\Core\Resource\ResourceStorage $storage */
        $storage = $storageRepository->findByUid($settingsDefault['sysFileStorageUid']);

        if ($storage) {

            $newFileObject = $storage->addFile($file['tmp_name'], $storage->getRootLevelFolder(), $file['name']);
            $newFileObject = $storage->getFile($newFileObject->getIdentifier());

            /** @var \RKW\RkwConsultant\Domain\Model\FileReference $newFileReference */
            $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Domain\\Repository\\FileRepository');
            $newFile = $fileRepository->findByUid($newFileObject->getProperty('uid'));

            /** @var \RKW\RkwConsultant\Domain\Model\FileReference $newFileReference */
            $newFileReference = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Domain\\Model\\FileReference');
            $newFileReference->setFile($newFile);
            $newFileReference->setFieldname($fieldName);

            if ($fieldName == 'file') {
                $ownerObject->addFile($newFileReference);
            } else {
                $ownerObject->addImage($newFileReference);
            }

        } else {

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::INFO,
                sprintf('SysFileStorage not found or misconfigured in typoscript. Please define a correct storage uid for file uploads.')
            );
        }

    }

    /**
     * determineGeoDataOfConsultant
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return void
     */
    public function determineGeoDataOfConsultant(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {
        // geolocation long lat
        /** @var \RKW\RkwGeolocation\Service\Geolocation $geolocation */
        $geolocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');
        $geolocation->setAddress($consultant->getZip() . ',' . $consultant->getAddress());

        /** @var \RKW\RkwGeolocation\Domain\Model\Geolocation $geoData */
        $geoData = $geolocation->fetchGeoData();

        if ($geoData) {
            $consultant->setLongitude($geoData->getLongitude());
            $consultant->setLatitude($geoData->getLatitude());

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::INFO,
                sprintf('Geo-Coordinates successfully retrieved.')
            );
        } else {
            // log if given zip or address faulty (and googleMaps can't determine some coordinates)
            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::WARNING,
                sprintf('Given ZIP or address faulty. Geo-Coordinates cannot retrieved by API.')
            );
        }

    }

    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public static function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {
        /**
         * Folgend verwendbar:
         * use \RKW\RkwConsultant\Helper\Misc;
         * $settingsDefault = Misc::getSettings();
         * $frontendUser->setTxRkwconsultantRole(intval($settingsDefault['consultant']['role']['admin']));
         */
        return Common::getTyposcriptConfiguration('Rkwconsultant', $which);
        //===
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {
        if (!$this->logger instanceof \TYPO3\CMS\Core\Log\Logger) {
            $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
        }

        return $this->logger;
        //===
    }

}
