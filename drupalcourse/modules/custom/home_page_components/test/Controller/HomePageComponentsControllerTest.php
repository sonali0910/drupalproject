<?php

namespace Drupal\Tests\home_page_components\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\home_page_components\Controller\HomePageComponentsController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\home_page_components\Service\ExceptionHandlerService;

/**
 * Tests the HomePageComponentsController.
 *
 * @group home_page_components
 */
class HomePageComponentsControllerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['home_page_components'];

  /**
   * The controller under test.
   *
   * @var \Drupal\home_page_components\Controller\HomePageComponentsController
   */
  protected $controller;

  /**
   * The mock exception handler service.
   *
   * @var \PHPUnit\Framework\MockObject\MockObject|\Drupal\home_page_components\Service\ExceptionHandlerService
   */
  protected $exceptionHandler;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Mock the ExceptionHandlerService.
    $this->exceptionHandler = $this->createMock(ExceptionHandlerService::class);

    // Create the controller instance.
    $this->controller = new HomePageComponentsController($this->exceptionHandler);
  }

  /**
   * Tests the getDisplayCarousel method.
   */
  public function testGetDisplayCarousel() {
    $expectedData = [
      ['title' => 'Slide 1', 'subtitle' => 'Subtitle 1'],
      ['title' => 'Slide 2', 'subtitle' => 'Subtitle 2'],
    ];

    // Configure the mock to return the expected data.
    $this->exceptionHandler->method('getCarousel')->willReturn($expectedData);

    // Call the method and assert the response.
    $response = $this->controller->getDisplayCarousel();
    $this->assertInstanceOf(JsonResponse::class, $response);
    $this->assertEquals($expectedData, json_decode($response->getContent(), true));
  }

  /**
   * Tests the getMobileData method.
   */
  public function testGetMobileData() {
    $expectedData = [
      ['title' => 'Mobile 1', 'model' => 'Model X'],
      ['title' => 'Mobile 2', 'model' => 'Model Y'],
    ];

    // Configure the mock to return the expected data.
    $this->exceptionHandler->method('getMobileData')->willReturn($expectedData);

    // Call the method and assert the response.
    $response = $this->controller->getMobileData();
    $this->assertInstanceOf(JsonResponse::class, $response);
    $this->assertEquals($expectedData, json_decode($response->getContent(), true));
  }
}
