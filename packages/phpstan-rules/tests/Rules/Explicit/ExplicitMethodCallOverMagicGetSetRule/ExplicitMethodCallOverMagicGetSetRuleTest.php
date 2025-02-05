<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\Explicit\ExplicitMethodCallOverMagicGetSetRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Symplify\PHPStanRules\Rules\Explicit\ExplicitMethodCallOverMagicGetSetRule;

/**
 * @extends RuleTestCase<ExplicitMethodCallOverMagicGetSetRule>
 */
final class ExplicitMethodCallOverMagicGetSetRuleTest extends RuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorsWithLines
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    public function provideData(): Iterator
    {
        $getterErrorMessage = \sprintf(
            ExplicitMethodCallOverMagicGetSetRule::ERROR_MESSAGE,
            'id',
            '$someSmartObject',
            'getId'
        );
        yield [__DIR__ . '/Fixture/MagicGetter.php', [[$getterErrorMessage, 13]]];

        $setterErrorMessage = \sprintf(
            ExplicitMethodCallOverMagicGetSetRule::ERROR_MESSAGE,
            'id',
            '$someSmartObject',
            'setId'
        );
        yield [__DIR__ . '/Fixture/MagicSetter.php', [[$setterErrorMessage, 13]]];

        $boolGetterErrorMessage = \sprintf(
            ExplicitMethodCallOverMagicGetSetRule::ERROR_MESSAGE,
            'valid',
            '$someSmartObject',
            'isValid'
        );
        yield [__DIR__ . '/Fixture/MagicIsGetter.php', [[$boolGetterErrorMessage, 16]]];

        yield [__DIR__ . '/Fixture/SkipNormalPropertyFetch.php', []];
        yield [__DIR__ . '/Fixture/SkipPrivateMethod.php', []];
        yield [__DIR__ . '/Fixture/SkipAssignToPositions.php', []];
        yield [__DIR__ . '/Fixture/SkipLocalGetter.php', []];
        yield [__DIR__ . '/Fixture/SkipGetterOnObjectWithoutMagicGet.php', []];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ExplicitMethodCallOverMagicGetSetRule::class);
    }
}
