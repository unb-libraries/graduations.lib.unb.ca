<?php

namespace Drupal\pomp_add_linking\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * EditAddressesForm object.
 */
class EditAddressesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pomp_add_linking_edit_addresses_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {
    $form = [];

    // List existing addresses.
    $view = Views::getView('list_ceremony_addresses');
    $view->setDisplay('block_1');
    $view->setArguments([$node]);
    $render = $view->render();
    $form['list_ceremony_addresses_view'] = $render;

    // Add addresses.
    $form['add_address_button'] = [
      '#type' => 'link',
      '#title' => t('Add New Address'),
      '#url' => Url::fromUri("internal:/node/add/honorary_address"),
      '#attributes' => [
        'class' => ['button'],
      ],
    ];

    $usr = \Drupal::currentUser()->id();
    $ses = \Drupal::service('session_manager')->getId();
    $pomp_add_linking_parent_cer = 'pomp_add_linking_parent_cer' . $usr . $ses;
    \Drupal::state()->set($pomp_add_linking_parent_cer, $node);

    $actual_node = Node::load($node);
    $year = $actual_node->get('field_ceremony_year')->getValue()[0]['value'];
    $pomp_add_linking_year = 'pomp_add_linking_year' . $usr . $ses;
    \Drupal::state()->set($pomp_add_linking_year, $year);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
