<?php

namespace Drupal\Tests\home_page_components\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\home_page_components\Service\ExceptionHandlerService;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\file\Entity\File;

/**
 * Tests the ExceptionHandlerService.
 *
 * @group home_page_components
 */
class ExceptionHandlerServiceTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['home_page_components', 'node', 'file'];

  /**
   * The service under test.
   *
   * @var \Drupal\home_page_components\Service\ExceptionHandlerService
   */
  protected $exceptionHandlerService;

  /**
   * The mock entity type manager.
   *
   * @var \PHPUnit\Framework\MockObject\MockObject|\Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The mock logger channel factory.
   *
   * @var \PHPUnit\Framework\MockObject\MockObject|\Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Mock the EntityTypeManagerInterface.
    $this->entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);

    // Mock the LoggerChannelFactoryInterface.
    $this->loggerFactory = $this->createMock(LoggerChannelFactoryInterface::class);
    $loggerChannel = $this->createMock(LoggerInterface::class);
    $this->loggerFactory->method('get')->willReturn($loggerChannel);

    // Create the service instance.
    $this->exceptionHandlerService = new ExceptionHandlerService($this->entityTypeManager, $this->loggerFactory);
  }

  /**
   * Tests the getCarousel method.
   */
  public function testGetCarousel() {
    // Mock the node storage and query.
    $nodeStorage = $this->createMock('Drupal\Core\Entity\EntityStorageInterface');
    $this->entityTypeManager->method('getStorage')->willReturn($nodeStorage);

    $node = $this->createMock('Drupal\node\Entity\Node');
    $node->method('get')->willReturnMap([
      ['field_carousel_title', (object) ['value' => 'Carousel Title']],
      ['field_carousel_subtitle', (object) ['value' => 'Carousel Subtitle']],
      ['field_mobile_model', (object) ['value' => 'Model X']],
      ['field_carousel_button_1', (object) ['uri' => 'http://example.com', 'title' => 'Button 1']],
      ['field_carousel_button_2', (object) ['uri' => 'http://example.com', 'title' => 'Button 2']],
      ['field_carousel_bg_image', (object) ['entity' => $this->createMock(File::class)]],
    ]);

    $nodeStorage->method('loadByProperties')->willReturn([$node]);

    // Call the method and assert the response.
    $carouselData = $this->exceptionHandlerService->getCarousel();
    $this->assertIsArray($carouselData);
    $this->assertNotEmpty($carouselData);
    $this->assertEquals('Carousel Title', $carouselData[0]['title']);
  }

  /**
   * Tests the getMobileData method.
   */
  public function testGetMobileData() {
    // Mock the node storage and query.
    $nodeStorage = $this->createMock('Drupal\Core\Entity\EntityStorageInterface');
    $this->entityTypeManager->method('getStorage')->willReturn($nodeStorage);

    $node = $this->createMock('Drupal\node\Entity\Node');
    $node->method('get')->willReturnMap([
      ['field_subtitle', (object) ['value' => 'Mobile Subtitle']],
      ['field_download', (object) ['uri' => 'http://example.com', 'title' => 'Download']],
      ['field_mobileimage', (object) ['entity' => $this->createMock(File::class)]],
    ]);

    $nodeStorage->method('loadByProperties')->willReturn([$node]);

    // Call the method and assert the response.
    $mobileData = $this->exceptionHandlerService->getMobileData();
    $this->assertIsArray($mobileData);
    $this->assertNotEmpty($mobileData);
    $this->assertEquals('Mobile Subtitle', $mobileData[0]['subtitle']);
  }
}
