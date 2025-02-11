<?php

namespace Drupal\home_page_components\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\home_page_components\Service\ExceptionHandlerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomePageComponentsController extends ControllerBase {

  protected $exceptionHandler;

  public function __construct(ExceptionHandlerService $exception_handler) {
    $this->exceptionHandler = $exception_handler;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('home_page_components.exception_handler')
    );
  }

  public function getDisplayCarousel() {
    $carouselData = $this->exceptionHandler->getCarousel();
    return new JsonResponse($carouselData);
  }

  public function getMobileData() {
    $mobileData = $this->exceptionHandler->getMobileData();
    return new JsonResponse($mobileData);
  }
}
