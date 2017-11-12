<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Cleans up dates that just have a month and year.
 *
 * @MigrateProcessPlugin(
 *   id = "month_date"
 * )
 */

class MonthDate extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $value = date('Y-m-d', strtotime('last day of ' . substr($value, 0, 7)));
    return $value;
  }
}
