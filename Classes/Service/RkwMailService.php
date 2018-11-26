<?php

namespace RKW\RkwConsultant\Service;

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use \RKW\RkwConsultant\Helper\Misc;

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
 * RkwMailService
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RkwMailService implements \TYPO3\CMS\Core\SingletonInterface
{


    /**
     * Send mail to admin when an consultant profile is created (for unlocking)
     *
     * @param \RKW\RkwConsultant\Domain\Model\BackendUser|array $backendUser
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function sendAdminApproveMail($backendUser, \RKW\RkwConsultant\Domain\Model\Consultant $consultant, \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        // get settings
        $settings = Misc::getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = Misc::getSettings();

        $recipients = array();
        if (is_array($backendUser)) {
            $recipients = $backendUser;
        } else {
            $recipients[] = $backendUser;
        }

        if ($settings['view']['templateRootPath']) {

            /** @var \RKW\RkwMailer\Service\MailService $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

            // informations about the consultant
            $marker = array(
                'pageUid'      => $settingsDefault['pageUid'],
                'frontendUser' => $frontendUser,
                'consultant'   => $consultant,
            );

            foreach ($recipients as $recipient) {

                if (
                    ($recipient instanceof \RKW\RkwConsultant\Domain\Model\BackendUser)
                    && ($recipient->getEmail())
                ) {

                    $mailService->setTo($recipient,
                        array(
                            'marker'  => array_merge(
                                array('backendUser' => $recipient),
                                $marker
                            ),
                            'subject' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'rkwMailService.adminApproveMail.subject',
                                'rkw_consultant',
                                null,
                                $recipient->getLang()
                            ),
                        )
                    );
                }
            }


            $mailService->getQueueMail()->setReplyAddress($frontendUser->getEmail());
            $mailService->getQueueMail()->setSubject(
                \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                    'rkwMailService.adminApproveMail.subject',
                    'rkw_consultant',
                    null,
                    'de'
                )
            );

            $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPath'] . 'Email/AdminApproveMail');
            $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPath'] . 'Email/AdminApproveMail');
            $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPath']);

            if (count($mailService->getTo())) {
                $mailService->send();
            }
        }

    }


    /**
     * Send mail to admin when an consultant profile is deleted (for unlocking)
     *
     * @param \RKW\RkwConsultant\Domain\Model\BackendUser|array $backendUser
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function sendAdminDeleteMail($backendUser, \RKW\RkwConsultant\Domain\Model\Consultant $consultant, \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        $recipients = array();
        if (is_array($backendUser)) {
            $recipients = $backendUser;
        } else {
            $recipients[] = $backendUser;
        }

        // get settings
        $settings = Misc::getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = Misc::getSettings();
        if ($settings['view']['templateRootPath']) {

            /** @var \RKW\RkwMailer\Service\MailService $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

            // informations about the consultant
            $marker = array(
                'pageUid'      => $settingsDefault['pageUid'],
                'frontendUser' => $frontendUser,
                'consultant'   => $consultant,
            );

            foreach ($recipients as $recipient) {

                if (
                    ($recipient instanceof \RKW\RkwConsultant\Domain\Model\BackendUser)
                    && ($recipient->getEmail())
                ) {
                    $mailService->setTo($recipient,
                        array(
                            'marker'  => array_merge(
                                array('backendUser' => $recipient),
                                $marker
                            ),
                            'subject' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'rkwMailService.adminDeleteMail.deleted',
                                'rkw_consultant',
                                null,
                                $recipient->getLang()
                            ),
                        )
                    );
                }
            }

            $mailService->getQueueMail()->setReplyAddress($frontendUser->getEmail());
            $mailService->getQueueMail()->setSubject(
                \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                    'rkwMailService.adminDeleteMail.deleted',
                    'rkw_consultant',
                    null,
                    'de'
                )
            );

            $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPath'] . 'Email/AdminDeleteMail');
            $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPath'] . 'Email/AdminDeleteMail');
            $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPath']);

            if (count($mailService->getTo())) {
                $mailService->send();
            }
        }

    }


    /**
     * Send mail to user
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function sendUserDeleteMail(\RKW\RkwConsultant\Domain\Model\Consultant $consultant, \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        // get settings
        $settings = Misc::getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = Misc::getSettings();
        if ($settings['view']['templateRootPath']) {

            /** @var \RKW\RkwMailer\Service\MailService $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

            // informations about the consultant
            $marker = array(
                'pageUid'      => $settingsDefault['pageUid'],
                'frontendUser' => $frontendUser,
                'consultant'   => $consultant,
            );

            $mailService->setTo($frontendUser,
                array(
                    'marker' => $marker,
                )
            );

            $mailService->getQueueMail()->setSubject(
                \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                    'rkwMailService.userDeleteMail.deleted',
                    'rkw_consultant',
                    null,
                    $frontendUser->getTxRkwregistrationLanguageKey()
                )
            );

            $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPath'] . 'Email/UserDeleteMail');
            $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPath'] . 'Email/UserDeleteMail');
            $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPath']);

            $mailService->send();
        }

    }


    /**
     * Send mail to user (when a consultant profile is unlocked by an admin)
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function sendUserUnlockMail(\RKW\RkwConsultant\Domain\Model\Consultant $consultant, \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        // get settings
        $settings = Misc::getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = Misc::getSettings();
        if ($settings['view']['templateRootPath']) {

            /** @var \RKW\RkwMailer\Service\MailService $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

            // informations about the consultant
            $marker = array(
                'pageUid'      => $settingsDefault['pageUid'],
                'frontendUser' => $frontendUser,
                'consultant'   => $consultant,
            );

            $mailService->setTo($frontendUser,
                array(
                    'marker' => $marker,
                )
            );

            $mailService->getQueueMail()->setSubject(
                \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                    'rkwMailService.userUnlockMail.unlock',
                    'rkw_consultant',
                    null,
                    $frontendUser->getTxRkwregistrationLanguageKey()
                )
            );

            $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPath'] . 'Email/UserUnlockMail');
            $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPath'] . 'Email/UserUnlockMail');
            $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPath']);

            $mailService->send();
        }

    }


}
