<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle\tests\Unit\Mocks;

class InjectableMock
{
    /** @var int */
    public $timeout;

    /** @var string */
    public $secretKey;

    public function __inject(int $timeout, string $secretKey): void
    {
        $this->timeout = $timeout;
        $this->secretKey = $secretKey;
    }
}
