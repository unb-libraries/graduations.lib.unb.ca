<?php

/**
 * @file
 * Script to reverse the direction of degree and address relationships relative
 * to ceremony.
 */

use Drupal\node\Entity\Node;

// Fetch ceremonies
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_ceremony')
  ->condition('nid', array(313, 314), 'IN');
$cers = $query->execute();

//print_r("\n***Debug cers:\n");
//print_r($cers);

// Iterate through ceremonies
foreach ($cers as $cer) {
  // Format ceremony id as entity reference
  $cer_node = Node::load($cer);
  $cer_id = $cer_node->get('nid')->getValue();
  $cer_id_val = $cer_id[0]['value'];
  $cer_id_ref = array('target' => $cer_id_val);

  //print_r("\n***Debug cer_id_ref:\n");
  //print_r($cer_id_ref);

  // Fetch degrees that contain the ceremony reference
  $query2 = \Drupal::entityQuery('node')
    ->condition('type', 'honorary_degree')
    ->condition('field_ref_degree_ceremony', $cer_id_ref, 'IN');
  $degs = $query2->execute();

  //print_r("\n***Debug degs:\n");
  //print_r($degs);

  // Iterate through degrees
  foreach ($degs as $deg) {
    // Format degree id as entity reference
    $deg_node = Node::load($deg);
    $deg_id = $deg_node->get('nid')->getValue();
    $deg_id_val = $deg_id[0]['value'];
    $deg_id_ref = array('target_id' => $deg_id_val);

    //print_r("\n***Debug deg_id_ref:\n");
    //print_r($deg_id_ref);

    // Get ceremonies referenced by degree
    $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();

    // If the ceremony isn't already referencing the degree, add reference.
    if (!in_array($deg_id_ref, $ref_cer_deg)) {
      $cer_node->field_ref_ceremony_degree->appendItem($deg_id_ref);
    }

    $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();

    //print_r("\n***Debug ref_cer_deg:\n");
    //print_r($ref_cer_deg);
  }

  // Fetch addresses that contain the ceremony reference
  // Process addresses exactly as previously done with degrees
  $query3 = \Drupal::entityQuery('node')
    ->condition('type', 'honorary_address')
    ->condition('field_ref_address_ceremony', $cer_id_ref, 'IN');
  $adds = $query3->execute();

  //print_r("\n***Debug degs:\n");
  //print_r($degs);

  // Iterate through degrees
  foreach ($adds as $add) {
    // Format degree id as entity reference
    $add_node = Node::load($add);
    $add_id = $add_node->get('nid')->getValue();
    $add_id_val = $add_id[0]['value'];
    $add_id_ref = array('target_id' => $add_id_val);

    //print_r("\n***Debug deg_id_ref:\n");
    //print_r($deg_id_ref);

    // Get ceremonies referenced by degree
    $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();

    // If the ceremony isn't already referencing the degree, add reference.
    if (!in_array($deg_id_ref, $ref_cer_deg)) {
      $cer_node->field_ref_ceremony_degree->appendItem($deg_id_ref);
    }

    $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();

    //print_r("\n***Debug ref_cer_deg:\n");
    //print_r($ref_cer_deg);
  }

  /*
  $cer_node = Node::load($cer);
  $cer_id = $cer_node->get('nid')->getValue()[0]['value'];
  print_r("\n***Debug cer_id:\n");
  print_r($cer_id);
  $cer_id_val = $cer_id;
  print_r("\n***Debug cer_id_val:\n");
  print_r($cer_id_val);
  $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();
  print_r("\n***Debug ref_cer_deg:\n");
  print_r($ref_cer_deg);
  $ref_array = array('target_id' => 4426);
  $ref_array2 = array('target_id' => 4427);
  $cer_node->field_ref_ceremony_degree->appendItem($ref_array);
  $cer_node->field_ref_ceremony_degree->appendItem($ref_array2);
  $ref_cer_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();
  print_r("\n***Debug ref_cer_deg:\n");
  print_r($ref_cer_deg);
  */

/*
// Iterate through ceremony node ids
foreach ($cers as $cer) {
  $cer_node = Node::load($cer);

  $array = array('value' => 4425);
  $ref = $cer_node->get('field_ref_ceremony_degree')->getValue();
  print_r($ref);
  echo '***';
  $cer_node->field_ref_ceremony_degree->appendItem($array);
  $ref = $cer_node->get('field_ref_ceremony_degree')->getValue();
  print_r($ref);

  $cer_id = $cer_node->get('nid')->getValue();

  // Fetch descendant degrees
  $query2 = \Drupal::entityQuery('node')
    ->condition('type', 'honorary_degree');
    // ->condition('field_ref_degree_ceremony', $cer_id);
  $degs = $query2->execute();

  // Iterate through degree nodes
  foreach ($degs as $deg) {
    $deg_node = Node::load($deg);
    $deg_id = $deg_node->get('nid')->getValue();
    // print_r($deg_id);
    $ref_array = array('target_id' => $deg_id);
    print_r($ref_array);
    echo '***CHECK1***';
    $cer_node->field_ref_ceremony_degree->appendItem($ref_array);
    echo '***CHECK2***';
    unset($deg_node);
  }

  $ref_deg = $cer_node->get('field_ref_ceremony_degree')->getValue();
  print_r('Ceremony:');
  print_r($cer_id);
  print_r('References');
  print_r($ref_deg);
  */

  /*
  $array = array('target_id' => 4425);
  $node->field_ref_ceremony_degree->appendItem($array);
  $ref = $node->get('field_ref_ceremony_degree')->getValue();
  print_r($ref);
  echo $ref[0][target_id];
  */

  unset($cer_node);
}
?>
