<?php declare(strict_types=1);

namespace Test;

use App\Validator\InnValidator;
use Codeception\Test\Unit;

final class InnValidatorTest extends Unit
{
    private const VALID_INN = '366221019350';
    private const INVALID_INN = '366221019361';

    protected UnitTester $tester;

    public function isValidProvider(): array
    {
        return [
            [self::VALID_INN, true],
            [self::INVALID_INN, false],
            ['', false]
        ];
    }

    /**
     * @dataProvider isValidProvider
     * @param string $value
     * @param bool $expected
     */
    public function testIsValid(string $value, bool $expected): void
    {
        $validator = new InnValidator();
        $this->assertSame($expected, $validator->isValid($value));
    }
}
