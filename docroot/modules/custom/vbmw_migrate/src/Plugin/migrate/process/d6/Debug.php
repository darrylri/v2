<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\process\d6;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Convert any reasonable date string into yyyy-mm-dd, which is what MySQL wants
 * to see as a date.
 *
 * @MigrateProcessPlugin(
 *   id = "d6_debug"
 * )
 */
class Debug extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    echo "DEBUG: " . $this->configuration['message'] . "\n";
    print_r(['value'=>$value]);
    print_r(['configuration'=>$this->configuration]);
//    $stacktrace = !isset($this->configuration['stacktrace']) ? TRUE : $this->configuration['stacktrace'];
//    if ($stacktrace) {
//      debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
//    }
    $dump_row = !isset($this->configuration['row']) ? TRUE : $this->configuration['row'];
    if ($dump_row) {
      print_r(['row'=>$row]);
    }
    return $value;
  }

}
