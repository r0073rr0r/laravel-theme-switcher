<?php

namespace r0073rr0r\ThemeSwitcher\Tests\Fixtures;

class FakeUser
{
    public int $saveCalls = 0;

    public function __construct(
        public ?string $theme_preference = null,
    ) {}

    /**
     * @param array<string, mixed> $attributes
     */
    public function forceFill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function save(): bool
    {
        $this->saveCalls++;

        return true;
    }
}
