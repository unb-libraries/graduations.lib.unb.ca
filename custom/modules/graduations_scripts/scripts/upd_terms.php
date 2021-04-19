<?php

/**
 * @file
 * Contains upd_terms.php.
 *
 * Updates taxonomy terms for graduations.lib.unb.ca v2.
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
  "Emeritus Honour",
  "Faculty Award/Honour",
  "Graduate Award",
  "Honorary Degree",
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
  'Alumni Gold Medal',
  'Douglas Gold Medal',
  'Governor General’s Gold Medal',
  'Governor General’s Silver Medal',
  'Ketchum Silver Medal',
  'Lieutenant Governor of New Brunswick’s Silver Medal',
  'Montgomery-Campbell Prize',
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

// Emeritus designation options for graduations.lib.unb.ca v2.
$emeritus_types = [
  'Professor Emeritus',
  'Professor Emerita',
  'President Emeritus',
  'Librarian Emeritus',
  'Librarian Emerita',
  'Governor Emeritus',
  'Governor Emerita',
  'Registrar Emeritus',
  'Registrar Emeritus',
  'Dean Emeritus',
  'Dean Emerita',
  'Vice-President Emeritus',
  'Vice-President Emerita',
  'Associate Vice-President Emeritus',
  'Associate Vice-President Emerita',
  'Chancellor Emeritus',
  'Chancellor Emerita',
  'Residence Fellow Emeritus',
  'Senior Teaching Associate Emeritus',
];

// Graduation ceremony season options for graduations.lib.unb.ca v2.
$seasons = [
  'Fall',
  'Special',
  'Spring',
];

// Add award types.
add_terms('award_type', $award_types);

// Add address types.
add_terms('address_type', $address_types);

// Add campi.
add_terms('campus', $campi);

// Add ceremony types.
add_terms('ceremony_type', $ceremony_types);

// Add faculty award types.
add_terms('faculty_award_type', $facaward_types);

// Add gender options.
add_terms('gender', $genders);

// Add graduate award types.
add_terms('grad_award_type', $gradaward_types);

// Add honorary degrees.
add_terms('honorary_degree', $honorary_degrees);

// Add emeritus designation types.
add_terms('emeritus_type', $emeritus_types);

// Add seasons.
add_terms('season', $seasons);

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
function add_terms($vid, array $terms) {

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
