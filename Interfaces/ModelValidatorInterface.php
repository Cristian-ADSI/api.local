<?php

namespace Services\RestService\Interfaces;

interface ModelValidatorInterface
{
    public function validateTable(string $table): void;
    public function validateColumn(string $table, string $column): void;
}
