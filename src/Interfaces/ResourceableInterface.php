<?php

namespace App\Interfaces;

interface ResourceableInterface
{
    public function toResource(): ArrayableInterface;
}
