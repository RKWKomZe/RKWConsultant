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
 * Class ContactPerson
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContactPerson extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * salutation
     *
     * @var integer
     */
    protected $salutation = 0;

    /**
     * title
     *
     * @var integer
     */
    protected $title = 0;

    /**
     * firstName
     *
     * @var string
     */
    protected $firstName = '';

    /**
     * lastName
     *
     * @var string
     */
    protected $lastName = '';

    /**
     * telephone
     *
     * @var string
     */
    protected $telephone = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference>
     */
    protected $image = '';

    /**
     * imageUpload
     * Array store for form image upload
     *
     * @var array
     */
    protected $imageUpload = array();

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
        $this->image = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->initStorageObjects();
    }

    /**
     * Returns the salutation
     *
     * @return integer $salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     *
     * @param integer $salutation
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the title
     *
     * @return integer $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param integer $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Returns the telephone
     *
     * @return string $telephone
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Sets the telephone
     *
     * @param string $telephone
     * @return void
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference> image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param string $image
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference> image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Adds a image
     *
     * @param \RKW\RkwConsultant\Domain\Model\FileReference $image
     * @return void
     */
    public function addImage(\RKW\RkwConsultant\Domain\Model\FileReference $image)
    {
        $this->image->attach($image);
    }

    /**
     * Removes a image
     *
     * @param \RKW\RkwConsultant\Domain\Model\FileReference $image
     * @return void
     * @api
     */
    public function removeImage(\RKW\RkwConsultant\Domain\Model\FileReference $image)
    {
        $this->image->detach($image);
    }

    /**
     * Returns the imageUpload
     * ### only for form upload ###
     *
     * @return array $imageUpload
     */
    public function getImageUpload()
    {
        return $this->imageUpload;
    }

    /**
     * Sets the imageUpload
     * ### only for form upload ###
     *
     * @param array $imageUpload
     * @return void
     */
    public function setImageUpload($imageUpload)
    {
        $this->imageUpload = $imageUpload;
    }

}