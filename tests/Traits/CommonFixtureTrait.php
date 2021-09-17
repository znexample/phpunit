<?php

namespace Tests\Traits;

use Tests\Helpers\FixtureHelper;

trait CommonFixtureTrait
{

    protected function setUp(): void
    {
        $this->addFixtures(FixtureHelper::getCommonFixtures());
        parent::setUp();
    }
}
