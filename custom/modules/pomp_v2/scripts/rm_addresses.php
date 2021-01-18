<?php

/**
 * @file
 * Contains rm_addresses.php
 * Remove new Address type data from graduations.lib.unb.ca v2.
 */

$query = \Drupal::entityQuery('node');
$query->condition('type', 'address');
$tids = $query->execute();

$storage_handler = \Drupal::entityTypeManager()->getStorage('node');
$entities = $storage_handler->loadMultiple($tids);
$storage_handler->delete($entities);
