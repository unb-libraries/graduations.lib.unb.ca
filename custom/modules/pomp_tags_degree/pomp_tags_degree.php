<?php

/**
 * @file
 * Contains pmportal_lexical_item.install.
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

  // POMP-37: Add Cattley attribution.
  $ora = $node->get('field_orator')->getValue()[0]['value'];

  if (strpos($ora, 'tley') !== false) {
    $cit = $node->get('field_citation')->getValue()[0]['value'];
    $cit_new = $cit . '<br><br /><strong>From:</strong> <br />Cattley, Robert E.D.'
      . ' <em>Honoris causa: the effervescences of a university orator</em>.'
      . ' Fredericton: UNB Associated Alumnae, 1968.';
    $node->get('field_citation')->setValue($cit_new);
  }
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
