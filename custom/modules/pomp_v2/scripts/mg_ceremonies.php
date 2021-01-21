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

    // Get honorary degrees.
    $awards = $ceremony_old->field_ref_ceremony_degree->getValue();
    $award_nids = [];
    // Scan multi-value field and put all referenced nids in array.
    foreach ($awards as $award) {
      $award_nids[] = $award['target_id'];
    }
    // Migrate awards, returns formatted ids.
    $new_awards = migrate_awards($award_nids);

    // Create new ceremony.
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
      'field_notes' => [
        'format' => 'unb_libraries',
        'value' => $ceremony_old->field_ceremony_notes->getValue()[0]['value'],
      ],
      'field_addresses' => $new_addresses,
      'field_awards' => $new_awards,
    ]);

    $new_ceremony->save();
    $title = $new_ceremony->getTitle();
    echo "\nCreated ceremony [$title]";
  }
  echo "\n";
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
      'field_address_speaker' => $speaker,
      'field_address_text' => [
        'format' => 'unb_libraries',
        'value' => $address_old->field_address_content->getValue()[0]['value'],
      ],
    ]);

    $new_address->save();
    $new_nids[] = ['target_id' => $new_address->id()];
  }

  return $new_nids;
}

// Migrate awards.
function migrate_awards($nids) {
  // Load entities into array.
  $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
  $entities = $storage_handler->loadMultiple($nids);
  $new_nids = [];

  // Scan through array and create new award with former data.
  foreach ($entities as $award_old) {
    // Find new award type term id.
    $type_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'award_type')
      ->condition('name', 'Honorary Degree')
      ->execute();
    $type_tid = reset($type_tid);
    // Get recipient.
    $recipient = $award_old->field_recipient_name->getString();
    // Get degree.
    $degree = $award_old->field_list_degree->entity->getName();
    // Find new degree term id.
    $degree_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'honorary_degree')
      ->condition('name', $degree)
      ->execute();
    $degree_tid = reset($degree_tid);
    // Get gender.
    $gender = $award_old->field_list_gender->entity->getName();
    // Find new gender term id.
    $gender_tid = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'gender')
      ->condition('name', $gender)
      ->execute();
    $gender_tid = reset($gender_tid);
    // Get valedictorian.
    $valedictorian = $award_old->field_list_valedictorian->getName();
    // Change valedictorian to boolean.
    $valedictorian = $valedictorian == 'Yes';
    // Get image entity.
    $image = $award_old->field_image->entity;

    // Create new award.
    $new_award = Node::create([
      'type' => 'award',
      'field_award_type' => [
        ['target_id' => $type_tid]
      ],
      'title' => "Honorary Degree awarded to $recipient ($degree)",
      'field_honorary_degree' => [
        ['target_id' => $degree_tid]
      ],
      'field_recipient' => $recipient,
      'field_recipient_valedictorian' => $valedictorian,
      'field_recipient_gender' => [
        ['target_id' => $gender_tid]
      ],
      'field_image' => $image,
      'field_image_caption' => $award_old->field_img_caption,
      'field_image_caption2' => $award_old->field_img_caption_2,
      'field_orator_name' => $award_old->field_orator,
      'field_citation' => [
        'format' => 'unb_libraries',
        'value' => $award_old->field_citation->getValue()[0]['value'],
      ],
      'field_notes' => [
        'format' => 'unb_libraries',
        'value' => $award_old->field_degree_notes->getValue()[0]['value'],
      ],
    ]);

    $new_award->save();
    $new_nids[] = ['target_id' => $new_award->id()];
  }

  return $new_nids;
}
