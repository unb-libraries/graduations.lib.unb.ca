<?php

/**
 * @file
 * Script to reverse the direction of degree and address relationships relative
 * to ceremony.
 */

use Drupal\node\Entity\Node;

// Fetch ceremonies
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_ceremony');
$cers = $query->execute();

// Iterate through ceremonies
foreach ($cers as $cer) {
  // Format ceremony id as entity reference
  $cer_node = Node::load($cer);
  $cer_id = $cer_node->get('nid')->getValue();
  $cer_id_val = $cer_id[0]['value'];
  $cer_id_ref = array('target' => $cer_id_val);

  // Fetch degrees that contain the ceremony reference
  $query2 = \Drupal::entityQuery('node')
    ->condition('type', 'honorary_degree')
    ->condition('field_ref_degree_ceremony', $cer_id_ref, 'IN');
  $degs = $query2->execute();

  // Iterate through degrees
  foreach ($degs as $deg) {
    // Format degree id as entity reference
    $deg_node = Node::load($deg);
    $deg_id = $deg_node->get('nid')->getValue();
    $deg_id_val = $deg_id[0]['value'];
    $deg_id_ref = array('target_id' => $deg_id_val);

    // Get ceremonies referenced by degree
    $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();

    // If the ceremony isn't already referencing the degree, add reference.
    if (!in_array($deg_id_ref, $ref_cer_deg)) {
      $cer_node->field_ref_ceremony_degree->appendItem($deg_id_ref);
    }

    unset($deg_node);
  }

  // Fetch addresses that contain the ceremony reference
  // Process addresses exactly as previously done with degrees
  $query3 = \Drupal::entityQuery('node')
    ->condition('type', 'honorary_address')
    ->condition('field_ref_address_ceremony', $cer_id_ref, 'IN');
  $adds = $query3->execute();

  foreach ($adds as $add) {
    $add_node = Node::load($add);
    $add_id = $add_node->get('nid')->getValue();
    $add_id_val = $add_id[0]['value'];
    $add_id_ref = array('target_id' => $add_id_val);

    $ref_cer_add = $cer_node->get('field_ref_ceremony_address')->getValue();

    if (!in_array($add_id_ref, $ref_cer_add)) {
      $cer_node->field_ref_ceremony_address->appendItem($add_id_ref);
    }

    unset($add_node);
  }

  // Save changes to ceremony node, close, and reiterate.
  $cer_node->save();
  unset($cer_node);
}
?>
