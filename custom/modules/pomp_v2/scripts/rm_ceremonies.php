<?php

/**
 * @file
 * Contains rm_ceremonies.php
 * Remove new Graduation Ceremony type data from graduations.lib.unb.ca v2.
 */

$query = \Drupal::entityQuery('node');
$query->condition('type', 'graduation_ceremony');
$tids = $query->execute();

$storage_handler = \Drupal::entityTypeManager()->getStorage('node');
$entities = $storage_handler->loadMultiple($tids);
$storage_handler->delete($entities);
