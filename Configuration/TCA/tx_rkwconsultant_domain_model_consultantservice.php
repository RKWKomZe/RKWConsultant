<?php
return [
	'ctrl' => [
		'hideTable' => 1,
		'title'	=> 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice',
		'label' => 'further_informations',
		'label_alt' => 'further_informations',
		'label_alt_force' => 1,
		'default_sortby' => 'ORDER BY uid, further_informations',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'searchFields' => 'further_informations,basic_service,qualification,sub_service,contact_person,',
		'iconfile' => 'EXT:rkw_consultant/Resources/Public/Icons/tx_rkwconsultant_domain_model_consultantservice.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'basic_service, further_informations, qualification, sub_service, contact_person',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, basic_service, further_informations, qualification, sub_service, contact_person, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access'],
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
				'default' => 0,
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
				'foreign_table' => 'tx_rkwconsultant_domain_model_consultantservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_consultantservice.pid=###CURRENT_PID### AND tx_rkwconsultant_domain_model_consultantservice.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		'further_informations' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.further_informations',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			],
		],
		'basic_service' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.basic_service',
			'config' => [
				'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 1,
				'maxitems' => 1,
				'foreign_table' => 'tx_rkwconsultant_domain_model_basicservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_basicservice.deleted = 0 AND tx_rkwconsultant_domain_model_basicservice.hidden = 0',
			],
		],
		'qualification' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.qualification',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 99,
				'foreign_table' => 'tx_rkwconsultant_domain_model_qualification',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_qualification.deleted = 0 AND tx_rkwconsultant_domain_model_qualification.hidden = 0 ORDER BY tx_rkwconsultant_domain_model_qualification.title ASC',
			],
		],
		'sub_service' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.sub_service',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 99,
				'foreign_table' => 'tx_rkwconsultant_domain_model_subservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_subservice.deleted = 0 AND tx_rkwconsultant_domain_model_subservice.hidden = 0 ORDER BY tx_rkwconsultant_domain_model_subservice.title ASC',
			],
		],

		'contact_person' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.contact_person',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 3,
				'foreign_table' => 'tx_rkwconsultant_domain_model_contactperson',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_contactperson.deleted = 0 AND tx_rkwconsultant_domain_model_contactperson.hidden = 0 ORDER BY tx_rkwconsultant_domain_model_contactperson.last_name ASC',
			],
		],

        /*
        'contact_person' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultantservice.contact_person',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_rkwconsultant_domain_model_consultantservice',
                'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_contactperson.deleted = 0 AND tx_rkwconsultant_domain_model_contactperson.hidden = 0',
                'foreign_field' => 'consultant_service',
                'maxitems'      => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
		*/
		'consultant' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		/*
		'consultant' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 1,
				'foreign_table' => 'tx_rkwconsultant_domain_model_consultant',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_consultant.deleted = 0 AND tx_rkwconsultant_domain_model_consultant.hidden = 0 ORDER BY tx_rkwconsultant_domain_model_consultant.company ASC',
			],
		],
		*/
	],
];
