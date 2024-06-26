<?php

namespace Drupal\debt_collection\Collection;

class Rocky implements DebtCollectionInterface
{
  public function collectDebt(float $owed_amount): float
  {

    return $owed_amount * 0.65;
  }
}
