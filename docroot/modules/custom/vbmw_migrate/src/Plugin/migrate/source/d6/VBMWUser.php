<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\source\d6;

use Drupal\user\Plugin\migrate\source\d6\User;

/**
 * Drupal 6 user source from database.
 *
 * @MigrateSource(
 *   id = "d6_vbmw_user",
 * )
 */
class VBMWUser extends User {

  /**
   * {@inheritdoc}
   */
  protected function baseFields() {
    $fields = parent::baseFields() +
              [
                'signature' => $this->t('User\'s signature'),
                'signature_format' => $this->t('Signature format'),
                ];

    return $fields;
  }

}

