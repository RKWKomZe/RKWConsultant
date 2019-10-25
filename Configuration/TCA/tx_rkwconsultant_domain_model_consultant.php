<?php
return [
	'ctrl' => [
		'title'	=> 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant',
		'label' => 'company',
		'label_alt' => 'last_name',
		'label_alt_force' => 0,
		'default_sortby' => 'ORDER BY company',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'searchFields' => 'salutation,title,first_name,last_name,company,address,zip,city,state,telephone,fax,email,www,facebook,twitter,google_plus,rkw_network,longitude,latitude,short_description,reference,file,image,consultant_service,admin,subeditor,sha1,sha1_valid_until,disabled',
		'iconfile' => 'EXT:rkw_consultant/Resources/Public/Icons/tx_rkwconsultant_domain_model_consultant.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, salutation, title, first_name, last_name, company, address, zip, city, state, telephone, fax, email, www, facebook, twitter, google_plus, longitude, latitude, short_description, reference, file, image, consultant_service, admin, subeditor, rkw_network, disabled',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,--palette--;;1, disabled, rkw_network, company, address, zip, city, telephone, fax, email, www, facebook, twitter, google_plus, short_description, reference, file, image, consultant_service, admin, subeditor, longitude, latitude, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access'],
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
				'foreign_table' => 'tx_rkwconsultant_domain_model_consultant',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_consultant.pid=###CURRENT_PID### AND tx_rkwconsultant_domain_model_consultant.sys_language_uid IN (-1,0)',
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
		'salutation' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.salutation',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.general_choice', 0],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.salutation.I.female', '1'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.salutation.I.male', '99'],
				],
				'size' => 1,
				'maxitems' => 1,
			],
		],
		'title' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.title',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.general_choice', 0],
					['Dr.', '1'],
					['Prof.', '2'],
				],
				'size' => 1,
				'maxitems' => 1,
			],
		],
		'first_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.first_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'last_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.last_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'company' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.company',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'address' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.address',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'zip' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.zip',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
        // only needed for SQL-query with distance calculation!
        'distance' => [
            'type' => 'passthrough'
        ],

		'city' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.city',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'state' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.general_choice', 0],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state1', '1'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state2', '2'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state3', '3'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state4', '4'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state5', '5'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state6', '6'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state7', '7'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state8', '8'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state9', '9'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state10', '10'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state11', '11'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state12', '12'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state13', '13'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state14', '14'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state15', '15'],
					['LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.state.I.state16', '16'],
				],
				'size' => 1,
				'maxitems' => 1,
			],
		],
		'telephone' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.telephone',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'fax' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.fax',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'email' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.email',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'www' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.www',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        ],
                    ],
                ],
                'softref' => 'typolink'
			],
		],
		'facebook' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.facebook',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        ],
                    ],
                ],
                'softref' => 'typolink'
			],
		],
		'twitter' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.twitter',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        ],
                    ],
                ],
                'softref' => 'typolink'
			],
		],
		'google_plus' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.google_plus',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        ],
                    ],
                ],
                'softref' => 'typolink'
			],
		],
		'short_description' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.short_description',
			'config' => [
				'type' => 'text',
				'rows' => 10,
				'eval' => 'trim'
			],
		],
		'reference' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.reference',
			'config' => [
				'type' => 'text',
				'rows' => 10,
				'eval' => 'trim'
			],
		],
		'file' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.file',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'file',
				['maxitems' => 5],
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
		],
		'image' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.image',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'image',
				['maxitems' => 1],
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
		],
		/*
		'consultant_service' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.consultant_service',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 99,
				'foreign_table' => 'tx_rkwconsultant_domain_model_consultantservice',
				'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_consultantservice.deleted = 0 AND tx_rkwconsultant_domain_model_consultantservice.hidden = 0',
			],
		],
		*/
		'consultant_service' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.consultant_service',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_rkwconsultant_domain_model_consultantservice',
                'foreign_table_where' => 'AND tx_rkwconsultant_domain_model_consultantservice.deleted = 0 AND tx_rkwconsultant_domain_model_consultantservice.hidden = 0',
                'foreign_field' => 'consultant',
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
		'admin' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.admin',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 8,
                'minitems' => 0,
                'maxitems' => 1,
                'itemsProcFunc' => 'RKW\RkwConsultant\Helper\Tca->getFeUsers',
            ],
		],
		'subeditor' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.subeditor',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 8,
				'minitems' => 0,
				'maxitems' => 99,
                'itemsProcFunc' => 'RKW\RkwConsultant\Helper\Tca->getFeUsers',
			],
		],

		'longitude' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.longitude',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'readOnly' => TRUE
			],
		],
		'latitude' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.latitude',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'readOnly' => TRUE
			],
		],
		'sha1' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.sha1',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'sha1_valid_until' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.sha1_valid_until',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'rkw_network' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.rkw_network',
			'config' => [
				'type' => 'check',
				'default' => 0,
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.rkw_network.I.enable'
					],
				],
			],
		],
		'disabled' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.disabled',
			'config' => [
				'type' => 'check',
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:rkw_consultant/Resources/Private/Language/locallang_db.xlf:tx_rkwconsultant_domain_model_consultant.disabled.I.disable'
					],
				],
			],
		],
	],
];
