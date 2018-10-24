<?php

namespace Drupal\pomp_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom Manage Content block for the page.
 *
 * @Block(
 *   id = "pomp_content_man",
 *   admin_label = @Translation("Manage Content"),
 *   category = @Translation("Misc"),
 * )
 */
class PompContentMan extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = '
      <p>
        <a href="/node/add/honorary_ceremony">New Graduation Ceremony</a> |
        <a href="/node/add/static_page">New Page</a> |
        <a href="/browse-content">Browse Content</a>
      </p>
    ';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
