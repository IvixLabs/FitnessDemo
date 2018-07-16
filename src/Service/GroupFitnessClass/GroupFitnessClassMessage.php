<?php

namespace App\Service\GroupFitnessClass;

class GroupFitnessClassMessage
{
    /**
     * @var string
     */
    private $groupFitnessClassId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $sms;

    public function __construct(string $groupFitnessClassId, string $email = null, string $sms = null)
    {
        $this->groupFitnessClassId = $groupFitnessClassId;
        $this->email = $email;
        $this->sms = $sms;
    }

    /**
     * @return string
     */
    public function getGroupFitnessClassId(): string
    {
        return $this->groupFitnessClassId;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSms(): ?string
    {
        return $this->sms;
    }
}