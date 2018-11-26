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
 * Class ConsultantService
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ConsultantService extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * furtherInformations
     *
     * @var string
     */
    protected $furtherInformations = '';

    /**
     * basicService
     *
     * @var \RKW\RkwConsultant\Domain\Model\BasicService
     */
    protected $basicService = null;

    /**
     * qualification
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\Qualification>
     */
    protected $qualification = null;

    /**
     * qualificationArray
     * Workaround
     *
     * @var array
     */
    protected $qualificationArray = null;

    /**
     * subService
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\SubService>
     */
    protected $subService = null;

    /**
     * subServiceArray
     * Workaround
     *
     * @var array
     */
    protected $subServiceArray = null;

    /**
     * contactPerson
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\ContactPerson>
     */
    protected $contactPerson = null;

    /**
     * consultant
     *
     * @var \RKW\RkwConsultant\Domain\Model\Consultant
     */
    protected $consultant = null;

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
        $this->contactPerson = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->initStorageObjects();
    }

    /**
     * Returns the furtherInformations
     *
     * @return string $furtherInformations
     */
    public function getFurtherInformations()
    {
        return $this->furtherInformations;
    }

    /**
     * Sets the furtherInformations
     *
     * @param string $furtherInformations
     * @return void
     */
    public function setFurtherInformations($furtherInformations)
    {
        $this->furtherInformations = $furtherInformations;
    }

    /**
     * Returns the basicService
     *
     * @return \RKW\RkwConsultant\Domain\Model\BasicService $basicService
     */
    public function getBasicService()
    {
        return $this->basicService;
    }

    /**
     * Sets the basicService
     *
     * @param \RKW\RkwConsultant\Domain\Model\BasicService $basicService
     * @return void
     */
    public function setBasicService(\RKW\RkwConsultant\Domain\Model\BasicService $basicService)
    {
        $this->basicService = $basicService;
    }

    /**
     * Adds a qualification to the consultantService
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
     * Removes a qualification to the consultantService
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\Qualification> $qualification
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Sets the qualification
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $qualification
     * @return void
     */
    public function setQualification(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $qualification)
    {
        $this->qualification = $qualification;
    }

    /**
     * Returns the qualificationArray
     *
     * @return array $qualificationArray
     */
    public function getQualificationArray()
    {
        return $this->qualificationArray;
    }

    /**
     * Sets the qualificationArray
     *
     * @param array $qualificationArray
     * @return void
     */
    public function setQualificationArray($qualificationArray)
    {
        $this->qualificationArray = $qualificationArray;
    }

    /**
     * Adds a contactPerson to the consultantService
     *
     * @param \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson
     * @return void
     * @api
     */
    public function addContactPerson(\RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson)
    {
        $this->contactPerson->attach($contactPerson);
    }

    /**
     * Removes a contactPerson to the consultantService
     *
     * @param \RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson
     * @return void
     * @api
     */
    public function removeContactPerson(\RKW\RkwConsultant\Domain\Model\ContactPerson $contactPerson)
    {
        $this->contactPerson->detach($contactPerson);
    }

    /**
     * Returns the contactPerson
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $contactPerson
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * Sets the contactPerson
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $contactPerson
     * @return void
     */
    public function setContactPerson(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    /**
     * Adds a subService to the consultantService
     *
     * @param \RKW\RkwConsultant\Domain\Model\SubService $subService
     * @return void
     * @api
     */
    public function addSubService(\RKW\RkwConsultant\Domain\Model\SubService $subService)
    {
        $this->subService->attach($subService);
    }

    /**
     * Removes a subService to the consultantService
     *
     * @param \RKW\RkwConsultant\Domain\Model\SubService $subService
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\SubService> $subService
     */
    public function getSubService()
    {
        return $this->subService;
    }

    /**
     * Sets the subService
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $subService
     */
    public function setSubService(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $subService)
    {
        $this->subService = $subService;
    }

    /**
     * Returns the subServiceArray
     *
     * @return array $subServiceArray
     */
    public function getSubServiceArray()
    {
        return $this->subServiceArray;
    }

    /**
     * Sets the subServiceArray
     *
     * @param array $subServiceArray
     * @return void
     */
    public function setSubServiceArray($subServiceArray)
    {
        $this->subServiceArray = $subServiceArray;
    }

    /**
     * consultant
     *
     * @param \RKW\RkwConsultant\Domain\Model\Consultant $consultant
     * @return void
     */
    public function setConsultant(\RKW\RkwConsultant\Domain\Model\Consultant $consultant)
    {
        $this->consultant = $consultant;
    }

    /**
     * admin
     *
     * @return \RKW\RkwConsultant\Domain\Model\Consultant
     */
    public function getConsultant()
    {
        return $this->consultant;
    }

}