<?php

namespace RKW\RkwConsultant\Domain\Model;

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
 * Class BasicService
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BasicService extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * shortDescription
     *
     * @var string
     */
    protected $shortDescription = '';

    /**
     * qualification
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\Qualification>
     */
    protected $qualification = null;

    /**
     * subService
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\SubService>
     */
    protected $subService = null;

    /**
     * usergroup
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>
     */
    protected $usergroup;

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->qualification = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->subService = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->usergroup = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->initStorageObjects();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the shortDescription
     *
     * @return string $shortDescription
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Sets the shortDescription
     *
     * @param string $shortDescription
     * @return void
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Adds a qualification to the service
     *
     * @param \RKW\RkwConsultant\Domain\Model\Qualification $qualification
     * @return void
     * @api
     */
    public function addQualification(\RKW\RkwConsultant\Domain\Model\Qualification $qualification)
    {
        $this->qualification->attach($qualification);
    }

    /**
     * Removes a qualification to the service
     *
     * @param \RKW\RkwConsultant\Domain\Model\Qualification $qualification
     * @return void
     * @api
     */
    public function removeQualification(\RKW\RkwConsultant\Domain\Model\Qualification $qualification)
    {
        $this->qualification->detach($qualification);
    }

    /**
     * Returns the qualification
     *
     * @return \RKW\RkwConsultant\Domain\Model\Qualification $qualification
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Sets the qualification
     *
     * @param \RKW\RkwConsultant\Domain\Model\Qualification $qualification
     * @return void
     */
    public function setQualification(\RKW\RkwConsultant\Domain\Model\Qualification $qualification)
    {
        $this->qualification = $qualification;
    }

    /**
     * Adds a subService to the service
     *
     * @param \RKW\RkwConsultant\Domain\Model\SubService subService
     * @return void
     * @api
     */
    public function addSubService(\RKW\RkwConsultant\Domain\Model\SubService $subService)
    {
        $this->subService->attach($subService);
    }

    /**
     * Removes a subService to the service
     *
     * @param \RKW\RkwConsultant\Domain\Model\SubService subService
     * @return void
     * @api
     */
    public function removeSubService(\RKW\RkwConsultant\Domain\Model\SubService $subService)
    {
        $this->subService->detach($subService);
    }

    /**
     * Returns the subService
     *
     * @return \RKW\RkwConsultant\Domain\Model\SubService $subService
     */
    public function getSubService()
    {
        return $this->subService;
    }

    /**
     * Sets the subService
     *
     * @param \RKW\RkwConsultant\Domain\Model\SubService $subService
     * @return \RKW\RkwConsultant\Domain\Model\SubService subService
     */
    public function setSubService(\RKW\RkwConsultant\Domain\Model\SubService $subService)
    {
        $this->subService = $subService;
    }

    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroup
     * @return void
     * @api
     */
    public function setUsergroup(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroup)
    {
        $this->usergroup = $usergroup;
    }

    /**
     * Adds a usergroup to the frontend user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup
     * @return void
     * @api
     */
    public function addUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup)
    {
        $this->usergroup->attach($usergroup);
    }

    /**
     * Removes a usergroup from the frontend user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup
     * @return void
     * @api
     */
    public function removeUsergroup(\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup)
    {
        $this->usergroup->detach($usergroup);
    }

    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the usergroup
     * @api
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
}