<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\process\d6;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

  /**
   * Forces various date formats into one that can be inserted into the db.
   *
   * @MigrateProcessPlugin(
   *   id = "d6_fix_date"
   * )
   */

class D6FixDate extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $format = 'Y-m-d h:i:s';
    if (!empty($this->configuration['format'])) {
      $format = $this->configuration['format'];
    }
    if (!empty($value) && is_string($value)) {
      $value = date($format, strtotime($value));
    }

    return $value;
  }

}