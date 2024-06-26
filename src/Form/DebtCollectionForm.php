<?php

namespace Drupal\debt_collection\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\debt_collection\Collection\Rocky;
use Drupal\debt_collection\Collection\CollectionAgency;
use Drupal\user\Entity\User;
use Drupal\taxonomy\Entity\Term;

class DebtCollectionForm extends FormBase {

  public function getFormId() {
    return 'debt_collection_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $user_service = \Drupal::service('debt_collection.user_load_service');
    $all_users = $user_service->loadAllUsers();
    $user_options = ['' => $this->t('Select')];

    foreach ($all_users as $user) {
      if ($user->id() != 0) {
        $user_options[$user->id()] = $user->get('name')->value;
      }
    }

    $vocabulary = 'debt_collector';
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vocabulary)
      ->accessCheck(True);
    $tids = $query->execute();

    $terms = Term::loadMultiple($tids);
    $taxonomy_terms = [];

    foreach ($terms as $term) {
      $taxonomy_terms[$term->id()] = $term->getName();
    }

    $form['taxonomy_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Debt Collector provider'),
      '#options' => $taxonomy_terms,
      '#ajax' => [
        'callback' => '::selectDebtCollector',
        'wrapper' => 'debt-info',
        'event' => 'change'
      ]
    ];

    $form['user_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select User'),
      '#options' => $user_options,
      '#ajax' => [
        'callback' => '::updateDebtInfo',
        'wrapper' => 'debt-info',
        'event' => 'change'
      ],
    ];

    $form['debt_info'] = [
      '#type' => 'markup',
      '#markup' => '<div id="debt-info"></div>',
      '#allowed_tags' => ['div'],
    ];
    return $form;
  }

  public function updateDebtInfo(array &$form, FormStateInterface $form_state) {
    $user_id = $form_state->getValue('user_select');
    $debt_service = \Drupal::service('debt_collection.debt_collection_service');

    $collector = new Rocky();
    $collected_amount = $debt_service->collectDebtForUser($user_id, $collector);

    $user = User::load($user_id);
    $owed_amount = $user->get('owed_amount')->value;

    $markup = 'Rocky guaranteed 65% of owed amount. Rocky will bring you $' . $collected_amount . ' from owed amount $' . $owed_amount;
    $form['debt_info']['#markup'] = '<div id="debt-info">' . $markup . '</div>';

    return $form['debt_info'];
  }

  public function selectDebtCollector() {

    $vocabulary = 'debt_collector';
    $query = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', $vocabulary)
      ->accessCheck(true);
    $tids = $query->execute();

    $terms = Term::loadMultiple($tids);
    $taxonomy_terms = [];

    foreach ($terms as $term) {
      $taxonomy_terms[$term->id()] = $term->getName();
    }

    return $taxonomy_terms;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
