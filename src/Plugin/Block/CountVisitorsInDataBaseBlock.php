<?php

namespace Drupal\fortune_number\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a 'Count Visitors in DB' Block.
 *
 * @Block(
 *   id = "count_visitors_in_db_block",
 *   admin_label = @Translation("Count Visitors in DB Block"),
 *   category = @Translation("Fortune Number"),
 * )
 */
class CountVisitorsInDataBaseBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected Connection $database;
  private $plugin_id;
  private $plugin_definition;
  private RequestStack $requestStack;

  public function __construct(
      array $configuration,
      $plugin_id,
      $plugin_definition,
      Connection $database,
      RequestStack $requestStack
  ) {
    parent::__construct( $configuration, $plugin_id, $plugin_definition );

    $this->setConfiguration( $configuration );
    $this->plugin_id = $plugin_id;
    $this->plugin_definition = $plugin_definition;
    $this->database = $database;
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->addNewVisitorInDB();
    $visitorsNumber = $this->countVisitorsInDB();

    return [
      '#theme' => 'fortune_number_theme_hook',
      '#visitorNumber' => $visitorsNumber
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  protected function countVisitorsInDB() {
      $queryCount = $this->database->select('visitors_statistics')->countQuery();

    return $queryCount->execute()->fetchField();
  }

  protected function addNewVisitorInDB() {
    $queryInsert = $this->database->insert('visitors_statistics' )
      ->fields( ['visitor_ip'], [
          $this->requestStack->getCurrentRequest()->getClientIp()
    ]);

    try {
      $queryInsert->execute();
    } catch( \Exception $e ) {
    }

    return true;
  }
}
