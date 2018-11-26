<?php

namespace RKW\RkwConsultant\Validation;

use \RKW\RkwConsultant\Helper\Misc;
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
 * Class ConsultantServiceValidator
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantServiceValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{


    /**
     * validation
     *
     * @var mixed $objectSource
     * @return boolean
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function isValid($objectSource)
    {

        $isValid = true;

        // get required fields
        $settingsDefault = Misc::getSettings();

        $mandatoryFields = $settingsDefault['mandatoryFields'];

        // transform mandatoryFields to array
        $requiredFields = array();
        foreach ($mandatoryFields as $key => $fields) {
            if (!is_array($fields) && $fields) {
                foreach (explode(',', $fields) as $field) {

                    $requiredFields[$key][] = trim($field);
                }
            }
        }


        // properties of consultantService
        if ($methods = get_class_methods($objectSource)) {

            foreach ($methods as $getter) {

                if (strpos($getter, 'get') === 0) {

                    // don't validate object storages
                    if (!$objectSource->$getter() instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {

                        // if required fields are set
                        if ($requiredFields['consultantService']) {

                            if (in_array(lcfirst(substr($getter, 3)), $requiredFields['consultantService'])) {

                                if (!trim($objectSource->$getter())) {

                                    $this->result->forProperty(lcfirst(substr($getter, 3)))->addError(
                                        new \TYPO3\CMS\Extbase\Error\Error(
                                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                                'validator.not_filled',
                                                'rkw_consultant'
                                            ), 1449399525
                                        )
                                    );
                                    $isValid = false;
                                }
                            }
                        }
                    }
                }
            }
        }

        // qualificationArray (!!workaround!!)
        if (in_array('qualification', $requiredFields['consultantService'])) {
            $qualificationIsSet = false;

            if (is_array($objectSource->getQualificationArray())) {
                foreach ($objectSource->getQualificationArray() as $arrayEntry) {

                    if ($arrayEntry) {
                        $qualificationIsSet = true;
                    }
                }
                if (!$qualificationIsSet) {
                    $this->result->forProperty('qualification')->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.no_choice',
                                'rkw_consultant'
                            ), 1449399543
                        )
                    );
                    $isValid = false;
                }
            }
        }

        // subServiceArray (!!workaround!!)
        if (in_array('subService', $requiredFields['consultantService'])) {
            $subServiceIsSet = false;

            if (is_array($objectSource->getSubServiceArray())) {
                foreach ($objectSource->getSubServiceArray() as $arrayEntry) {

                    if ($arrayEntry) {
                        $subServiceIsSet = true;
                    }
                }
                if (!$subServiceIsSet) {
                    $this->result->forProperty('subService')->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.no_choice',
                                'rkw_consultant'
                            ), 1449399553
                        )
                    );
                    $isValid = false;
                }
            }
        }

        // qualification
        /*	if (!count($objectSource->getQualification())) {
                $this->result->forProperty('qualification')->addError(
                    new \TYPO3\CMS\Extbase\Error\Error(
                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                            'validator.no_choice',
                            'rkw_consultant'
                        ), 1449399543
                    )
                );
                $isValid = FALSE;
            }



            // subService
            if (!count($objectSource->getSubService())) {
                $this->result->forProperty('subService')->addError(
                    new \TYPO3\CMS\Extbase\Error\Error(
                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                            'validator.no_choice',
                            'rkw_consultant'
                        ), 1449399553
                    )
                );
                $isValid = FALSE;
            }
    */
        // contactPerson
        $setContactPersons = 0;
        $i = 0;
        /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
        foreach ($objectSource->getContactPerson()->toArray() as $contactPerson) {

            // only check if some field of contactPerson is filled:
            $imageIsSet = 0;
            foreach ($contactPerson->getImageUpload() as $image) {
                if ($image['error'] != 4) {
                    $imageIsSet = 1;
                }
            }

            if (!$imageIsSet && !trim($contactPerson->getFirstName()) && !trim($contactPerson->getLastName())
                && !trim($contactPerson->getTelephone()) && !trim($contactPerson->getEmail())
            ) {
                continue;
            }

            // something other as $i in the further algorithm
            $setContactPersons++;

            // properties of contactPerson
            if ($methods = get_class_methods($contactPerson)) {

                foreach ($methods as $getter) {

                    if (strpos($getter, 'get') === 0) {

                        // if required fields are set
                        if ($requiredFields['contactPerson']) {

                            if (in_array(lcfirst(substr($getter, 3)), $requiredFields['contactPerson'])) {

                                if (!trim($contactPerson->$getter())) {

                                    $this->result->forProperty('contactPerson.' . $i . '.' . lcfirst(substr($getter, 3)))->addError(
                                        new \TYPO3\CMS\Extbase\Error\Error(
                                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                                'validator.not_filled',
                                                'rkw_consultant'
                                            ), 1449399558
                                        )
                                    );
                                    $isValid = false;
                                }
                            }
                        }
                    }
                }
            }


            // images
            $allowedImageSize = $settingsDefault['mandatoryFields']['upload']['images']['allowedSize'];
            $allowedImageTypes = array_map('trim', explode(',', $settingsDefault['mandatoryFields']['upload']['images']['allowedTypes']));

            $j = 0;
            foreach ($contactPerson->getImageUpload() as $image) {

                if ($image['error'] != 4) {

                    $j++;

                    // -> && if a fileSize is set (If not set: Every size is allowed)
                    if ($image['size'] > $allowedImageSize && $allowedImageSize) {

                        $this->result->forProperty('contactPerson.' . $i . '.imageUpload.' . $j)->addError(
                            new \TYPO3\CMS\Extbase\Error\Error(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'validator.size_exceed',
                                    'rkw_consultant'
                                ), 1449399568
                            )
                        );
                        $isValid = false;
                        //===
                    }

                    // "pathinfo($file['type'],PATHINFO_EXTENSION)" doesn't work because of unspecific uploading
                    $fileExtension = explode('/', $image['type']);
                    if (!in_array($fileExtension[1], $allowedImageTypes)) {

                        $this->result->forProperty('contactPerson.' . $i . '.imageUpload.' . $j)->addError(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.wrong_type',
                                'rkw_consultant'
                            ) . implode(', ', $allowedImageTypes), 1449399576
                        );
                        $isValid = false;
                        //===
                    }
                }
            }


            // if no image was loaded ($i is 0 then), but one file or more is mandatory, then:
            $imageIsMandatory = $settingsDefault['mandatoryFields']['contactPersonImageMandatory'];
            if ($imageIsMandatory) {

                // for edit: Add existing images to count
                if (!($j + count($contactPerson->getImage()))) {

                    $this->result->forProperty('contactPerson.' . $i . '.imageUpload.' . $j)->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.one_image_mandatory',
                                'rkw_consultant'
                            ), 1449399583
                        )
                    );
                    $isValid = false;
                    //===
                }
            }

            $i++;
        }

        // check the number of filled contactPersons (count of mandatory contactPersons)
        if ($setContactPersons < $settingsDefault['mandatoryFields']['numberOfMandatoryContactPersons']) {

            $i = 0;
            // mark all fields of missing contactPersons which is mandatory
            foreach ($objectSource->getContactPerson()->toArray() as $contactPerson) {
                // count every contactPerson
                $setContactPersons++;


                if ($setContactPersons > $settingsDefault['mandatoryFields']['numberOfMandatoryContactPersons']) {
                    continue;
                }

                // properties of contactPerson
                if ($methods = get_class_methods($contactPerson)) {

                    // set first message only one time
                    if ($i == 0) {

                        $this->result->forProperty('contactPerson')->addError(
                            new \TYPO3\CMS\Extbase\Error\Error(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'validator.contact_persons_mandatory',
                                    'rkw_consultant'
                                ), 1449399591
                            )
                        );
                    }

                    foreach ($methods as $getter) {
                        if (strpos($getter, 'get') === 0) {
                            // if required fields are set
                            if ($requiredFields['consultantService']) {
                                if (in_array(lcfirst(substr($getter, 3)), $requiredFields['contactPerson'])) {
                                    if (!trim($contactPerson->$getter())) {

                                        $this->result->forProperty('contactPerson.' . $i . '.' . lcfirst(substr($getter, 3)))->addError(
                                            new \TYPO3\CMS\Extbase\Error\Error(
                                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                                    'validator.not_filled',
                                                    'rkw_consultant'
                                                ), 1449399597
                                            )
                                        );
                                        $isValid = false;
                                    }
                                }
                            }
                        }
                    }
                }
                $i++;
            }
        }

        return $isValid;
        //===
    }


}

