<?php

namespace Drupal\pomp_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom Footer block for the page.
 *
 * @Block(
 *   id = "pomp_footer",
 *   admin_label = @Translation("Honorary Footer"),
 *   category = @Translation("Misc"),
 * )
 */
class PompFooter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text = '
      <div id="footer-menu">
        <br>
        <a href="/">Home</a> |
        <a href="/ceremonies">Browse Ceremonies</a> |
        <a href="/degrees">Browse Honorary Degrees</a> |
        <a href="/about">About</a> |
        <a href="/traditions">Traditions</a> |
        <a href="/orators">Orators</a> |
        <a href="/acknowledgements">Acknowledgements</a> |
        <a href="/contact">Contact Us</a>
        <br>
        All contents © ' . date('Y') . '
          <a href="https://www.lib.unb.ca/archives/">
            Archives &amp; Special Collections</a>
          and
          <a href="https://www.lib.unb.ca/">UNB Libraries</a>
        </div>

      <div id="footer-logo">
        <a href="https://www.lib.unb.ca/">
          <img alt="UNB Libraries Logo" data-align="right" data-entity-type="file" data-entity-uuid="287fcb32-b0dc-42d9-8d6b-ca14f575fff2" id="unb-lib-logo" src="/sites/default/files/inline-images/UNB-Libraries-Fton-Red-Black_0_0.png" />
        </a>
      </div>
    ';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
