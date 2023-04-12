<?php

declare(strict_types=1);
include_once __DIR__ . '/stubs/Validator.php';
class WaermemengenZaehlerValidationTest extends TestCaseSymconValidation
{
    public function testValidateWaermemengenZaehler(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }
    public function testValidateWaermemengenZaehlerModule(): void
    {
        $this->validateModule(__DIR__ . '/../WaermemengenZaehler');
    }
}