<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle;

class InjectionTemplate
{
    /** @var array */
    protected $arguments;

    /** @param  mixed  ...$arguments */
    public function __construct(...$arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
