<?php
namespace RKW\RkwConsultant\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 
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
 * Test case for class RKW\RkwConsultant\Controller\ServiceController.
 *
 */
class ServiceControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \RKW\RkwConsultant\Controller\ServiceController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('RKW\\RkwConsultant\\Controller\\ServiceController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllServicesFromRepositoryAndAssignsThemToView() {

		$allServices = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$serviceRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServiceRepository', array('findAll'), array(), '', FALSE);
		$serviceRepository->expects($this->once())->method('findAll')->will($this->returnValue($allServices));
		$this->inject($this->subject, 'serviceRepository', $serviceRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('services', $allServices);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenServiceToView() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('service', $service);

		$this->subject->showAction($service);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenServiceToView() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newService', $service);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($service);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenServiceToServiceRepository() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$serviceRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServiceRepository', array('add'), array(), '', FALSE);
		$serviceRepository->expects($this->once())->method('add')->with($service);
		$this->inject($this->subject, 'serviceRepository', $serviceRepository);

		$this->subject->createAction($service);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenServiceToView() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('service', $service);

		$this->subject->editAction($service);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenServiceInServiceRepository() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$serviceRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServiceRepository', array('update'), array(), '', FALSE);
		$serviceRepository->expects($this->once())->method('update')->with($service);
		$this->inject($this->subject, 'serviceRepository', $serviceRepository);

		$this->subject->updateAction($service);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenServiceFromServiceRepository() {
		$service = new \RKW\RkwConsultant\Domain\Model\Service();

		$serviceRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServiceRepository', array('remove'), array(), '', FALSE);
		$serviceRepository->expects($this->once())->method('remove')->with($service);
		$this->inject($this->subject, 'serviceRepository', $serviceRepository);

		$this->subject->deleteAction($service);
	}
}
