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
 * Class FrontendUserRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FrontendUserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{


    /**
     * findAllByFrontendUserGroup
     *
     * @param array $groupIdList
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findAllByFrontendGroup($groupIdList = array())
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        if (count($groupIdList)) {
            $constraints = array();
            foreach ($groupIdList as $groupId) {
                $constraints[] = $query->contains('usergroup', $groupId);
            }

            $query->matching(
                $query->logicalOr($constraints)
            );
        }

        $query->setOrderings(array('lastName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));

        return $query->execute();
        //===
    }

}
