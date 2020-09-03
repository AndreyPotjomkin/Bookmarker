<?php

namespace App\Entities\Grabber;

interface GrabberInterface
{
    public function status(): string;

    public function error(): string;

    public function errorMessage(): string;

    public function fill(): void;

    public function fields(): array;
}
