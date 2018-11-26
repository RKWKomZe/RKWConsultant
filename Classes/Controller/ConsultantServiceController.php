<?php

namespace RKW\RkwConsultant\Controller;

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
 * Class ConsultantServiceController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantServiceController extends \RKW\RkwConsultant\Controller\AbstractController
{


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_CONSULTANTSERVICE_FOR_SEARCH = 'consultantServiceSearch';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_CONSULTANTSERVICE_FOR_VARNISH = 'consultantServiceVarnish';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_CREATING_CONSULTANTSERVICE = 'consultantServiceCreate';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_UPDATING_CONSULTANTSERVICE = 'consultantServiceUpdate';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_DELETING_CONSULTANTSERVICE = 'consultantServiceDelete';


    /**
     * backendUserRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\BackendUserRepository
     * @inject
     */
    protected $backendUserRepository = null;

    /**
     * basicServiceRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\BasicServiceRepository
     * @inject
     */
    protected $basicServiceRepository = null;

    /**
     * qualificationRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\QualificationRepository
     * @inject
     */
    protected $qualificationRepository = null;

    /**
     * subServiceRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\SubServiceRepository
     * @inject
     */
    protected $subServiceRepository = null;


    /**
     * action choose
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @ignorevalidation $consultant
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function chooseAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        $this->checkLogin();

        // get consultantServices which matches the usergroups of the consultant
        $basicServiceList = $this->basicServiceRepository->findAllDependingOnUsergroup($this->getFrontendUser());

        // filter list by basicServices, which are already used by consultant
        /** @var \RKW\RkwConsultant\Domain\Model\ConsultantService $basicService */
        $finalList = array();
        foreach ($basicServiceList as $basicService) {

            /** @var \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService */
            foreach ($consultant->getConsultantService() as $consultantService) {

                if ($basicService->getUid() == $consultantService->getBasicService()->getUid()) {
                    continue(2);
                }
                //===
            }
            $finalList[] = $basicService;
        }


        // if there is npothing left to select, redirect back to edit-mode of consultant!
        if (!$finalList) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultantservice.nothtingToChoose',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            $this->redirect('edit', 'Consultant', 'RkwConsultant', array('consultant' => $consultant), intval($this->settings['restrictedPageEditUid']));
            //===

        }

        $this->view->assignMultiple(array(
            'consultant'       => $consultant,
            'basicServiceList' => $finalList,
        ));
    }


    /**
     * action new
     *
     * @param \RKW\RkwConsultant\Domain\Model\BasicService $basicService
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @ignorevalidation $consultant
     * @ignorevalidation $consultantService
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function newAction(\RKW\RkwConsultant\Domain\Model\BasicService $basicService, \RKW\RkwConsultant\Domain\Model\Consultant $consultant, \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService = null)
    {
        $this->checkLogin();

        $this->view->assignMultiple(array(
            'basicService'      => $basicService,
            'consultantService' => $consultantService,
            'consultant'        => $consultant,
        ));
    }


    /**
     * action create
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param integer $privacy
     * @validate $consultantService \RKW\RkwConsultant\Validation\ConsultantServiceValidator
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function createAction(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService, \RKW\RkwConsultant\Domain\Model\Consultant $consultant, $privacy = null)
    {
        $this->checkLogin();
        $this->checkUserRights($consultant);

        /*
        if (!$privacy) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'registrationController.error.accept_privacy', 'rkw_registration'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            // @toDo: Find a solution for this workaround, which avoid following message:
            // // #1260881688: Could not serialize Domain Object RKW\RkwConsultant\Domain\Model\ConsultantService. It is neither an Entity with identity properties set, nor a Value Object
            if ($consultantService->_isNew()) {
                $this->consultantServiceRepository->add($consultantService);
            } else {
                $this->consultantServiceRepository->update($consultantService);
            }
            $this->persistenceManager->persistAll();
            $this->redirect('new', 'ConsultantService', NULL, array('basicService' => $consultantService->getBasicService(), 'consultant' => $consultantService->getConsultant(), 'consultantService' => $consultantService ), intval($this->settings['restrictedPageNewUid']));
            //$this->forward('new', 'ConsultantService');
            //===
        }
        */

        // add privacy info
        \RKW\RkwRegistration\Tools\Privacy::addPrivacyData($this->request, $this->getFrontendUser(), $consultantService, 'new consultant service');

        // get arguments for action-switch
        $arguments = $this->request->getArguments();

        // ### Start Workaround: Fill qualification and subService with data from qualificationArray and subServiceArray
        /** @var \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService */
        if (is_array($consultantService->getQualificationArray())) {

            foreach ($consultantService->getQualificationArray() as $qualificationUid) {
                if ($qualificationUid) {
                    $consultantService->addQualification($this->qualificationRepository->findByIdentifier(intval($qualificationUid)));
                }
            }
        }

        if (is_array($consultantService->getSubServiceArray())) {
            foreach ($consultantService->getSubServiceArray() as $subServiceUid) {
                if ($subServiceUid) {
                    $consultantService->addSubService($this->subServiceRepository->findByIdentifier(intval($subServiceUid)));
                }
            }
        }
        // ### End Workaround

        // REMOVE not used contactPersons
        /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
        foreach ($consultantService->getContactPerson()->toArray() as $contactPerson) {

            if ($contactPerson->getEmail() == '') {
                $consultantService->removeContactPerson($contactPerson);
            }
        }

        $this->consultantServiceRepository->add($consultantService);
        $this->persistenceManager->persistAll();

        /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
        $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');

        // save image
        foreach ($consultantService->getContactPerson() as $contactPerson) {
            /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
            foreach ($contactPerson->getImageUpload() as $file) {
                if ($file['name'] == "" || $file['name'] == " ") {
                    continue;
                }
                $miscHelper->createFileReference($file, 'image', $contactPerson);
            }
        }

        $consultant->addConsultantService($consultantService);
        $this->consultantRepository->update($consultant);
        $this->persistenceManager->persistAll();

        // add new consultantService to search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_SEARCH,
            array(
                $consultantService,
                'create',
                $consultant,
            )
        );

        // add new consultantService to varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );

        // Initial: Check for inconsistency in TypoScript configuration
        // If the profiles should unlocked by an admin, but there are no admin email addresses in typoscript
        if (!$this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::WARNING,
                sprintf('Consultant profiles should checked by admin(s). But no email address is defined in TypoScript.')
            );
        }

        // only if unlocking through admin is activated (and some admins are set) we send an email to the admin
        if ($this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            // Info: Every consultant profile at this point is deactivated (Model: disabled = TRUE)
            // But for updating the value es set here again
            $consultant->setDisabled(true);

            // Now create SHA1 token and send email to admin (for preview & unlocking)
            // create sha1
            /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
            $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');
            $consultant->setSha1($miscHelper->createSha1($consultant->getUid() . time()));
            $consultant->setSha1ValidUntil(time() + $this->settings['sha1ValidUntil']);

            $this->consultantRepository->update($consultant);
            $this->persistenceManager->persistAll();

            // send mails to admins
            $adminList = array();
            foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['consultant']['adminIdsForEmail']) as $adminUid) {
                $admin = $this->backendUserRepository->findByIdentifier($adminUid);
                if (
                    ($admin)
                    && ($admin->getEmail())
                ) {
                    $adminList[] = $admin;
                }
            }


            $this->getSignalSlotDispatcher()->dispatch(
                __CLASS__,
                self::SIGNAL_AFTER_CREATING_CONSULTANTSERVICE,
                array(
                    $adminList,
                    $consultant,
                    $consultant->getAdmin(),
                )
            );

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultantservice.disabled',
                    'rkw_consultant'
                )
            );
        }


        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultantservice.created',
                'rkw_consultant'
            )
        );

        // if user want to create an further consultantservice
        if ($arguments['further']) {
            $this->redirect(
                'choose', 'ConsultantService', null, array('consultant' => $consultant)
            );
        } else {
            // else complete creating / editing
            $this->redirect('myList', 'Consultant', null, array(), intval($this->settings['restrictedPageUid']));
        }
    }


    /**
     * action edit
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @ignorevalidation $consultantService
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function editAction(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService)
    {

        $this->checkLogin();
        $this->checkUserRights($consultantService->getConsultant());

        // fill consultantService Model with contactPersons, if there are less than 3
        if (count($consultantService->getContactPerson()) < 3) {

            $contactPersonsToAdd = 3 - count($consultantService->getContactPerson());

            for ($i = 0; $contactPersonsToAdd > $i; $i++) {

                /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
                $contactPerson = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Domain\\Model\\ContactPerson');
                $consultantService->addContactPerson($contactPerson);

            }
        }

        $this->view->assignMultiple(array(
            'consultantService' => $consultantService,
            'basicService'      => $consultantService->getBasicService(),
            'consultant'        => $consultantService->getConsultant(),
        ));

    }


    /**
     * action update
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @validate $consultantService \RKW\RkwConsultant\Validation\ConsultantServiceValidator
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function updateAction(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService)
    {

        $this->checkLogin();
        $this->checkUserRights($consultantService->getConsultant());

        /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
        $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');
        // save image
        foreach ($consultantService->getContactPerson() as $contactPerson) {

            /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
            foreach ($contactPerson->getImageUpload() as $file) {
                if ($file['name'] == "" || $file['name'] == " ") {
                    continue;
                }
                $miscHelper->createFileReference($file, 'image', $contactPerson);
            }
        }

        // ### Start Workaround: Fill qualification and subService with data from qualificationArray and subServiceArray

        /** @var \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService */

        // first remove, then re-add
        foreach ($consultantService->getQualification() as $qualification) {
            $consultantService->removeQualification($qualification);
        }

        if (is_array($consultantService->getQualificationArray())) {
            foreach ($consultantService->getQualificationArray() as $qualificationUid) {
                if ($qualificationUid) {
                    $consultantService->addQualification($this->qualificationRepository->findByIdentifier(intval($qualificationUid)));
                }
            }
        }

        // first remove, then re-add
        foreach ($consultantService->getSubService() as $subService) {
            $consultantService->removeSubService($subService);
        }

        if (is_array($consultantService->getSubServiceArray())) {
            foreach ($consultantService->getSubServiceArray() as $subServiceUid) {
                if ($subServiceUid) {
                    $consultantService->addSubService($this->subServiceRepository->findByIdentifier(intval($subServiceUid)));
                }
            }
        }

        // ### End Workaround

        // REMOVE not used contactPersons
        /** @var \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
        foreach ($consultantService->getContactPerson()->toArray() as $contactPerson) {

            // same examination like in ConsultantServiceValidator.php
            if (!trim($contactPerson->getEmail())) {
                $consultantService->removeContactPerson($contactPerson);
            }
        }

        $this->consultantServiceRepository->update($consultantService);

        // update consultantService in search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_SEARCH,
            array(
                $consultantService,
                'update',
                null,
            )
        );

        // update consultantService in varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );

        $this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
            'tx_rkwconsultant_controller_consultantservice.updated',
            'rkw_consultant'
        )
        );

        // Initial: Check for inconsistency in TypoScript configuration
        // If the profiles should unlocked by an admin, but there are no admin email addresses in typoscript
        if (!$this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::WARNING,
                sprintf('Consultant profiles should checked by admin(s). But no email address is defined in TypoScript.')
            );
        }

        // only if unlocking through admin is activated (and some admins are set) we send an email to the admin
        if ($this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            // Info: Every consultant profile at this point is deactivated (Model: disabled = TRUE)
            // But for updating the value es set here again
            $consultant = $consultantService->getConsultant();
            $consultant->setDisabled(true);

            // Now create SHA1 token and send email to admin (for preview & unlocking)
            // create sha1
            /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
            $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');
            $consultant->setSha1($miscHelper->createSha1($consultant->getUid() . time()));
            $consultant->setSha1ValidUntil(time() + $this->settings['sha1ValidUntil']);

            $this->consultantRepository->update($consultant);
            $this->persistenceManager->persistAll();

            // send mails to admins
            $adminList = array();
            foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['consultant']['adminIdsForEmail']) as $adminUid) {
                $admin = $this->backendUserRepository->findByIdentifier($adminUid);
                if (
                    ($admin)
                    && ($admin->getEmail())
                ) {
                    $adminList[] = $admin;
                }
            }


            $this->getSignalSlotDispatcher()->dispatch(
                __CLASS__,
                self::SIGNAL_AFTER_UPDATING_CONSULTANTSERVICE,
                array(
                    $adminList,
                    $consultant,
                    $consultant->getAdmin(),
                )
            );

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultantservice.disabled',
                    'rkw_consultant'
                )
            );
        }

        $this->redirect('myList', 'Consultant', null, array(), intval($this->settings['restrictedPageUid']));
    }


    /**
     * action delete
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function deleteAction(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService)
    {

        $this->checkLogin();
        $this->checkUserRights($consultantService->getConsultant());

        $consultant = $consultantService->getConsultant();

        $this->consultantServiceRepository->remove($consultantService);

        // remove consultantService from search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_SEARCH,
            array(
                $consultantService,
                'remove',
                null,
            )
        );

        // remove consultantService from varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANTSERVICE_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );

        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultantservice.deleted',
                'rkw_consultant',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            )
        );

        // Initial: Check for inconsistency in TypoScript configuration
        // If the profiles should unlocked by an admin, but there are no admin email addresses in typoscript
        if (!$this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::WARNING,
                sprintf('Consultant profiles should checked by admin(s). But no email address is defined in TypoScript.')
            );
        }

        // only if unlocking through admin is activated (and some admins are set) we send an email to the admin
        if ($this->settings['consultant']['adminIdsForEmail'] && $this->settings['consultant']['adminUnlocking']) {

            // Info: Every consultant profile at this point is deactivated (Model: disabled = TRUE)
            // But for updating the value es set here again
            $consultant = $consultantService->getConsultant();
            $consultant->setDisabled(true);

            // Now create SHA1 token and send email to admin (for preview & unlocking)
            // create sha1
            /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
            $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');
            $consultant->setSha1($miscHelper->createSha1($consultant->getUid() . time()));
            $consultant->setSha1ValidUntil(time() + $this->settings['sha1ValidUntil']);

            $this->consultantRepository->update($consultant);
            $this->persistenceManager->persistAll();

            // send mails to admins
            $adminList = array();
            foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['consultant']['adminIdsForEmail']) as $adminUid) {
                $admin = $this->backendUserRepository->findByIdentifier($adminUid);
                if (
                    ($admin)
                    && ($admin->getEmail())
                ) {
                    $adminList[] = $admin;
                }
            }


            $this->getSignalSlotDispatcher()->dispatch(
                __CLASS__,
                self::SIGNAL_AFTER_DELETING_CONSULTANTSERVICE,
                array(
                    $adminList,
                    $consultant,
                    $consultant->getAdmin(),
                )
            );

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultantservice.disabled',
                    'rkw_consultant'
                )
            );
        }

        $this->redirect('myList', 'Consultant', null, array(), intval($this->settings['restrictedPageUid']));
    }


    /**
     * action removeFile
     * Ajax
     *
     * @return boolean
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function removeFileAction()
    {

        // security check
        $this->checkLogin();

        // get arguments for action-switch
        $arguments = $this->request->getArguments();

        $fileUid = preg_replace('/[^0-9]/', '', $arguments['fileUid']);
        $contactPersonUid = preg_replace('/[^0-9]/', '', $arguments['contactPersonUid']);

        $contactPerson = $this->contactPersonRepository->findByIdentifier($contactPersonUid);

        if (!$contactPerson) {
            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::ERROR,
                sprintf('ContactPerson cannot be found.')
            );

            return false;
            //===
        }

        /** @var \RKW\RkwConsultant\Domain\Model\FileReference $fileToRemove */
        $fileToRemove = $this->fileReferenceRepository->findByIdentifier($fileUid);

        // additional check: Is fileUid part of contactPerson and a frontendUser logged in?
        if ($contactPerson->getImage()->contains($fileToRemove) && $this->getFrontendUser()) {

            $contactPerson->removeImage($fileToRemove);

            $this->contactPersonRepository->update($contactPerson);

            // Now: delete fileReference and file in database
            $this->fileReferenceRepository->remove($fileToRemove);

            // if there are no more entry in file reference for this file-id
            //$fileReference = $this->fileReferenceRepository->findByUidLocal($fileToRemove->getOriginalResource()->getOriginalFile()->getUid());

            // delete file directly from HDD!
            $fileToRemove->getOriginalResource()->getOriginalFile()->delete();


            $this->persistenceManager->persistAll();

            return json_encode(true);
            //===
        }

        return json_encode(false);
        //===

    }
}