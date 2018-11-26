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
 * Class ConsultantController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantController extends \RKW\RkwConsultant\Controller\AbstractController
{

    /**
     * backendUserRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\BackendUserRepository
     * @inject
     */
    protected $backendUserRepository = null;


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_EDITING_CONSULTANT = 'sendAdminApproveMail';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_UNLOCKING_CONSULTANT_USER = 'sendUserUnlockMail';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_DELETE_CONSULTANT_ADMIN = 'sendMailDeleteConsultant';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_DELETE_CONSULTANT_USER = 'sendMailDeletedToUser';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_CONSULTANT_FOR_SEARCH = 'consultantSearch';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_CONSULTANT_FOR_VARNISH = 'consultantVarnish';


    /**
     * consultantUid
     *
     * @var integer consultantUid
     */
    protected $consultantUid = null;


    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {

        // 1. get list
        $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
        $networkOnly = boolval($this->settings['networkOnly']) ? boolval($this->settings['networkOnly']) : false;
        $consultantList = $this->consultantRepository->findByFilterOptions($listItemsPerView, 0, $networkOnly);

        // 2. proof if we have further results (query with listItemsPerQuery + 2; one before and one after!)
        $showMoreLink = count($consultantList) > $listItemsPerView ? true : false;

        // remove proof-element, if there are one more result than items per page needed
        if ($showMoreLink) {
            unset($consultantList[$listItemsPerView]);
        }


        $this->view->assignMultiple(array(
            'consultantList' => $consultantList,
            'pageDetailUid'  => $this->settings['pageDetailUid'],
            'page'           => 0,
            'pageMore'       => 1,
            'showMoreLink'   => $showMoreLink,
            'networkOnly'    => $networkOnly,
            'typeNum'        => intval($this->settings['pageTypeAjaxForConsultantList']),
        ));
    }


    /**
     * action myList
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function myListAction()
    {

        // !! change: Everybody (who is logged in) can see now "myList". If he hadn't a consultant, he can create one here
        $this->checkLogin();

        /*
        if (!$this->consultantRepository->isAdminOrSubeditor($this->getFrontendUser())) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.rights_problem',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );
            $this->redirect('list');
        }
        */

        // get profiles where FrontendUser is Admin or subeditor
        $consultants = null;
        if ($this->getFrontendUser()) {
            $consultants = $this->consultantRepository->findAllByFrontendUser($this->getFrontendUser());
        }

        $this->view->assignMultiple(array(
            'consultants' => $consultants,
            'editPageUid' => intval($this->settings['restrictedPageEditUid']),
            'newPageUid'  => intval($this->settings['restrictedPageNewUid']),
        ));
    }


    /**
     * action show
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return void
     */
    public function showAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        if ($consultant->isDisabled()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.disabled',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );
        }

        $this->view->assignMultiple(array(
            'consultant'           => $consultant,
            'hideDetails'          => $consultant->isDisabled(),
            'rightsOfFrontendUser' => $this->getRightsOfFrontendUser($consultant),
        ));
    }

    /**
     * action new
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @ignorevalidation $consultant
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function newAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant = null)
    {
        $this->checkLogin();

        // if in ts maxProfilesUser is set, than get consultant profile of which the user is admin
        // -> if equal to maximum (maxProfilesUser), than block creating new profile
        $userMayCreateFurtherProfiles = true;
        if (isset($this->settings['consultant']['maxProfilesUser'])) {
            $userMayCreateFurtherProfiles = false;
            $numberOfProfiles = $this->consultantRepository->countProfilesWhereFrontendUserIsAdmin($this->getFrontendUser());

            if ($numberOfProfiles < intval($this->settings['consultant']['maxProfilesUser'])) {
                // if the limit isn't reached yet -> TRUE
                $userMayCreateFurtherProfiles = true;
            }
        }

        $this->view->assignMultiple(array(
            'consultant'                   => $consultant,
            'frontendUser'                 => $this->getFrontendUser(),
            'userMayCreateFurtherProfiles' => $userMayCreateFurtherProfiles,
        ));
    }


    /**
     * action create
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @validate $consultant \RKW\RkwConsultant\Validation\ConsultantValidator
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function createAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        $this->checkLogin();

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
            // // #1260881688: Could not serialize Domain Object RKW\RkwConsultant\Domain\Model\Consultant. It is neither an Entity with identity properties set, nor a Value Object
            /*
            if ($consultant->_isNew()) {
                $this->consultantRepository->add($consultant);
            } else {
                $this->consultantRepository->update($consultant);
            }
            $this->persistenceManager->persistAll();
            $this->redirect('new', NULL, NULL, array('consultant' => $consultant), intval($this->settings['restrictedPageNewUid']));
            */
        //		$this->forward('new');
        //===
        //	}


        // add privacy info
        \RKW\RkwRegistration\Tools\Privacy::addPrivacyData($this->request, $this->getFrontendUser(), $consultant, 'new consultant');

        // set admin role to frontendUser
        /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = $this->getFrontendUser();

        // set user als admin of new profile
        /** @var \RKW\RkwConsultant\Domain\Model\Consultant $consultant */
        $consultant->setAdmin($frontendUser);

        // Initial: Check for inconsistency in TypoScript configuration
        // If the profiles should unlocked by an admin, but there are no admin email addresses in typoscript
        if (
            (!$this->settings['consultant']['adminIdsForEmail'])
            && ($this->settings['consultant']['adminUnlocking'])
        ) {

            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::WARNING,
                sprintf('Consultant profiles should checked by admin(s). But no email address is defined in TypoScript.')
            );
        }

        if (
            ($this->settings['consultant']['adminIdsForEmail'])
            && ($this->settings['consultant']['adminUnlocking'])
        ) {

            // Info: Every consultant profile at this point is deactivated (Model: disabled = TRUE)
            // But for updating the value es set here again
            $consultant->setDisabled(true);
        }


        $this->consultantRepository->add($consultant);
        $this->persistenceManager->persistAll();

        /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
        $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');

        // save file(s)
        foreach ($consultant->getFileUpload() as $file) {

            if ($file['name'] == "" || $file['name'] == " ") {
                continue;
                //===
            }

            $miscHelper->createFileReference($file, 'file', $consultant);
        }

        // save image(s)
        foreach ($consultant->getImageUpload() as $file) {

            if ($file['name'] == "" || $file['name'] == " ") {
                continue;
                //===
            }

            $miscHelper->createFileReference($file, 'image', $consultant);
        }

        // get longitude and latitude
        if ($consultant->getZip() || $consultant->getAddress()) {
            $miscHelper->determineGeoDataOfConsultant($consultant);
        }

        $this->consultantRepository->update($consultant);

        // add new consultant to search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_SEARCH,
            array(
                $consultant,
                'create',
            )
        );

        // add new consultant to varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );


        /*
        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultant.created',
                'rkw_consultant'
            )
        );
        */

        // create now services of new consultant
        $this->redirect(
            'choose', 'ConsultantService', null, array('consultant' => $consultant)
        );
    }


    /**
     * action edit
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @ignorevalidation $consultant
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function editAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        $this->checkLogin();
        $this->checkUserRights($consultant);


        // else give consultant to view for edit
        $this->view->assign('consultant', $consultant);
    }


    /**
     * action update
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @validate $consultant \RKW\RkwConsultant\Validation\ConsultantValidator
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function updateAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        $this->checkLogin();
        $this->checkUserRights($consultant);

        /** @var \RKW\RkwConsultant\Helper\Misc $miscHelper */
        $miscHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwConsultant\\Helper\\Misc');

        // save file(s)
        foreach ($consultant->getFileUpload() as $file) {

            if ($file['name'] == "" || $file['name'] == " ") {
                continue;
            }

            $miscHelper->createFileReference($file, 'file', $consultant);
        }

        // save image(s)
        foreach ($consultant->getImageUpload() as $file) {

            if ($file['name'] == "" || $file['name'] == " ") {
                continue;
            }

            $miscHelper->createFileReference($file, 'image', $consultant);
        }

        // renew longitude and latitude
        if ($consultant->getZip() || $consultant->getAddress()) {
            $miscHelper->determineGeoDataOfConsultant($consultant);
        }

        $this->consultantRepository->update($consultant);

        // update consultant in search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_SEARCH,
            array(
                $consultant,
                'update',
            )
        );

        // update consultant in varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );

        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultant.updated',
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
                self::SIGNAL_AFTER_EDITING_CONSULTANT,
                array(
                    $adminList,
                    $consultant,
                    $consultant->getAdmin(),
                )
            );

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.disabled',
                    'rkw_consultant'
                )
            );
        }
        $this->redirect('myList', 'Consultant');
    }


    /**
     * action preview
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return void
     */
    public function previewAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant = null)
    {

        if (!$consultant) {
            // get arguments for action-switch
            $arguments = $this->request->getArguments();

            $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $arguments['user']);

            //@todo ggf entfernen
            // !! wird wohl garnicht aufgerufen, wenn der token fehlt:
            // !! "Reason: Request parameters could not be validated (&cHash comparison failed)"
            // !! Anweisung durch Typo3 internes Sicherungssystem ggf unerreichbar
            if (!$arguments['user']) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'tx_rkwconsultant_controller_consultant.token_not_set',
                        'rkw_consultant',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    )
                );

                return;
            }

            // get consultant of token
            $consultant = $this->consultantRepository->findBySha1($consultantSha1);

            // proof token valid time
            if ($consultant->getSha1ValidUntil() < time()) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'tx_rkwconsultant_controller_consultant.token_expired',
                        'rkw_consultant',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    )
                );

                return;
            }

            // proof remote addr
            $remoteAddr = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
            if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
                $ips = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                if ($ips[0]) {
                    $remoteAddr = filter_var($ips[0], FILTER_VALIDATE_IP);
                }
            }

            if ($remoteAddr != $this->settings['allowedRemoteAddr']) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'tx_rkwconsultant_controller_consultant.remote_addr_wrong',
                        'rkw_consultant',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    )
                );

                return;
                //===
            }

        }

        $this->view->assignMultiple(array(
            'consultant' => $consultant,
        ));
    }


    /**
     * action enable
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function enableAction()
    {

        // get arguments for action-switch
        $arguments = $this->request->getArguments();
        $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $arguments['user']);

        if (!$arguments['user']) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.token_not_set',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            return;
            //===
        }


        $consultant = $this->consultantRepository->findBySha1($consultantSha1);
        // proof for object
        if (!$consultant) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.token_invalid',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            return;
            //===
        }

        // proof token valid time
        if ($consultant->getSha1ValidUntil() < time()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.token_expired',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            return;
            //===
        }

        // proof remote addr
        $remoteAddr = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ips = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ips[0]) {
                $remoteAddr = filter_var($ips[0], FILTER_VALIDATE_IP);
            }
        }

        if ($remoteAddr != $this->settings['allowedRemoteAddr']) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.remote_addr_wrong',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            return;
            //===
        }

        // if profile is already unlocked
        if (!$consultant->isDisabled()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.already_unlocked',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );

            $this->redirect(
                'show', 'Consultant', null, array('consultant' => $consultant)
            );
            //===
        }

        // unlock consultant
        $consultant->setDisabled(false);
        $this->consultantRepository->update($consultant);

        //send mail to owner of consultant profile (frontendUser)
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_AFTER_UNLOCKING_CONSULTANT_USER,
            array(
                $consultant,
                $consultant->getAdmin(),
            )
        );

        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultant.enabled',
                'rkw_consultant',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            )
        );

        $this->redirect(
            'show', 'Consultant', null, array('consultant' => $consultant)
        );
    }


    /**
     * action prepareDelete
     * builds ajax request for jquery featherlight
     */
    public function prepareDeleteAction()
    {

        // get arguments for action-switch
        $arguments = $this->request->getArguments();

        $consultantUid = preg_replace('/[^0-9]/', '', $arguments['consultant']);
        $consultantServiceUid = preg_replace('/[^0-9]/', '', $arguments['consultantservice']);
        $consultantUidOfImage = preg_replace('/[^0-9]/', '', $arguments['consultantimage']);
        $consultantUidOfFile = preg_replace('/[^0-9]/', '', $arguments['consultantfile']);
        $contactPersonUid = preg_replace('/[^0-9]/', '', $arguments['contactperson']);

        // delete consultant
        if ($consultantUid) {

            $consultant = $this->consultantRepository->findByIdentifier($consultantUid);

            if (!$consultant) {
                $this->getLogger()->log(
                    \TYPO3\CMS\Core\Log\LogLevel::INFO,
                    sprintf('Consultant cannot be found.')
                );

                return false;
                //===
            }

            $this->view->assignMultiple(array(
                'consultant' => $consultant,
                'type'       => 'consultant',
            ));
        }


        // delete consultantService
        if ($consultantServiceUid) {

            $consultantService = $this->consultantServiceRepository->findByIdentifier($consultantServiceUid);

            if (!$consultantService) {
                $this->getLogger()->log(
                    \TYPO3\CMS\Core\Log\LogLevel::INFO,
                    sprintf('Consultant cannot be found.')
                );

                return false;
                //===
            }

            $this->view->assignMultiple(array(
                'consultantService' => $consultantService,
                'type'              => 'service',
            ));
        }


        // delete image of consultant
        if ($consultantUidOfImage) {

            $fileUid = preg_replace('/[^0-9]/', '', $arguments['fileuid']);
            $imageNumber = preg_replace('/[^0-9]/', '', $arguments['imagenumber']);

            $consultant = $this->consultantRepository->findByIdentifier($consultantUidOfImage);

            if (!$consultant) {
                $this->getLogger()->log(
                    \TYPO3\CMS\Core\Log\LogLevel::INFO,
                    sprintf('Consultant cannot be found.')
                );

                return false;
                //===
            }

            $this->view->assignMultiple(array(
                'consultant'  => $consultant,
                'fileUid'     => $fileUid,
                'imageNumber' => $imageNumber,
                'type'        => 'image',
            ));
        }


        // delete file of consultant
        if ($consultantUidOfFile) {

            $fileUid = preg_replace('/[^0-9]/', '', $arguments['fileuid']);
            $fileNumber = preg_replace('/[^0-9]/', '', $arguments['filenumber']);

            $consultant = $this->consultantRepository->findByIdentifier($consultantUidOfFile);

            if (!$consultant) {
                $this->getLogger()->log(
                    \TYPO3\CMS\Core\Log\LogLevel::INFO,
                    sprintf('Consultant cannot be found.')
                );

                return false;
                //===
            }

            $this->view->assignMultiple(array(
                'consultant' => $consultant,
                'fileUid'    => $fileUid,
                'fileNumber' => $fileNumber,
                'type'       => 'file',
            ));
        }


        // delete file of consultant
        if ($contactPersonUid) {

            $fileUid = preg_replace('/[^0-9]/', '', $arguments['fileuid']);
            $imageNumber = preg_replace('/[^0-9]/', '', $arguments['imagenumber']);

            $contactPerson = $this->contactPersonRepository->findByIdentifier($contactPersonUid);

            if (!$contactPerson) {
                $this->getLogger()->log(
                    \TYPO3\CMS\Core\Log\LogLevel::INFO,
                    sprintf('ContactPerson cannot be found.')
                );

                return false;
                //===
            }

            $this->view->assignMultiple(array(
                'contactPerson' => $contactPerson,
                'fileUid'       => $fileUid,
                'imageNumber'   => $imageNumber,
                'type'          => 'contactperson',
            ));
        }


    }


    /**
     * action delete
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function deleteAction(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {

        $this->checkLogin();
        $this->checkUserRights($consultant, true);

        /** @var  \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService */
        foreach ($consultant->getConsultantService()->toArray() as $consultantService) {

            /** @var  \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
            foreach ($consultantService->getContactPerson()->toArray() as $contactPerson)
                $this->contactPersonRepository->remove($contactPerson);

            $this->consultantServiceRepository->remove($consultantService);
        }

        $this->consultantRepository->remove($consultant);

        // remove consultant from search
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_SEARCH,
            array(
                $consultant,
                'remove',
            )
        );

        // remove consultant from varnish cache
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_CONSULTANT_FOR_VARNISH,
            array(
                intval($this->settings['pageListUid']),
            )
        );

        // begin: send mails to admins
        $adminList = array();
        foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->settings['consultant']['adminIdsForEmail']) as $adminUid) {
            $admin = $this->backendUserRepository->findByIdentifier(trim($adminUid));
            if (
                ($admin)
                && ($admin->getEmail())
            ) {
                $adminList[] = $admin;
            }
        }

        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_AFTER_DELETE_CONSULTANT_ADMIN,
            array(
                $adminList,
                $consultant,
                $this->getFrontendUser(),
            )
        );

        //send mail to owner of consultant profile (frontendUser)
        $this->getSignalSlotDispatcher()->dispatch(
            __CLASS__,
            self::SIGNAL_AFTER_DELETE_CONSULTANT_USER,
            array(
                $consultant,
                $consultant->getAdmin(),
            )
        );

        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'tx_rkwconsultant_controller_consultant.deleted',
                'rkw_consultant',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            )
        );

        $this->redirect('myList', 'Consultant', 'RkwConsultant', array(), $this->settings['restrictedPageUid']);
    }


    /**
     * Deletes all profiles of an FE-User
     * Used via Signal Slot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $feUser
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function removeAllOfUserSignalSlot(\RKW\RkwRegistration\Domain\Model\FrontendUser $feUser)
    {
        $consultants = $this->consultantRepository->findByAdmin($feUser);

        if ($consultants) {

            /** @var  \RKW\RkwConsultant\Domain\Model\Consultant $consultant */
            foreach ($consultants as $consultant) {

                /** @var  \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService */
                foreach ($consultant->getConsultantService()->toArray() as $consultantService) {

                    /** @var  \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson */
                    foreach ($consultantService->getContactPerson()->toArray() as $contactPerson)
                        $this->contactPersonRepository->remove($contactPerson);

                    $this->consultantServiceRepository->remove($consultantService);
                }

                $this->consultantRepository->remove($consultant);

                /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

                /** @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager */
                $persistenceManager = $objectManager->get('TYPO3\\CMS\Extbase\\Persistence\\Generic\\PersistenceManager');
                $persistenceManager->persistAll();

                // remove consultant from search
                $this->getSignalSlotDispatcher()->dispatch(
                    __CLASS__,
                    self::SIGNAL_CONSULTANT_FOR_SEARCH,
                    array(
                        $consultant,
                        'remove',
                    )
                );

                // remove consultant from varnish cache
                $this->getSignalSlotDispatcher()->dispatch(
                    __CLASS__,
                    self::SIGNAL_CONSULTANT_FOR_VARNISH,
                    array(
                        intval($this->settings['pageListUid']),
                    )
                );

                // begin: send mails to admins
                $adminList = array();
                $settings = $this->getSettings();
                foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $settings['consultant']['adminIdsForEmail']) as $adminUid) {
                    $admin = $this->backendUserRepository->findByIdentifier(trim($adminUid));
                    if (
                        ($admin)
                        && ($admin->getEmail())
                    ) {
                        $adminList[] = $admin;
                    }
                }

                $this->getSignalSlotDispatcher()->dispatch(
                    __CLASS__,
                    self::SIGNAL_AFTER_DELETE_CONSULTANT_ADMIN,
                    array(
                        $adminList,
                        $consultant,
                        $this->getFrontendUser(),
                    )
                );

                // send mail to owner of consultant profile (frontendUser)
                $this->getSignalSlotDispatcher()->dispatch(
                    __CLASS__,
                    self::SIGNAL_AFTER_DELETE_CONSULTANT_USER,
                    array(
                        $consultant,
                        $consultant->getAdmin(),
                    )
                );
            }
        }
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
        // get arguments for action-switch
        $arguments = $this->request->getArguments();

        $fileUid = preg_replace('/[^0-9]/', '', $arguments['fileUid']);
        $consultantUid = preg_replace('/[^0-9]/', '', $arguments['consultantUid']);

        /** @var \RKW\RkwConsultant\Domain\Model\Consultant $consultant */
        $consultant = $this->consultantRepository->findByIdentifier($consultantUid);

        if (!$consultant) {
            $this->getLogger()->log(
                \TYPO3\CMS\Core\Log\LogLevel::INFO,
                sprintf('Consultant cannot be found.')
            );

            return false;
            //===
        }

        // security check
        $this->checkLogin();
        $this->checkUserRights($consultant);

        /** @var \RKW\RkwConsultant\Domain\Model\FileReference $fileToRemove */
        $fileToRemove = $this->fileReferenceRepository->findByIdentifier($fileUid);

        // additional check: Is fileUid part of consultant? (as file or image)
        if ($consultant->getFile()->contains($fileToRemove) || $consultant->getImage()->contains($fileToRemove)) {

            if ($fileToRemove->getFieldname() == 'file') {
                $consultant->removeFile($fileToRemove);
            } else {
                $consultant->removeImage($fileToRemove);
            }

            $this->consultantRepository->update($consultant);

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


    /**
     * action mapsAction
     *
     * @return void
     */
    public function mapsAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwconsultant_rkwconsultant');

        if (!$getParams['consultant']) {
            // FOR PREVIEW
            $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $getParams['user']);
            // get consultant of token
            $consultant = $this->consultantRepository->findBySha1($consultantSha1);
        } else {
            /** @var \RKW\RkwConsultant\Domain\Model\Consultant $consultant */
            $consultant = $this->consultantRepository->findByIdentifier(filter_var($getParams['consultant'], FILTER_SANITIZE_NUMBER_INT));
        }


        $this->view->assignMultiple(array(
            'consultant' => $consultant,
        ));

    }


    /**
     * action galleryAction
     *
     * @return void
     */
    public function galleryAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwconsultant_rkwconsultant');

        if (!$getParams['consultant']) {
            // FOR PREVIEW
            $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $getParams['user']);
            // get consultant of token
            $consultant = $this->consultantRepository->findBySha1($consultantSha1);
        } else {
            $consultant = $this->consultantRepository->findByIdentifier(filter_var($getParams['consultant'], FILTER_SANITIZE_NUMBER_INT));
        }

        $this->view->assignMultiple(array(
            'consultant' => $consultant,
        ));

    }


    /**
     * action infoAction
     *
     * @return void
     */
    public function infoAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwconsultant_rkwconsultant');

        if (!$getParams['consultant']) {
            // FOR PREVIEW
            $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $getParams['user']);
            // get consultant of token
            $consultant = $this->consultantRepository->findBySha1($consultantSha1);
        } else {
            $consultant = $this->consultantRepository->findByIdentifier(filter_var($getParams['consultant'], FILTER_SANITIZE_NUMBER_INT));
        }

        $this->view->assignMultiple(array(
            'consultant' => $consultant,
        ));
    }


    /**
     * action companyAction
     * returns company name in view
     *
     * @return void
     */
    public function companyAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwconsultant_rkwconsultant');

        if (!$getParams['consultant']) {
            // FOR PREVIEW
            $consultantSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $getParams['user']);
            // get consultant of token
            $consultant = $this->consultantRepository->findBySha1($consultantSha1);
        } else {
            $consultant = $this->consultantRepository->findByIdentifier(filter_var($getParams['consultant'], FILTER_SANITIZE_NUMBER_INT));
        }

        $this->view->assignMultiple(array(
            'consultant' => $consultant,
        ));
    }
}