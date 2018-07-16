<?php

namespace App\Service;

use App\Service\GroupFitnessClass\GroupFitnessClassMessage;

interface GroupFitnessClassAsyncMessageServiceInterface
{
    public function sendAsyncMessage(GroupFitnessClassMessage $message);
}