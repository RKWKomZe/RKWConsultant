<?php

namespace RKW\RkwConsultant\Controller;

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use \RKW\RkwBasics\Helper\Common;

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
 * Class AbstractController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Constants for permissions of FE-User Admins
     *
     * @const integer
     */
    const PERMISSION_FRONTEND_ADMIN = 77;

    /**
     * Constants for permissions of FE-User Authors
     *
     * @const integer
     */
    const PERMISSION_FRONTEND_AUTHOR = 1;

    /**
     * Constants for permissions of all other FE-Users
     *
     * @const integer
     */
    const PERMISSION_FRONTEND_ALL = 0;

    /**
     * consultantRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\ConsultantRepository
     * @inject
     */
    protected $consultantRepository = null;

    /**
     * consultantServiceRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\ConsultantServiceRepository
     * @inject
     */
    protected $consultantServiceRepository = null;

    /**
     * frontendUserRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository = null;

    /**
     * fileRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\FileRepository
     * @inject
     */
    protected $fileRepository = null;

    /**
     * fileReferenceRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\FileReferenceRepository
     * @inject
     */
    protected $fileReferenceRepository = null;

    /**
     * contactPersonRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\ContactPersonRepository
     * @inject
     */
    protected $contactPersonRepository = null;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * SignalSlotDispatcher
     *
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     * @inject
     */
    protected $signalSlotDispatcher;

    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;

    /**
     * logged FrontendUser
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;

    /**
     * rightsOfFrontendUser
     *
     * @var integer rightsOfFrontendUser
     */
    protected $rightsOfFrontendUser = null;


    /**
     * Remove ErrorFlashMessage
     *
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::getErrorFlashMessage()
     */
    protected function getErrorFlashMessage()
    {
        return false;
        //===
    }


    /**
     * Id of logged User
     *
     * @return integer
     */
    protected function getFrontendUserId()
    {

        // is $GLOBALS set?
        if (
            ($GLOBALS['TSFE'])
            && ($GLOBALS['TSFE']->loginUser)
            && ($GLOBALS['TSFE']->fe_user->user['uid'])
        ) {
            return intval($GLOBALS['TSFE']->fe_user->user['uid']);
            //===
        }

        return false;
        //===
    }


    /**
     * Returns current logged in user object
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser|NULL
     */
    protected function getFrontendUser()
    {

        $this->frontendUser = $this->frontendUserRepository->findByIdentifier($this->getFrontendUserId());

        if ($this->frontendUser instanceof \TYPO3\CMS\Extbase\Domain\Model\FrontendUser) {
            return $this->frontendUser;
        }

        //===

        return null;
        //===
    }


    /**
     * checkLogin
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function checkLogin()
    {

        if (!$this->getFrontendUser()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.pleaselogin',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );
            $this->redirect('list', 'Consultant');
        }
    }

    /**
     * checkUserRights
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param boolean $onlyAdmin
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function checkUserRights(\RKW\RkwConsultant\Domain\Model\Consultant $consultant, $onlyAdmin = false)
    {

        // have to be an admin
        if ($onlyAdmin) {
            if ($this->getRightsOfFrontendUser($consultant) != self::PERMISSION_FRONTEND_ADMIN) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'tx_rkwconsultant_controller_consultant.rights_problem_admin',
                        'rkw_consultant',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    )
                );
                $this->redirect('myList', 'Consultant');
            }
        }

        // admin or subeditor
        if (!$this->getRightsOfFrontendUser($consultant)) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'tx_rkwconsultant_controller_consultant.rights_problem',
                    'rkw_consultant',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                )
            );
            $this->redirect('myList', 'Consultant');
        }
    }

    /**
     * getRightsOfFrontendUser
     * determine the edit rights of FrontendUser for consultant profile
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return integer $this->rightsOfFrontendUser
     */
    protected function getRightsOfFrontendUser(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {


        // subeditor = 1, admin = 77
        if ($this->rightsOfFrontendUser === null) {

            $this->rightsOfFrontendUser = self::PERMISSION_FRONTEND_ALL;

            if ($this->getFrontendUser()) {

                if ($this->getFrontendUser() == $consultant->getAdmin()) {
                    $this->rightsOfFrontendUser = self::PERMISSION_FRONTEND_ADMIN;
                } else {
                    foreach ($consultant->getSubeditor()->toArray() as $subeditor) {
                        if ($this->getFrontendUser() == $subeditor) {
                            $this->rightsOfFrontendUser = self::PERMISSION_FRONTEND_AUTHOR;
                        }
                    }
                }
            }
        }

        return $this->rightsOfFrontendUser;
        //===
    }


    /**
     * Returns SignalSlotDispatcher
     *
     * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {

        if (!$this->signalSlotDispatcher) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
            $this->signalSlotDispatcher = $objectManager->get('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        }

        return $this->signalSlotDispatcher;
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


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {

        return Common::getTyposcriptConfiguration('Rkwconsultant', $which);
        //===
    }


}