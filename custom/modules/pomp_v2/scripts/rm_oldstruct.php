<?php

/**
 * @file
 * Contains rm_oldstruct.php
 * Remove old v1 data from graduations.lib.unb.ca v2.
 */

delete_nodes('honorary_address');
delete_nodes('honorary_degree');
delete_nodes('honorary_ceremony');

function delete_nodes($type) {
  $query = \Drupal::entityQuery('node');
  $query->condition('type', $type);
  $tids = $query->execute();
  $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
  $entities = $storage_handler->loadMultiple($tids);
  $storage_handler->delete($entities);
}
