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
 * Test case for class RKW\RkwConsultant\Controller\ServicesController.
 *
 */
class ServicesControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \RKW\RkwConsultant\Controller\ServicesController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('RKW\\RkwConsultant\\Controller\\ServicesController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllServicessFromRepositoryAndAssignsThemToView() {

		$allServicess = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$servicesRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServicesRepository', array('findAll'), array(), '', FALSE);
		$servicesRepository->expects($this->once())->method('findAll')->will($this->returnValue($allServicess));
		$this->inject($this->subject, 'servicesRepository', $servicesRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('servicess', $allServicess);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenServicesToView() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('services', $services);

		$this->subject->showAction($services);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenServicesToView() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newServices', $services);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($services);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenServicesToServicesRepository() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$servicesRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServicesRepository', array('add'), array(), '', FALSE);
		$servicesRepository->expects($this->once())->method('add')->with($services);
		$this->inject($this->subject, 'servicesRepository', $servicesRepository);

		$this->subject->createAction($services);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenServicesToView() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('services', $services);

		$this->subject->editAction($services);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenServicesInServicesRepository() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$servicesRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServicesRepository', array('update'), array(), '', FALSE);
		$servicesRepository->expects($this->once())->method('update')->with($services);
		$this->inject($this->subject, 'servicesRepository', $servicesRepository);

		$this->subject->updateAction($services);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenServicesFromServicesRepository() {
		$services = new \RKW\RkwConsultant\Domain\Model\Services();

		$servicesRepository = $this->getMock('RKW\\RkwConsultant\\Domain\\Repository\\ServicesRepository', array('remove'), array(), '', FALSE);
		$servicesRepository->expects($this->once())->method('remove')->with($services);
		$this->inject($this->subject, 'servicesRepository', $servicesRepository);

		$this->subject->deleteAction($services);
	}
}
