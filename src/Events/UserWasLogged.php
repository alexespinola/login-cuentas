<?php

namespace loginCuentas\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;


class UserWasLogged
{
  use Dispatchable; // ,SerializesModels;

  public $userID;

  public function __construct($userID)
  {
    $this->userID = $userID;
  }
}