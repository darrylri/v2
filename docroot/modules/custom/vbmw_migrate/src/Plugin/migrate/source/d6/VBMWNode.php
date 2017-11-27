<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\source\d6;

use Drupal\node\Plugin\migrate\source\d6\Node;
use Drupal\Core\Database\Database;
use Drupal\migrate\Row;

/**
 * Drupal 6 node source from database.
 *
 * @MigrateSource(
 *   id = "vbmw_node",
 * )
 */
class VBMWNode extends Node {

  function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');

    // Find all tags and inject their migrated tid values into the source row.
    // Get d6 tids.
    $terms = $this->select('term_node', 'tn')
                  ->fields('tn', ['tid'])
                  ->condition('nid', $nid)
                  ->condition('vid', $vid)
                  ->execute();
    $tids = $terms->fetchCol();

    // Convert to d8 tids.
    if (!empty($tids)){
      $terms = Database::getConnection('default', 'default')
                       ->select('migrate_map_vbmw_term', 't')
                       ->fields('t', ['destid1'])
                       ->condition('t.sourceid1', $tids, 'IN')
                       ->execute();
      $tids = $terms->fetchCol();
    }
    $row->setSourceProperty('tids', $tids);

    // Find all attached files and inject their migrated fid values.
    // Get the d6 fids.
    $files = $this->select('upload', 'u')
                  ->fields('u', ['fid'])
                  ->condition('nid', $nid)
                  ->condition('vid', $vid)
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
      $fids = $files->fetchCol();
      $uris = Database::getConnection('default', 'default')
                      ->select('file_managed', 'fm')
                      ->fields('fm', ['uri'])
                      ->condition('fm.fid', $fids, 'IN')
                      ->execute()
                      ->fetchCol();
    }
    $row->setSourceProperty('fids', $fids);
    $row->setSourceProperty('uris', $uris);

    return parent::prepareRow($row);
  }

}