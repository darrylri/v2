<?php
namespace Drupal\vbmw_migrate\Plugin\migrate\process\d6;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Maps a set of D6 field values to address values.
 *
 * @MigrateProcessPlugin(
 *   id = "d6_file_data"
 * )
 */

class D6FileData extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!empty($value) && is_string($value)) {
      $data = unserialize($value);
      foreach ($data as $key => $item) {
        $row->setDestinationProperty($key, $item);
      }
    }

    return $value;
  }

}