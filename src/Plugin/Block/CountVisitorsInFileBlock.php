<?php

namespace Drupal\fortune_number\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Count Visitors In a File' Block.
 *
 * @Block(
 *   id = "count_visitors_in_file_block",
 *   admin_label = @Translation("Count Visitors In a File Block"),
 *   category = @Translation("Fortune Number"),
 * )
 */
class CountVisitorsInFileBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $visitorsNumber = $this->countVisitorsInFile();

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

  private function countVisitorsInFile() {
    $dirName = str_replace( 'src/Plugin/Block', 'public', __DIR__ );
    $counterFileName = $dirName . '/counterVisitors.txt';

    if( !file_exists( $dirName ) ) {
      mkdir ( $dirName );
    }

    $f = fopen( $counterFileName,"a+" );

    if( filesize( $counterFileName ) == 0 ) {
      $counterVal = 1;

      fwrite( $f, $counterVal );
    } else {
      $counterVal = fread( $f, filesize( $counterFileName ) );
      ftruncate( $f, 0 );

      /**
       * logic to renew counter after reaching a specific value
       */
//      if( ++$counterVal == 10 ) {
//        fwrite( $f, 0 );
//      } else {
//        fwrite( $f, $counterVal );
//      }

      fwrite($f, ++$counterVal);
    }

    fclose( $f );

    return $counterVal;
  }
}
