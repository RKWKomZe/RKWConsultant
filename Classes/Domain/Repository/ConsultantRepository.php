<?php

namespace RKW\RkwConsultant\Domain\Repository;
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
 * Class ConsultantRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Logger
     *
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;


    /**
     * findAll
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|NULL
     */
    public function findAll()
    {

        $query = $this->createQuery();

        $query->matching(
            $query->equals('disabled', 0)
        );

        return $query->execute();
        //====
    }


    /**
     * findAllByFrontendUser
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $frontendUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|NULL
     */
    public function findAllByFrontendUser(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $frontendUser)
    {

        $query = $this->createQuery();

        $query->matching(
            $query->logicalOr(
                $query->equals('admin', $frontendUser->getUid()),
                $query->contains('subeditor', $frontendUser->getUid())
            )
        );

        return $query->execute();
        //===
    }


    /**
     * countProfilesWhereFrontendUserIsAdmin
     * !! includes hidden profiles !!
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return integer
     */
    public function countProfilesWhereFrontendUserIsAdmin(\RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->equals('admin', $frontendUser->getUid())
        );

        return count($query->execute());
        //====
    }


    /**
     * findBySha1
     *
     * @param string $sha1
     * @return \RKW\RkwConsultant\Domain\Model\Consultant
     */
    public function findBySha1($sha1)
    {

        $query = $this->createQuery();

        $query->matching(
            $query->equals('sha1', $sha1)
        );

        return $query->execute()->getFirst();
        //====
    }


    /**
     * findVisibleByFrontendUser
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findVisibleByFrontendUser(\RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->equals('admin', $frontendUser->getUid()),
                    $query->contains('subeditor', $frontendUser->getUid())
                ),
                $query->equals('disabled', 0)
            )
        );

        return $query->execute();
        //===
    }


    /**
     * findHiddenByFrontendUser
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findHiddenByFrontendUser(\RKW\RkwConsultant\Domain\Model\FrontendUser $frontendUser)
    {

        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->equals('admin', $frontendUser->getUid()),
                    $query->contains('subeditor', $frontendUser->getUid())
                ),
                $query->equals('disabled', 1)
            )
        );

        return $query->execute();
        //===
    }


    /**
     * findByAdmin
     * Used by delete Signal Slot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $feUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|null
     */
    public function findByAdmin($feUser)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching(
            $query->equals('admin', $feUser)
        );

        return $query->execute();
        //====
    }


    /**
     * findByFilterOptions
     *
     * @param int $limit
     * @param int $page
     * @param bool $networkOnly
     * @param mixed $address
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|NULL
     */
    public function findByFilterOptions($limit, $page, $networkOnly, $address = null)
    {

        // limit with additional proof item to check, if there are more results -> +1
        $offset = $page * $limit;
        $limit++;

        $query = $this->createQuery();

        if ($address) {

            /** @var \RKW\RkwGeolocation\Service\Geolocation $geoLocation */
            $geoLocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');
            $geoLocation->setAddress(filter_var($address, FILTER_SANITIZE_STRING));
            $geoLocation->setCountry('Germany');
            $geoData = null;
            $andWhere = 'AND disabled = 0 AND rkw_network = ' . intval($networkOnly);

            try {
                $geoLocation->getQueryStatementDistanceSearch($query, 'tx_rkwconsultant_domain_model_consultant', $limit, $offset, $andWhere);
            } catch (\Exception $e) {
                // api request failed
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('RkwGeolocation call failed in EventRepository: %s.', $e->getMessage()));
            }


        } else {

            // Sort by company name
            $query->setOrderings(
                array(
                    'company' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
                )
            );

            // If there is no address: Keep it simple :)
            $query->matching(
                $query->logicalAnd(
                    $query->equals('disabled', 0),
                    $query->equals('rkwNetwork', intval($networkOnly))
                )
            );

            $query->setOffset($offset);
            $query->setLimit($limit);

        }

        $result = $query->execute();


        return $result;
        //====
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

}