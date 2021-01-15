<?php

/**
 * @file
 * Contains cp_addresses.php
 * Copy old-to-new Address type data for graduations.lib.unb.ca v2.
 */

use \Drupal\node\Entity\Node;
use \Drupal\taxonomy\Entity\Term;

// Query entities.
$query = \Drupal::entityQuery('node');
$query->condition('type', 'honorary_address');
$nids = $query->execute();
// Load into array.
$storage_handler = \Drupal::entityTypeManager()->getStorage('node');
$entities = $storage_handler->loadMultiple($nids);

// Scan through array and create new address with former data.
foreach ($entities as $address_old) {
  // Get old address type name.
  $type = $address_old->field_list_address_type->entity->getName();
  // Find new address type term id.
  $type_tid = \Drupal::entityQuery('taxonomy_term')
    ->condition('vid', 'address_type')
    ->condition('name', $type)
    ->execute();
  $type_tid = reset($type_tid);
  // Get speaker.
  $speaker = $address_old->field_delivered_by->getString();

  // Create new address.
  $new_address = Node::create([
    'field_old_nid' => $address_old->id(),
    'type' => 'address',
    'title' => "$type delivered by $speaker",
    'field_address_type' => [
      ['target_id' => $type_tid]
    ],
    'field_address_speaker' => $address_old->field_delivered_by,
    'field_address_text' => [
      'format' => 'full_html',
      'value' => $address_old->field_address_content->getString(),
    ],
  ]);

  $new_address->save();
  exit;
}
