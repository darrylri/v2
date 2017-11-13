<?php
namespace Drupal\vbmw_migrate\Plugin\migrate\source\d6;

use Drupal\comment\Plugin\migrate\source\d6\Comment;
use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Drupal 6 comment source for a particular node type from database.
 *
 * @MigrateSource(
 *   id = "d6_type_comment",
 * )
 */
class TypeComment extends Comment {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $node_type = $this->configuration['node_type'];
    $query = parent::query();
    $query->condition('n.type', $node_type)
          ->addField('n', 'uid', 'profile_uid');
    return $query;
  }

}
