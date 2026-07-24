<?php

declare(strict_types=1);

namespace App\Validation;

/**
 * Small server-side validator covering the rule set this phase
 * needs. Add rules as new forms require them rather than building a
 * generic rule-expression parser up front.
 */
final class Validator
{
    /** @var array<string, array<int, string>> */
    private array $errors = [];

    public function __construct(private readonly array $data)
    {
    }

    public function required(string $field, string $label): self
    {
        $value = $this->data[$field] ?? '';

        if (is_string($value) && trim($value) === '') {
            $this->addError($field, "{$label} is required.");
        }

        return $this;
    }

    public function email(string $field, string $label): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($field, "{$label} must be a valid email address.");
        }

        return $this;
    }

    public function minLength(string $field, int $min, string $label): self
    {
        $value = $this->data[$field] ?? '';

        if (is_string($value) && $value !== '' && mb_strlen($value) < $min) {
            $this->addError($field, "{$label} must be at least {$min} characters.");
        }

        return $this;
    }

    public function matches(string $field, string $otherField, string $label): self
    {
        $value = $this->data[$field] ?? '';
        $other = $this->data[$otherField] ?? '';

        if ($value !== $other) {
            $this->addError($field, "{$label} does not match.");
        }

        return $this;
    }

    /**
     * @param array<int, string> $allowed
     */
    public function in(string $field, array $allowed, string $label): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !in_array($value, $allowed, true)) {
            $this->addError($field, "{$label} is not a valid selection.");
        }

        return $this;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }
}
