<?php

/**
 * @file
 * Contains pmportal_lexical_item.install.
 */

 use Drupal\node\Entiy\Node;

 // Get number of items we need to update.
$count_query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_address');
$count_nids = $count_query->execute();
$num_nodes = count($count_nids);

// Set up the batch if this is the first run.
if (!isset($sandbox['progress'])) {
  $sandbox['progress'] = 0;
  $sandbox['current_nid'] = 0;
  $sandbox['max'] = $num_nodes;
}

// Actual row query.
$query = \Drupal::entityQuery('node')
  ->condition('type', 'honorary_address')
  ->range(0, $batch_size);
$nids = $query->execute();

// Iterate through rows, load node, save node.
foreach ($nids as $nid) {
  $node = Node::load($nid);
  $node->save();
  $sandbox['progress']++;
  $sandbox['current_nid'] = $node->id();
  }
  unset($node);
}

// Update #finished value.
$sandbox['#finished'] = empty($sandbox['max']) ? 1 : ($sandbox['progress'] / $sandbox['max']);

// To display a message to the user when the update is completed, return it.
return t('All nodes iterated through and saved.');
?>
