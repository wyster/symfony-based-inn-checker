<?php declare(strict_types=1);

namespace Test;

class InnFormCest
{
    private const VALID_INN = '366221019350';
    private const INVALID_INN = '366221019361';

    public function tryToTest(FunctionalTester $I): void
    {
        $I->amOnPage('/');
    }

    public function tryToTestWithValidInnAndPays(FunctionalTester $I): void
    {
        $inn = self::VALID_INN;
        $I->haveInDatabase('tax_payer', ['pays' => true, 'inn' => $inn]);
        $I->amOnPage(sprintf('/?inn=%s', $inn));
        $content = $I->grabPageSource();
        $I->assertRegExp('/ИНН является самозанятым/u', $content);
    }

    public function tryToTestWithValidInnAndNodPays(FunctionalTester $I): void
    {
        $inn = self::VALID_INN;
        $I->haveInDatabase('tax_payer', ['pays' => false, 'inn' => $inn]);
        $I->amOnPage(sprintf('/?inn=%s', $inn));
        $content = $I->grabPageSource();
        $I->assertRegExp('/ИНН не является самозанятым/u', $content);
    }
}
