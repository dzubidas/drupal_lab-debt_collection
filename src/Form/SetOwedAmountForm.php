<?php

namespace Drupal\debt_collection\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SetOwedAmountForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'set_owed_amount_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm($form, FormStateInterface $form_state)
  {
    $user_service = \Drupal::service('debt_collection.user_load_service');
    $all_users = $user_service->loadAllUsers();

    $users = ['' => $this->t('Select')];

    foreach ($all_users as $user) {
      if ($user->id() != 0) {
        $users[$user->id()] = $user->get('name')->value;
      }
    }

    $form['user_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Select User'),
      '#options' => $users,
    ];

    $form['owed_amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Owed amount'),
      '#required' => false,
      '#description' => $this->t('Enter the amount owed by the user.')
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
      '#attributes' => ['class' => ['button--primary']]
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $user_id = $form['user_id']['#value'];
    $owed_amount = $form['owed_amount']['#value'];

    $debt_service = \Drupal::service('debt_collection.user_load_service');
    $debt_service->setOwedAmount($user_id, $owed_amount);
  }

}
