<?php

namespace RKW\RkwConsultant\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \RKW\RkwConsultant\Domain\Model\Services.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ServicesTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \RKW\RkwConsultant\Domain\Model\Services
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \RKW\RkwConsultant\Domain\Model\Services();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getFurtherInformationsReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFurtherInformations()
		);
	}

	/**
	 * @test
	 */
	public function setFurtherInformationsForStringSetsFurtherInformations() {
		$this->subject->setFurtherInformations('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'furtherInformations',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getConsultingServiceReturnsInitialValueForConsultingService() {
		$this->assertEquals(
			NULL,
			$this->subject->getConsultingService()
		);
	}

	/**
	 * @test
	 */
	public function setConsultingServiceForConsultingServiceSetsConsultingService() {
		$consultingServiceFixture = new \RKW\RkwConsultant\Domain\Model\ConsultingService();
		$this->subject->setConsultingService($consultingServiceFixture);

		$this->assertAttributeEquals(
			$consultingServiceFixture,
			'consultingService',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getQualificationReturnsInitialValueForQualification() {
		$this->assertEquals(
			NULL,
			$this->subject->getQualification()
		);
	}

	/**
	 * @test
	 */
	public function setQualificationForQualificationSetsQualification() {
		$qualificationFixture = new \RKW\RkwConsultant\Domain\Model\Qualification();
		$this->subject->setQualification($qualificationFixture);

		$this->assertAttributeEquals(
			$qualificationFixture,
			'qualification',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSpecialServicesReturnsInitialValueForSpecialServices() {
		$this->assertEquals(
			NULL,
			$this->subject->getSpecialServices()
		);
	}

	/**
	 * @test
	 */
	public function setSpecialServicesForSpecialServicesSetsSpecialServices() {
		$specialServicesFixture = new \RKW\RkwConsultant\Domain\Model\SpecialServices();
		$this->subject->setSpecialServices($specialServicesFixture);

		$this->assertAttributeEquals(
			$specialServicesFixture,
			'specialServices',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getContactPersonReturnsInitialValueForContactPerson() {
		$this->assertEquals(
			NULL,
			$this->subject->getContactPerson()
		);
	}

	/**
	 * @test
	 */
	public function setContactPersonForContactPersonSetsContactPerson() {
		$contactPersonFixture = new \RKW\RkwConsultant\Domain\Model\ContactPerson();
		$this->subject->setContactPerson($contactPersonFixture);

		$this->assertAttributeEquals(
			$contactPersonFixture,
			'contactPerson',
			$this->subject
		);
	}
}
