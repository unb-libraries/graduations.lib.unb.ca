<?php

namespace Drupal\pomp_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom Browse block for the home page.
 *
 * @Block(
 *   id = "pomp_browse",
 *   admin_label = @Translation("Browse"),
 *   category = @Translation("Misc"),
 * )
 */
class PompBrowse extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = '
      <p><a href="/ceremonies"><span class="sr-only">Browse </span>Graduation Ceremonies</a></p>
      <p><a href="/awards"><span class="sr-only">Browse </span>Honorary Degrees and Awards</a></p>
    ';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
