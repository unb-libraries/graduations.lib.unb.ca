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

// Add ceremony types.
addTerms('ceremony_voc', $ceremony_types);

// Add address types.
addTerms('address_type_voc', $address_types);

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
    Term::create([
      'vid' => $vid,
      'name' => $term,
    ])->save();
  }
}
