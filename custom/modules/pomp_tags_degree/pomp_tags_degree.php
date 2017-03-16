<?php

/**
 * @file
 * Contains script to finalize html tags in degree orations.
 */

use Drupal\node\Entity\Node;

// Get number of items we need to update.
$count_query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_degree');
$count_nids = $count_query->execute();
$num_nodes = count($count_nids);

// Set batch size to ALL records.

$batch_size = $num_nodes;

// Set up the batch if this is the first run.
if (!isset($sandbox['progress'])) {
  $sandbox['progress'] = 0;
  $sandbox['current_nid'] = 0;
  $sandbox['max'] = $num_nodes;
}

// Actual row query.
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_degree')
  ->range(0, $batch_size);
$nids = $query->execute();

// Iterate through rows, load node, save node.
foreach ($nids as $nid) {
  $node = Node::load($nid);
  $node->get('field_citation')->format = 'full_html';
  $node->save();
  $sandbox['progress']++;
  $sandbox['current_nid'] = $node->id();

  unset($node);
}

// Update #finished value.
$sandbox['#finished'] = empty($sandbox['max']) ? 1 : ($sandbox['progress'] / $sandbox['max']);

// To display a message to the user when the update is completed, return it.
return t('All nodes iterated through and saved.');
?>
