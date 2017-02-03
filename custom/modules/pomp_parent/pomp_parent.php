<?php

/**
 * @file
 * Script to reverse the direction of degree and address relationships relative
 * to ceremony.
 */

use Drupal\node\Entity\Node;

// Fetch ceremonies
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_ceremony')
  ->condition('nid', 313);
$nids = $query->execute();

foreach ($nids as $nid) {
  $node = Node::load($nid);  
  $ref = $node->get('field_ref_ceremony_degree')->getValue();
  $array = array('target_id' => 4425);
  $node->field_ref_ceremony_degree->appendItem($array);
  $ref = $node->get('field_ref_ceremony_degree')->getValue();
  print_r($ref);
  echo $ref[0][target_id];
  unset($node);
}
?>
