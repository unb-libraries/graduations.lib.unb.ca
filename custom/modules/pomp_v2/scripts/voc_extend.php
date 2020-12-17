<?php

/**
 * @file
 * Contains voc_extend.php
 * Add additional vocabulary terms for V2.
 */

use Drupal\taxonomy\Entity\Term;

// New ceremony types as per ticket POMP-122.
$ceremony_types = [
  'Virtual Convocation',
  'Virtual Convocation - Ceremony A',
  'Virtual Convocation - Ceremony B',
  'Virtual Convocation - Ceremony C',
  'Virtual Convocation - Ceremony D',
];

// New address types as per ticket POMP-123.
$address_types = [
  "Founder's Day Address",
];

// Graduate award types for new content type as per POMP-125.
$gradaward_types =[
  'Douglas Gold Medal',
  'Governor General’s Gold Medal',
  'Governor General’s Silver Medal',
  'Lieutenant Governor of New Brunswick’s Silver Medal',
];

// Add ceremony types.
addTerms('ceremony_voc', $ceremony_types);

// Add address types.
addTerms('address_type_voc', $address_types);

// Add graduate award types.
addTerms('gradaward_type_voc', $gradaward_types);

/**
 * Add multiple terms to a given vocabulary.
 *
 * @param string $vid
 *   A string indicating the id of the vocabulary to update.
 * @param array $terms
 *   An array containing the names of the terms to add.
 */
function addTerms($vid, $terms) {

  foreach ($terms as $term) {
    $found = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', $vid)
      ->condition('name', $term)
      ->execute();

    if (!$found) {
      Term::create([
        'vid' => $vid,
        'name' => $term,
      ])->save();

      echo "[+] [$term]->[$vid]\n";
    }
    else {
      echo "[-] [$term] exists in [$vid]\n";
    }
  }
}
