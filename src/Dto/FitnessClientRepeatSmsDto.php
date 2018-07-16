<?php

namespace App\Dto;

class FitnessClientRepeatSmsDto
{
    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $sms;

    /**
     * FitnessClientRepeatSmsDto constructor.
     *
     * @param string $phone
     * @param string $sms
     */
    public function __construct(string $phone, string $sms)
    {
        $this->phone = $phone;
        $this->sms = $sms;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getSms(): string
    {
        return $this->sms;
    }
}