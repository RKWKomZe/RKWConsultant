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
 * Class ConsultantValidator
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
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

        // Start: Privacy Workaround
        if (!$objectSource->getPrivacy()) {
            $this->result->forProperty('privacy')->addError(
                new \TYPO3\CMS\Extbase\Error\Error(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'registrationController.error.accept_privacy',
                        'rkw_registration'
                    ), 1526904113
                )
            );
            $isValid = false;
        }

        // End: Privacy Workaround

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

        // properties
        if ($methods = get_class_methods($objectSource)) {

            foreach ($methods as $getter) {

                if (strpos($getter, 'get') === 0) {

                    // if required fields are set
                    if ($requiredFields['consultant']) {

                        if (in_array(lcfirst(substr($getter, 3)), $requiredFields['consultant'])) {

                            if (!trim($objectSource->$getter())) {

                                $this->result->forProperty(lcfirst(substr($getter, 3)))->addError(
                                    new \TYPO3\CMS\Extbase\Error\Error(
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                            'validator.not_filled',
                                            'rkw_consultant'
                                        ), 1449314603
                                    )
                                );
                                $isValid = false;
                            }
                        }
                    }
                }
            }
        }


        // files
        $allowedFileSize = $settingsDefault['mandatoryFields']['upload']['files']['allowedSize'];
        $allowedFileTypes = array_map('trim', explode(',', $settingsDefault['mandatoryFields']['upload']['files']['allowedTypes']));

        $i = 0;
        foreach ($objectSource->getFileUpload() as $file) {

            if ($file['error'] != 4) {

                $i++;

                // -> && if a fileSize is set (If not set: Every size is allowed)
                if ($file['size'] > intval($allowedFileSize) && $allowedFileSize) {

                    $this->result->forProperty('fileUpload.' . $i)->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.size_exceed',
                                'rkw_consultant'
                            ), 1449314625
                        )
                    );
                    $isValid = false;
                }

                // "pathinfo($file['type'],PATHINFO_EXTENSION)" doesn't work because of unspecific uploading
                $fileExtension = explode('/', $file['type']);

                if (!in_array($fileExtension[1], $allowedFileTypes)) {

                    $this->result->forProperty('fileUpload.' . $i)->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.wrong_type',
                                'rkw_consultant'
                            ) . implode(', ', $allowedFileTypes), 471007432
                        )
                    );
                    $isValid = false;
                }
            }
        }


        // if no file was loaded ($i is 0 then), but one file or more is mandatory, then:
        $mandatoryFiles = $settingsDefault['mandatoryFields']['upload']['files']['numberOfMandatoryFiles'];
        if ($mandatoryFiles) {
            // for consultant edit: Add existing files to count
            if (($i + count($objectSource->getFile())) < $mandatoryFiles) {

                /*	<f:translate
                        key="error.{propertyError.code}"
                        arguments="{0:'{f:translate(key:\"form.error.{propertyName}\")}'}" >{propertyError.message}
                    </f:translate>
                */
                $this->result->forProperty('fileUpload')->addError(
                    new \TYPO3\CMS\Extbase\Error\Error(
                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                            'validator.files_mandatory',
                            'rkw_consultant'
                        ), 1449314642
                    )
                );
                $isValid = false;
            }
        }


        // images
        $allowedImageSize = $settingsDefault['mandatoryFields']['upload']['images']['allowedSize'];
        $allowedImageTypes = array_map('trim', explode(',', $settingsDefault['mandatoryFields']['upload']['images']['allowedTypes']));

        $i = 0;
        foreach ($objectSource->getImageUpload() as $image) {

            if ($image['error'] != 4) {

                $i++;

                // -> && if a fileSize is set (If not set: Every size is allowed)
                if ($image['size'] > intval($allowedImageSize) && $allowedImageSize) {

                    $this->result->forProperty('imageUpload.' . $i)->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.size_exceed',
                                'rkw_consultant'
                            ), 1449314649
                        )
                    );
                    $isValid = false;
                }

                // "pathinfo($file['type'],PATHINFO_EXTENSION)" doesn't work because of unspecific uploading
                $fileExtension = explode('/', $image['type']);
                if (!in_array($fileExtension[1], $allowedImageTypes)) {

                    $this->result->forProperty('imageUpload.' . $i)->addError(
                        new \TYPO3\CMS\Extbase\Error\Error(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'validator.wrong_type',
                                'rkw_consultant'
                            ) . implode(', ', $allowedImageTypes), 1449314657
                        )
                    );
                    $isValid = false;
                }
            }
        }

        // if no image was loaded ($i is 0 then), but one file or more is mandatory, then:
        $mandatoryImages = $settingsDefault['mandatoryFields']['upload']['images']['numberOfMandatoryImages'];
        if ($mandatoryImages) {
            // for consultant edit: Add existing images to count
            if (($i + count($objectSource->getImage())) < $mandatoryImages) {

                $this->result->forProperty('imageUpload')->addError(
                    new \TYPO3\CMS\Extbase\Error\Error(
                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                            'validator.images_mandatory',
                            'rkw_consultant'
                        ), 1449314667
                    )
                );
                $isValid = false;
            }
        }

        return $isValid;
        //===
    }

}

