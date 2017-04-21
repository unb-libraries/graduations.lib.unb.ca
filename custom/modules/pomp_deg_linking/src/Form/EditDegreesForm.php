<?php

namespace Drupal\pomp_deg_linking\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
use Drupal\Core\Url;

/**
 * EditDegreesForm object.
 */
class EditDegreesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pomp_deg_linking_edit_degrees_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {
    $form = [];

    // List existing degrees.
    $view = Views::getView('edit_ceremony_degrees');
    $view->setDisplay('block_1');
    $view->setArguments([$node]);
    $render = $view->render();
    $form['edit_ceremony_degrees_view'] = $render;

    // Add degrees.
    $form['add_degree_button'] = [
      '#type' => 'link',
      '#title' => t('Add New Degree'),
      '#url' => Url::fromUri("internal:/node/add/honorary_degree"),
      '#attributes' => [
        'class' => ['button'],
      ],
    ];

    $usr = \Drupal::currentUser()->id();
    $ses = \Drupal::service('session_manager')->getId();
    $pomp_deg_linking_parent_cer = 'parent_cer' . $usr . $ses;
    \Drupal::state()->set($pomp_deg_linking_parent_cer, $node);

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
