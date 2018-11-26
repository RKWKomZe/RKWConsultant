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
 * Class Consultant
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwConsultant
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Consultant extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * company
     *
     * @var string
     */
    protected $company = '';

    /**
     * address
     *
     * @var string
     */
    protected $address = '';

    /**
     * zip
     *
     * @var string
     */
    protected $zip = '';

    /**
     * city
     *
     * @var string
     */
    protected $city = '';

    /**
     * state
     *
     * @var integer
     */
    protected $state = '';

    /**
     * telephone
     *
     * @var string
     */
    protected $telephone = '';

    /**
     * fax
     *
     * @var string
     */
    protected $fax = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * www
     *
     * @var string
     */
    protected $www = '';

    /**
     * facebook
     *
     * @var string
     */
    protected $facebook = '';

    /**
     * twitter
     *
     * @var string
     */
    protected $twitter = '';

    /**
     * googlePlus
     *
     * @var string
     */
    protected $googlePlus = '';

    /**
     * rkwNetwork
     *
     * @var boolean
     */
    protected $rkwNetwork = false;

    /**
     * longitude
     *
     * @var string
     */
    protected $longitude = '';

    /**
     * latitude
     *
     * @var string
     */
    protected $latitude = '';

    /**
     * shortDescription
     *
     * @var string
     */
    protected $shortDescription = '';

    /**
     * reference
     *
     * @var string
     */
    protected $reference = '';

    /**
     * file
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference>
     */
    protected $file = '';

    /**
     * fileUpload
     * Array store for form file upload
     *
     * @var array
     */
    protected $fileUpload = array();

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
     * consultantService
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\ConsultantService>
     */
    protected $consultantService = null;

    /**
     * admin
     *
     * @var \RKW\RkwConsultant\Domain\Model\FrontendUser
     */
    protected $admin = null;

    /**
     * subeditor
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FrontendUser>
     */
    protected $subeditor = null;

    /**
     * hidden
     *
     * @var boolean
     */
    protected $hidden = false;

    /**
     * disabled
     *
     * @var boolean
     */
    protected $disabled = false;

    /**
     * sha1
     *
     * @var string
     */
    protected $sha1 = '';

    /**
     * sha1ValidUntil
     *
     * @var string
     */
    protected $sha1ValidUntil = '';

    /**
     * distance
     *
     * @var string
     */
    protected $distance;

    /**
     * privacy
     * ! Hint: Just a workaround, this is a non-persistent property !
     *
     * @var string
     */
    protected $privacy;


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
        $this->subeditor = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->consultantService = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->file = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param string $company
     * @return void
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the state
     *
     * @return integer $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the state
     *
     * @param integer $state
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
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
     * Returns the fax
     *
     * @return string $fax
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets the fax
     *
     * @param string $fax
     * @return void
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
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
     * Returns the www
     *
     * @return string $www
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Sets the www
     *
     * @param string $www
     * @return void
     */
    public function setWww($www)
    {
        $this->www = $www;
    }

    /**
     * Returns the facebook
     *
     * @return string $facebook
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Sets the facebook
     *
     * @param string $facebook
     * @return void
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * Returns the twitter
     *
     * @return string $twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Sets the twitter
     *
     * @param string $twitter
     * @return void
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Returns the googlePlus
     *
     * @return string $googlePlus
     */
    public function getGooglePlus()
    {
        return $this->googlePlus;
    }

    /**
     * Sets the googlePlus
     *
     * @param string $googlePlus
     * @return void
     */
    public function setGooglePlus($googlePlus)
    {
        $this->googlePlus = $googlePlus;
    }

    /**
     * Returns the rkwNetwork
     *
     * @return boolean $rkwNetwork
     */
    public function getRkwNetwork()
    {
        return $this->rkwNetwork;
    }

    /**
     * Sets the rkwNetwork
     *
     * @param boolean $rkwNetwork
     * @return void
     */
    public function setRkwNetwork($rkwNetwork)
    {
        $this->rkwNetwork = $rkwNetwork;
    }

    /**
     * Returns the boolean state of rkwNetwork
     *
     * @return boolean
     */
    public function isRkwNetwork()
    {
        return $this->rkwNetwork;
    }

    /**
     * Returns the longitude
     *
     * @return string $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @param string $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude
     *
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
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
     * Returns the reference
     *
     * @return string $reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Sets the reference
     *
     * @param string $reference
     * @return void
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Returns the file
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference> file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file
     *
     * @param string $file
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwConsultant\Domain\Model\FileReference> file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Adds a file
     *
     * @param \RKW\RkwConsultant\Domain\Model\FileReference $file
     * @return void
     */
    public function addFile(\RKW\RkwConsultant\Domain\Model\FileReference $file)
    {
        $this->file->attach($file);
    }

    /**
     * Removes a file
     *
     * @param \RKW\RkwConsultant\Domain\Model\FileReference $file
     * @return void
     * @api
     */
    public function removeFile(\RKW\RkwConsultant\Domain\Model\FileReference $file)
    {
        $this->file->detach($file);
    }

    /**
     * Returns the fileUpload
     * ### only for form upload ###
     *
     * @return array $fileUpload
     */
    public function getFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * Sets the fileUpload
     * ### only for form upload ###
     *
     * @param array $fileUpload
     * @return void
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload;
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

    /**
     * Adds a consultantService to the consultant
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @return void
     * @api
     */
    public function addConsultantService(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService)
    {
        $this->consultantService->attach($consultantService);
    }

    /**
     * Removes a consultantService to the consultant
     *
     * @param \RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService
     * @return void
     * @api
     */
    public function removeConsultantService(\RKW\RkwConsultant\Domain\Model\ConsultantService $consultantService)
    {
        $this->consultantService->detach($consultantService);
    }

    /**
     * Returns the consultantService
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $consultantService
     */
    public function getConsultantService()
    {
        return $this->consultantService;
    }

    /**
     * Sets the consultantService
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $consultantService
     * @return void
     */
    public function setConsultantService(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $consultantService)
    {
        $this->consultantService = $consultantService;
    }

    /**
     * admin
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $admin
     * @return void
     */
    public function setAdmin(\RKW\RkwConsultant\Domain\Model\FrontendUser $admin)
    {
        $this->admin = $admin;
    }

    /**
     * admin
     *
     * @return \RKW\RkwConsultant\Domain\Model\FrontendUser
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Adds a subeditor to the consultant profile
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $subeditor
     * @return void
     * @api
     */
    public function addSubeditor(\RKW\RkwConsultant\Domain\Model\FrontendUser $subeditor)
    {
        $this->subeditor->attach($subeditor);
    }

    /**
     * Removes a subeditor from the consultant profile
     *
     * @param \RKW\RkwConsultant\Domain\Model\FrontendUser $subeditor
     * @return void
     * @api
     */
    public function removeSubeditor(\RKW\RkwConsultant\Domain\Model\FrontendUser $subeditor)
    {
        $this->subeditor->detach($subeditor);
    }

    /**
     * Returns the subeditor. Keep in mind that the property is called "subeditor"
     * although it can hold several subeditor.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the subeditor
     * @api
     */
    public function getSubeditor()
    {
        return $this->subeditor;
    }

    /**
     * Sets the subeditor. Keep in mind that the property is called "subeditor"
     * although it can hold several subeditor.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $subeditor
     * @return void
     * @api
     */
    public function setSubeditor(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $subeditor)
    {
        $this->subeditor = $subeditor;
    }

    /**
     * Returns hidden
     *
     * @return boolean $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets hidden
     *
     * @param boolean $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the boolean state of hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->getHidden();
    }

    /**
     * Returns disabled
     *
     * @return boolean $disabled
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Sets disabled
     *
     * @param boolean $disabled
     * @return void
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * Returns the boolean state of disabled
     *
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->getDisabled();
    }

    /**
     * Returns the sha1
     *
     * @return string $sha1
     */
    public function getSha1()
    {
        return $this->sha1;
    }

    /**
     * Sets the sha1
     *
     * @param string $sha1
     * @return void
     */
    public function setSha1($sha1)
    {
        $this->sha1 = $sha1;
    }

    /**
     * Returns the sha1ValidUntil
     *
     * @return string $sha1ValidUntil
     */
    public function getSha1ValidUntil()
    {
        return $this->sha1ValidUntil;
    }

    /**
     * Sets the sha1ValidUntil
     *
     * @param string $sha1ValidUntil
     * @return void
     */
    public function setSha1ValidUntil($sha1ValidUntil)
    {
        $this->sha1ValidUntil = $sha1ValidUntil;
    }

    /**
     * Returns the distance
     *
     * @return integer $distance
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Sets the distance
     *
     * @param integer $distance
     * @return void
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * Returns the privacy
     *
     * @return integer $privacy
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Sets the privacy
     *
     * @param integer $privacy
     * @return void
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
    }


}