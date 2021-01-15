<?php

/**
 * @file
 * Contains cp_awards.php
 * Copy old-to-new Award type data for graduations.lib.unb.ca v2.
 */

use \Drupal\node\Entity\Node;
use \Drupal\taxonomy\Entity\Term;

// Query entities.
$query = \Drupal::entityQuery('node');
$query->condition('type', 'honorary_degree');
$nids = $query->execute();
$nids=[16650];
// Load into array.
$storage_handler = \Drupal::entityTypeManager()->getStorage('node');
$entities = $storage_handler->loadMultiple($nids);

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
    'field_old_nid' => $award_old->id(),
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
    'field_image_caption' => $award_old->field_img_caption->getString(),
    'field_image_caption2' => $award_old->field_img_caption_2->getString(),
    'field_orator_name' => $award_old->field_orator->getString(),
    'field_citation' => [
      'format' => 'full_html',
      'value' => $award_old->field_citation->getString(),
    ],
    'field_degree' => $award_old->field_degree_notes->getString(),
  ]);

  $new_award->save();
}
