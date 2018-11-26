<?php

namespace RKW\RkwConsultant\Controller;
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
 * Class AjaxController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * consultantRepository
     *
     * @var \RKW\RkwConsultant\Domain\Repository\ConsultantRepository
     * @inject
     */
    protected $consultantRepository = null;


    /**
     * filterAction
     *
     * @param string $location
     * @param int $page
     * @param bool $networkOnly
     * @return string
     */
    public function filterAction($location, $page, $networkOnly = false)
    {

        // 1. get filter data
        $location = filter_var($location, FILTER_SANITIZE_STRING);
        $page = intval($page);
        $networkOnly = boolval($networkOnly);

        // 2. get event list
        $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
        $consultantList = $this->consultantRepository->findByFilterOptions($listItemsPerView, intval($page), $networkOnly, $location);

        // 3. proof if we have further results (query with listItemsPerQuery + 1)
        $showMoreLink = count($consultantList->toArray()) > $listItemsPerView ? true : false;

        // 4. remove proof-element, if there are one more result than items per page needed
        if ($showMoreLink) {
            unset($consultantList[$listItemsPerView]);
        }


        // get JSON helper
        /** @var \RKW\RkwBasics\Helper\Json $jsonHelper */
        $jsonHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwBasics\\Helper\\Json');

        // get new list
        $replacements = array(
            'typeNum'        => intval($this->settings['pageTypeAjaxForConsultantList']),
            'pageDetailUid'  => intval($this->settings['pageDetailUid']),
            'pageMore'       => $page + 1,
            'showMoreLink'   => $showMoreLink,
            'consultantList' => $consultantList,
            'networkOnly'    => $networkOnly,
            'location'       => $location,
        );


        $jsonHelper->setHtml(
            ($page > 0 ? 'tx-rkwconsultant-grid-section' : 'tx-rkwconsultant-search-result-section'),
            $replacements,
            ($page > 0 ? 'append' : 'replace'),
            ($page > 0 ? 'Ajax/List/More.html' : 'Ajax/List/List.html')
        );

        print (string)$jsonHelper;
        exit();
        //===
    }


}

