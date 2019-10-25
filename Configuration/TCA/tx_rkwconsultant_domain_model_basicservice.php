<?php
return [
	'ctrl' => [
		'title'	=> 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'searchFields' => 'title,short_description,qualification,sub_service,usergroup,',
		'iconfile' => 'EXT:rkw_consultant/Resources/Public/Icons/tx_rkwconsultant_domain_model_basicservice.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, short_description, qualification, sub_service, usergroup',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,--palette--;;1, title, short_description, qualification, sub_service, usergroup, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [
	
		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
					['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0],
				],
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_rkwconsultant_domain_model_basicservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_basicservice.pid=###CURRENT_PID### AND tx_rkwconsultant_domain_model_basicservice.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'title' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'short_description' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice.short_description',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			],
		],
		'qualification' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice.qualification',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'eval' => 'int',
				'minitems' => 0,
				'maxitems' => 999,
				'foreign_table' => 'tx_rkwconsultant_domain_model_qualification',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_qualification.deleted = 0 AND tx_rkwconsultant_domain_model_qualification.hidden = 0',
			],


		],
		'sub_service' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice.sub_service',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'eval' => 'int',
				'minitems' => 0,
				'maxitems' => 999,
				'foreign_table' => 'tx_rkwconsultant_domain_model_subservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_subservice.deleted = 0 AND tx_rkwconsultant_domain_model_subservice.hidden = 0',
			],
		],
		'usergroup' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_basicservice.usergroup',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => 4,
				'eval' => 'int',
				'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUserGroup',
				'foreign_table' => 'fe_groups',
				'foreign_table_where' => 'AND fe_groups.hidden = 0',
			],
		],
		
	],
];
