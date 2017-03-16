<?php

/**
 * @file
 * Contains script to delete all ceremony, degree, and address data.
 */

use Drupal\node\Entity\Node;

// Delete all ceremonies.
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_ceremony');
  ->execute();
entity_delete_multiple('node', $query);

// Delete all degrees.
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_degree');
  ->execute();
entity_delete_multiple('node', $query);

// Delete all addresses.
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_address');
  ->execute();
entity_delete_multiple('node', $query);

?>
