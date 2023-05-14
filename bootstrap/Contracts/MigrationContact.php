<?php

namespace WordCamp\Bootstrap\Contracts;

interface MigrationContact
{
    public function execute(): void;
}