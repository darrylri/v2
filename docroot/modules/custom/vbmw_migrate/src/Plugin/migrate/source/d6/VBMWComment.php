<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\source\d6;

use Drupal\comment\Plugin\migrate\source\d6\Comment;
use Drupal\Core\Database\Database;
use Drupal\migrate\Row;

/**
 * Drupal 6 node comment source including attachments from database.
 *
 * @MigrateSource(
 *   id = "vbmw_comment",
 * )
 */
class VBMWComment extends Comment {

    /**
     * {@inheritdoc}
     */
    public function query() {
        $query = parent::query();
        $query->condition('n.type', 'profile', '!=');
        return $query;
    }

  /**
   * @inheritdoc
   */
  function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('nid');
    $cid = $row->getSourceProperty('cid');

    // Find all attached files and inject their migrated fid values.
    // Get the d6 fids.
    $files = $this->select('comment_upload', 'u')
                  ->fields('u', ['fid'])
                  ->condition('nid', $nid)
                  ->condition('cid', $cid)
                  ->execute();
    $fids = $files->fetchCol();

    // Convert to d8 fids and URIs.
    $uris = [];
    if (!empty($fids)){
      $files = Database::getConnection('default', 'default')
                       ->select('migrate_map_vbmw_file', 'f')
                       ->fields('f', ['destid1'])
                       ->condition('f.sourceid1', $fids, 'IN')
                       ->execute();
      $d8_fids = $files->fetchCol();
      if (!empty($d8_fids)) {
          $fids = $d8_fids;
          $uris = Database::getConnection('default', 'default')
              ->select('file_managed', 'fm')
              ->fields('fm', ['uri'])
              ->condition('fm.fid', $fids, 'IN')
              ->execute()
              ->fetchCol();
      }
    }
    $row->setSourceProperty('fids', $fids);
    $row->setSourceProperty('uris', $uris);

    return parent::prepareRow($row);
  }

}