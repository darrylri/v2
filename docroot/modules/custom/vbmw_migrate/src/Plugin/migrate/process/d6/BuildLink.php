<?php

namespace Drupal\vbmw_migrate\Plugin\migrate\process\d6;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Take an alleged url and rebuilt it so that it will not break link fields.
 *
 * @MigrateProcessPlugin(
 *   id = "d6_build_link"
 * )
 */
class BuildLink extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (isset($value) && is_array($value)) {
      if (!in_array('url', array_keys($value))) {
        $value = array_pop($value);
      }

      $value = [
        'uri' => $this->valid_uri($value['url']),
        'title' => $value['title'],
        'options' => $value['attributes'],
      ];
    }
    else {
      $url = $this->configuration['url'];
      $url = $row->getSourceProperty($url);
      if (is_array($url)) {
        $url = array_shift($url);
      }
      $title = $this->configuration['title'];
      $options = $this->configuration['options'];
      $url = $this->valid_uri($url);

      $value = [
        'uri' => $url,
        'title' => $title,
        'options' => $options,
      ];
    }
    return $value;
  }

  /**
   * Builds a valid, complete uri from an input string
   * @param string $value
   *  the proposed uri
   * @return string
   *  a complete uri or empty
   */
  protected function valid_uri($value) {
    $newvalue = $value;
    if (!empty($value)) {
      $parts = parse_url($value);
      $parts['scheme'] = $parts['scheme'] ?: 'http';
      $userpass = $parts['user'] . ':' . $parts['pass'] . '@';
      $userpass = $userpass != ':@' ?: '';
      $port = empty($parts['port']) ? '' : ':' . $parts['port'];
      $query = empty($parts['query']) ? '' : '?' . $parts['query'];
      $frag = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];

      $newvalue = 
        $parts['scheme'] . '://' .
        $userpass .
        $parts['host'] .
        $port .
        $parts['path'] .
        $query . $frag;
      if ($newvalue === 'http://') {
        $newvalue = '';
      }
    }
    return $newvalue;
  }

}
