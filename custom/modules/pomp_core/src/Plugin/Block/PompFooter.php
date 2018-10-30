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
        <a href="/orators">Orators</a> |<br>
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
          <img alt="UNB Libraries Logo" id="unb-lib-logo" src="/themes/custom/pomp_subtheme_bs/images/UNB-Libraries-Fton-Red-Black.png" />
        </a>
      </div>
    ';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
