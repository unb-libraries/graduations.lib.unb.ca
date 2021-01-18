<?php

/**
 * @file
 * Contains mg_ceremonies.php
 * Migrate old-to-new Graduation Ceremony type data for graduations.lib.unb.ca v2.
 */

use \Drupal\node\Entity\Node;
use \Drupal\taxonomy\Entity\Term;

// Query entities.
$query = \Drupal::entityQuery('node');
$query->condition('type', 'honorary_ceremony');
$nids = $query->execute();
$nids = [270];
migrate_ceremonies($nids);

function migrate_ceremonies($nids) {
  // Load into array.
  $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
  $entities = $storage_handler->loadMultiple($nids);

  // Scan through array and create new address with former data.
  foreach ($entities as $ceremony_old) {
    // Get campus.
    $campus = $ceremony_old->field_list_campus->entity->getName();
    // Find new campus term id.
    $campus_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'campus')
      ->condition('name', $campus)
      ->execute();
    $campus_tid = reset($campus_tid);
    // Get ceremony type.
    $type = $ceremony_old->field_list_ceremony_type->entity->getName();
    // Find new type term id.
    $type_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'ceremony_type')
      ->condition('name', $type)
      ->execute();
    $type_tid = reset($type_tid);
    // Get season.
    $season = $ceremony_old->field_list_season->entity->getName();
    // Find new season term id.
    $season_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'season')
      ->condition('name', $season)
      ->execute();
    $season_tid = reset($season_tid);

    // Get addresses.
    $addresses = $ceremony_old->field_ref_ceremony_address->getValue();
    $address_nids = [];
    // Scan multi-value field and put all referenced nids in array.
    foreach ($addresses as $address) {
      $address_nids[] = $address['target_id'];
    }
    // Migrate addresses, returns formatted ids.
    $new_addresses = migrate_addresses($address_nids);

    // Create new address.
    $new_ceremony = Node::create([
      'type' => 'graduation_ceremony',
      'field_ceremony_year' => $ceremony_old->field_ceremony_year->getString(),
      'field_campus' => [
        ['target_id' => $campus_tid]
      ],
      'field_ceremony_type' => [
        ['target_id' => $type_tid]
      ],
      'field_season' => [
        ['target_id' => $season_tid]
      ],
      'field_notes' => $ceremony_old->field_ceremony_notes->getString(),
      'field_addresses' => $new_addresses,
    ]);

    $new_ceremony->save();
  }
}

function migrate_addresses($nids) {
  // Load into array.
  $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
  $entities = $storage_handler->loadMultiple($nids);
  $new_nids = [];

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
    $new_nids[] = ['target_id' => $new_address->id()];
  }

  return $new_nids;
}
