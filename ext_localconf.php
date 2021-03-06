<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        //=================================================================
        // Configure Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultant',
            array(
                'Consultant' => 'list, show, preview, error, disable, enable',
                'Ajax' => 'filter',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'list, show, preview, error, disable, enable',
                'Ajax' => 'filter',
            )
        );


        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultantmyconsultant',
            array(
                'Consultant' => 'myList, show, new, create, edit, update, prepareDelete, delete, error, removeFile, maps',
                'ConsultantService' => 'new, create, edit, update, delete, choose, removeFile',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'myList, show, new, create, edit, update, prepareDelete, delete, error, removeFile, maps',
                'ConsultantService' => 'new, create, edit, update, delete, choose, removeFile',
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultantmaps',
            array(
                'Consultant' => 'maps',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'maps',
            )
        );


        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultantgallery',
            array(
                'Consultant' => 'gallery',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'gallery',
            )
        );


        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultantinfo',
            array(
                'Consultant' => 'info',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'info',
            )
        );


        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Rkwconsultantcompany',
            array(
                'Consultant' => 'company',
            ),
            // non-cacheable actions
            array(
                'Consultant' => 'company',
            )
        );


        //=================================================================
        // Configure Signal-Slots
        //=================================================================
        /**
         * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
         */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantController',
            \RKW\RkwConsultant\Controller\ConsultantController::SIGNAL_AFTER_EDITING_CONSULTANT,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendAdminApproveMail'
        );
        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantServiceController',
            \RKW\RkwConsultant\Controller\ConsultantServiceController::SIGNAL_AFTER_CREATING_CONSULTANTSERVICE,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendAdminApproveMail'
        );
        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantServiceController',
            \RKW\RkwConsultant\Controller\ConsultantServiceController::SIGNAL_AFTER_UPDATING_CONSULTANTSERVICE,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendAdminApproveMail'
        );
        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantServiceController',
            \RKW\RkwConsultant\Controller\ConsultantServiceController::SIGNAL_AFTER_DELETING_CONSULTANTSERVICE,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendAdminApproveMail'
        );

        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantController',
            \RKW\RkwConsultant\Controller\ConsultantController::SIGNAL_AFTER_DELETE_CONSULTANT_ADMIN,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendAdminDeleteMail'
        );

        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantController',
            \RKW\RkwConsultant\Controller\ConsultantController::SIGNAL_AFTER_DELETE_CONSULTANT_USER,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendUserDeleteMail'
        );

        $signalSlotDispatcher->connect(
            'RKW\\RkwConsultant\\Controller\\ConsultantController',
            \RKW\RkwConsultant\Controller\ConsultantController::SIGNAL_AFTER_UNLOCKING_CONSULTANT_USER,
            'RKW\\RkwConsultant\\Service\\RkwMailService',
            'sendUserUnlockMail'
        );
        $signalSlotDispatcher->connect(
            'RKW\\RkwRegistration\\Tools\\Registration',
            \RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_DELETING_USER,
            'RKW\\RkwConsultant\\Controller\\ConsultantController',
            'removeAllOfUserSignalSlot'
        );

        // Signal Slot for varnish-extension
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('varnish')) {

            $signalSlotDispatcher->connect(
                'RKW\\RkwConsultant\\Controller\\ConsultantController',
                \RKW\RkwConsultant\Controller\ConsultantController::SIGNAL_CONSULTANT_FOR_VARNISH,
                'RKW\\RkwConsultant\\Service\\VarnishService',
                'clearCacheOfPageEvent'
            );

            $signalSlotDispatcher->connect(
                'RKW\\RkwConsultant\\Controller\\ConsultantServiceController',
                \RKW\RkwConsultant\Controller\ConsultantServiceController::SIGNAL_CONSULTANTSERVICE_FOR_VARNISH,
                'RKW\\RkwConsultant\\Service\\VarnishService',
                'clearCacheOfPageEvent'
            );
        }

        //=================================================================
        // Configure Hook
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey] = 'RKW\\RkwConsultant\\Hooks\\TceMainHooks';


        //=================================================================
        // Configure Logger
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['RKW']['RkwConsultant']['writerConfiguration'] = array(

            // configuration for WARNING severity, including all
            // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
            \TYPO3\CMS\Core\Log\LogLevel::DEBUG => array(

                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
                    // configuration for the writer
                    'logFile' => 'typo3temp/logs/tx_rkwconsultant.log'
                )
            ),
        );

    },
    $_EXTKEY
);


