<?php

/**
 * @file
 * Contains rm_awards.php
 * Remove new Award type data from graduations.lib.unb.ca v2.
 */

$query = \Drupal::entityQuery('node');
$query->condition('type', 'award');
$nids = $query->execute();

$storage_handler = \Drupal::entityTypeManager()->getStorage('node');
$entities = $storage_handler->loadMultiple($nids);
$storage_handler->delete($entities);
