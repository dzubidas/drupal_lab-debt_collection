<?php

namespace Drupal\debt_collection\Services;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;

class UserLoadService
{
  /**
   * The entity type manager
   *
   * @var EntityTypeManagerInterface
  */
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Load all users
   * @return array
  */
  public function loadAllUsers(): array
  {
    $query = $this->entityTypeManager->getStorage('user')->getQuery()->accessCheck(FALSE);
    $users_ids = $query->execute();

    return $this->entityTypeManager->getStorage('user')->loadMultiple($users_ids);
  }

  /**
   * Load user by ID
   *
   * @param int $user_id
   */
  public function loadUserById(int $user_id)
  {
    return $this->entityTypeManager->getStorage('user')->load($user_id);
  }


  /**
   * Set owed amount to user
   * @param int $user_id
   * @param float $amount
   * @return void
   */
  public function setOwedAmount(int $user_id, float $amount): void {

    $user = $this->loadUserById($user_id);

    if ($user && $user->hasField('owed_amount')) {
      $user->set('owed_amount', $amount)
        ->save();
    }
  }
}
