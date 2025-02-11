<?php

namespace Drupal\home_page_components\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Drupal\file\Entity\File;

/**
 * Provides exception handling and data fetching services.
 */
class ExceptionHandlerService {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs an ExceptionHandlerService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger channel factory service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LoggerChannelFactoryInterface $logger_factory) {
    $this->entityTypeManager = $entity_type_manager;
    // Get the specific logger channel for your module.
    $this->logger = $logger_factory->get('home_page_components');
  }

  /**
   * Handles exceptions and logs them.
   *
   * @param \Exception $exception
   *   The exception to handle.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   A response object with an error message.
   */
  public function handleException(Exception $exception) {
    $this->logger->error('Error: @message', ['@message' => $exception->getMessage()]);
    return new Response('An error occurred. Please try again later.', Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  /**
   * Retrieves carousel data for display.
   *
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the mobile data.
   */
  public function getCarousel() {
    try {
      $carouselNodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['type' => 'carousel']);

      $carouselData = [];
      foreach ($carouselNodes as $node) {
        // Get static and dynamic content for carousel mobile model text
      if ($node && $node->hasField('field_mobile_model')) {
        $mobile_model = $node->get('field_mobile_model')->value;
      }

       // Get the carousel button 1 data (if it exists).
       $button1 = '';
       if ($node->hasField('field_carousel_button_1') && !$node->get('field_carousel_button_1')->isEmpty()) {
         $button1 = [
             'url' => $node->get('field_carousel_button_1')->uri,
             'text' => $node->get('field_carousel_button_1')->title,
           ];
       }

       // Get the carousel button 2 data (if it exists).
       $button2 = '';
       if ($node->hasField('field_carousel_button_2') && !$node->get('field_carousel_button_2')->isEmpty()) {
         $button2 = [
             'url' => $node->get('field_carousel_button_2')->uri,
             'text' => $node->get('field_carousel_button_2')->title,
           ];
       }

       // Get the carousel image (if it exists).
       $carouselimage = '';
       if ($node->hasField('field_carousel_bg_image') && !$node->get('field_carousel_bg_image')->isEmpty()) {
           $file = $node->get('field_carousel_bg_image')->entity;
           if ($file instanceof File) {
               $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
           }
       }

      $carouselData[] = [
        'title' => $node->get('field_carousel_title')->value,
        'subtitle' => $node->get('field_carousel_subtitle')->value,
        'mobilemodel' => $mobile_model,
        'button1' => $button1,
        'button2' => $button2,
        'bgimage' => $image_url,
      ];
      }

      return $carouselData;
    }
    catch (Exception $e) {
      return $this->handleException($e);
    }
  }

  /**
   * Retrieves mobile data for display.
   *
   * This method fetches mobile data using the ExceptionHandlerService and
   * returns it as a JSON response. The data typically includes fields such as
   * title, model, and other relevant attributes for mobile showcase.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the mobile data.
   */
  public function getMobileData() {
    try {
      $nodes = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadByProperties(['type' => 'mobile_showcase']);

      $data = [];
      foreach ($nodes as $node) {

        // Get the image field (if it exists).
          $image = '';
          if ($node->hasField('field_mobileimage') && !$node->get('field_mobileimage')->isEmpty()) {
              $file = $node->get('field_mobileimage')->entity;
              if ($file instanceof File) {
                  $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
              }
          }

        // Get the subtitle field (if it exists).
        $subtitle = '';
        if ($node->hasField('field_subtitle') && !$node->get('field_subtitle')->isEmpty()) {
          $subtitle = $node->get('field_subtitle')->value;
        }

        // Get the download link field (if it exists).
        $download_link = '';
        if ($node->hasField('field_download') && !$node->get('field_download')->isEmpty()) {
          $download_link = [
              'url' => $node->get('field_download')->uri,
              'text' => $node->get('field_download')->title,
            ];
        }

        $data[] = [
          'id' => $node->id(),
          'title' => $node->getTitle(),
          'subtitle' => $subtitle,
          'body' => $node->get('body')->value,
          'download_link' => $download_link,
          'image' => $image_url,
        ];
        return $data;
      }
    }
      catch (Exception $e) {
        return $this->handleException($e);
    }

  }

}