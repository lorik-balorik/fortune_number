<?php
namespace Drupal\fortune_number\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Fortune Number module.
 */
class FortuneNumberController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function indexAction() {

    return [
      '#markup' => 'Hello, world',
    ];
  }

}
