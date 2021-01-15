<?php

/**
 * @file
 * Contains rm_awards.php
 * Remove new Award type data from graduations.lib.unb.ca v2.
 */

$query = \Drupal::entityQuery('node');
$query->condition('type', 'award');
$tids = $query->execute();

$storage_handler = \Drupal::entityTypeManager()->getStorage($entity_type);
$entities = $storage_handler->loadMultiple($tids);
$storage_handler->delete($entities);
