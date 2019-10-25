<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultant',
	'RKW Consultant'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultantmyconsultant',
	'RKW Consultant - MyList'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultantmaps',
	'RKW Consultant - GoogleMaps'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultantgallery',
	'RKW Consultant - Gallery'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultantinfo',
	'RKW Consultant - Info'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Rkwconsultantcompany',
	'RKW Consultant - Company'
);

//=================================================================
// Add tables
//=================================================================
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_basicservice', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_basicservice.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_basicservice');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_consultant', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_consultant.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_consultant');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_consultantservice', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_consultantservice.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_consultantservice');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_contactperson', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_contactperson.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_contactperson');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_qualification', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_qualification.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_qualification');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwconsultant_domain_model_subservice', 'EXT:rkw_consultant/Resources/Private/Language/locallang_csh_tx_rkwconsultant_domain_model_subservice.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwconsultant_domain_model_subservice');
//=================================================================
// General stuff
//=================================================================
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'RKW Consultant');


//=================================================================
// Add Flexform
//=================================================================
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$pluginName = strtolower('Rkwconsultantmyconsultant');
$pluginSignature = $extensionName.'_'.$pluginName;

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/MyConsultant.xml');


$pluginName = strtolower('Rkwconsultant');
$pluginSignature = $extensionName.'_'.$pluginName;

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/Consultant.xml');

