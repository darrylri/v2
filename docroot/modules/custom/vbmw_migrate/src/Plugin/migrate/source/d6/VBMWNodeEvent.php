<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\source\d6;

use Drupal\vbmw_migrate\Plugin\migrate\source\d6;
use Drupal\node\Plugin\migrate\source\d6\Node;
use Drupal\Core\Database\Database;
use Drupal\migrate\Row;

/**
 * Drupal 6 node source from database.
 *
 * @MigrateSource(
 *   id = "vbmw_node_event",
 * )
 */
class VBMWNodeEvent extends VBMWNode {

  /**
   * @inheritdoc
   */
  function query() {
    $query = parent::query();
    $query->leftJoin('location_instance', 'li', 'n.nid=li.nid AND nr.vid=li.vid');
    $query->leftJoin('location', 'l', 'l.lid=li.lid');
    $query->fields('l', ['latitude', 'longitude',
                         'name', 'street', 'additional',
                         'city', 'province', 'postal_code', 'country', ]);
    return $query;
  }

  /**
   * @inheritdoc
   */
  function fields() {
    $fields = parent::fields();
    $fields += ['latitude' => $this->t('Location latitude.'),
                'longitude' => $this->t('Location longitude.'),
                'name' => $this->t('Location name.'),
                'street' => $this->t('Location street.'),
                'additional' => $this->t('Location additional.'),
                'city' => $this->t('Location city.'),
                'province' => $this->t('Location state/province.'),
                'postal_code' => $this->t('Location postal code.'),
                'country' => $this->t('Location ISO country code.'),
      ];
    return $fields;
  }

  function prepareRow(Row $row) {
    // Fix country ISO codes.
    // Force upper case.
    $country = strtoupper($row->getSourceProperty('country'));
    static $country_updates = ['UK' => 'GB'];
    $country = empty($country_updates[$country]) ? $country : $country_updates[$country];
    $row->setSourceProperty('country', $country);

    return parent::prepareRow($row);
  }

}