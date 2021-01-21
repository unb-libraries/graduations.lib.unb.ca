<?php

/**
 * @file
 * Contains populate_vocabularies.php
 * Populates vocabulary terms for graduations.lib.unb.ca v2.
 */

use Drupal\taxonomy\Entity\Term;

// Address types for graduations.lib.unb.ca v2.
$address_types = [
  "Alumni Oration",
  "Founder's Day Address",
  "Graduation Address",
  "President's Address",
  "Valedictory Address",
];

// Award types for graduations.lib.unb.ca v2.
$award_types = [
  "Faculty Award/Honour",
  "Graduate Award",
  "Honorary Degree",
  "Professor Emeritus",
];

// Campus options for graduations.lib.unb.ca v2.
$campi = [
  'Fredericton',
  'Kenya',
  'Saint John',
];

// Ceremony types for graduations.lib.unb.ca v2.
$ceremony_types = [
  'Academic Awards Ceremony',
  '1st Academic Awards Ceremony',
  '2nd Academic Awards Ceremony',
  '3rd Academic Awards Ceremony',
  '4th Academic Awards Ceremony',
  'Convocation',
  'Convocation - Ceremony A',
  'Convocation - Ceremony B',
  'No Convocation Occurred',
  'Encaenia',
  'Encaenia - Ceremony A',
  'Encaenia - Ceremony B',
  'Encaenia - Ceremony C',
  'Encaenia - Ceremony D',
  'Special Convocation (January)',
  'Special Convocation (February)',
  'Special Convocation (March)',
  'Special Convocation (April)',
  'Special Convocation (May)',
  'Special Convocation (June)',
  'Special Convocation (July)',
  'Special Convocation (August)',
  'Special Convocation (September)',
  'Special Convocation (October)',
  'Special Convocation (November)',
  'Special Convocation (December)',
  'Special Encaenia (March)',
  'Spring Convocation',
  'Virtual Convocation',
  'Virtual Convocation - Ceremony A',
  'Virtual Convocation - Ceremony B',
  'Virtual Convocation - Ceremony C',
  'Virtual Convocation - Ceremony D',
];

// Faculty award options for graduations.lib.unb.ca v2.
$facaward_types = [
  'Dr. Allan P. Stuart Memorial for Excellence in Teaching',
  'University of New Brunswick Award for Excellence in Research',
];

// Gender options for graduations.lib.unb.ca v2.
$genders = [
  'Female',
  'Male',
  'Unspecified',
];

// Graduate award types for new content type as per POMP-125.
$gradaward_types = [
  'Douglas Gold Medal',
  'Governor General’s Gold Medal',
  'Governor General’s Silver Medal',
  'Lieutenant Governor of New Brunswick’s Silver Medal',
];

// Honorary degree options for graduations.lib.unb.ca v2.
$honorary_degrees = [
  'D.C.L.',
  'D.Litt.',
  'D.Sc.',
  'LL.D.',
  'M.A.',
  'M.Sc.',
  'N/A',
  'Ph.D.',
];

// Graduation ceremony season options for graduations.lib.unb.ca v2.
$seasons = [
  'Fall',
  'Special',
  'Spring',
];

// Add award types.
addTerms('award_type', $award_types);

// Add address types.
addTerms('address_type', $address_types);

// Add campi.
addTerms('campus', $campi);

// Add ceremony types.
addTerms('ceremony_type', $ceremony_types);

// Add faculty award types.
addTerms('faculty_award_type', $facaward_types);

// Add gender options.
addTerms('gender', $genders);

// Add graduate award types.
addTerms('grad_award_type', $gradaward_types);

// Add honorary degrees.
addTerms('honorary_degree', $honorary_degrees);

// Add seasons.
addTerms('season', $seasons);

// Update honorary degree descriptions.
foreach ($honorary_degrees as $honorary_degree) {
  // Search for degree by name.
  $found = \Drupal::entityQuery('taxonomy_term')
    ->condition('vid', 'honorary_degree')
    ->condition('name', $honorary_degree)
    ->execute();

  if ($found) {
    // Get found term and description.
    $term = Term::load(reset($found));

    switch ($honorary_degree) {
      case 'D.C.L.':
        $term->setDescription('Doctor of Civil Law');
        $term->save();
        break;

      case 'D.Litt.':
        $term->setDescription('Doctor of Letters');
        $term->save();
        break;

      case 'D.Sc.':
        $term->setDescription('Doctor of Science');
        $term->save();
        break;

      case 'LL.D.':
        $term->setDescription('Doctor of Laws');
        $term->save();
        break;

      case 'M.A.':
        $term->setDescription('Master of Arts');
        $term->save();
        break;

      case 'M.Sc.':
        $term->setDescription('Master of Science');
        $term->save();
        break;

      case 'Ph.D.':
        $term->setDescription('Doctor of Philosophy');
        $term->save();
        break;
    }
  }
}

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