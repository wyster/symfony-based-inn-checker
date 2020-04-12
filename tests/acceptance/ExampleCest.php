<?php declare(strict_types=1);

namespace Test;

class ExampleCest
{
    public function tryToTest(AcceptanceTester $I): void
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
    }
}
