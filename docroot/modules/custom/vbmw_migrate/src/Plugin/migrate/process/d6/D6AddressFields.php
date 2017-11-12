<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\process\d6;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Maps a set of D6 field values to address values.
 *
 * @MigrateProcessPlugin(
 *   id = "d6_addressfield"
 * )
 */

class D6AddressFields extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $parms = [
      'country_code' => ['uc', 'fix_country'],
      'administrative_area' => ['uc',],
      'locality' => ['uc',],
      'dependent_locality' => [],
      'postal_code' => ['uc',],
      'sorting_code' => [],
      'address_line1' => ['uc',],
      'address_line2' => ['uc',],
      'organization' => [],
      'given_name' => [],
      'family_name' => [],
      'additional_name' => [],
    ];

    $parsed = [];
    foreach ($parms as $field => $parm) {
      if (isset($this->configuration[$field])) {
        $value = $row->getSourceProperty($this->configuration[$field]);
        $value = $value[0]['value'];
        if (in_array('uc', $parm)) {
          $value = strtoupper($value);
        }
        if (in_array('fix_country', $parm)) {
          static $country_fix = ['UK' => 'GB'];
          if (!empty($country_fix[$value])) {
            $value = $country_fix[$value];
          }
        }
        $parsed[$field] = $value;
      }
      else {
        $parsed[$field] = NULL;
      }
    }
    
    return $parsed;
  }

}
