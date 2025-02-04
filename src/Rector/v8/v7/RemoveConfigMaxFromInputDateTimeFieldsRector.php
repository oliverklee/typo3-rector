<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.7/Deprecation-80027-RemoveTCAConfigMaxOnInputDateTimeFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFieldsRector\RemoveConfigMaxFromInputDateTimeFieldsRectorTest
 */
final class RemoveConfigMaxFromInputDateTimeFieldsRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isFullTca($node)) {
            return null;
        }

        $columnsArrayItem = $this->extractColumns($node);

        if (! $columnsArrayItem instanceof ArrayItem) {
            return null;
        }

        $columnItems = $columnsArrayItem->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($columnItems->items as $columnItem) {
            if (! $columnItem instanceof ArrayItem) {
                continue;
            }

            if (! $columnItem->key instanceof Expr) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            foreach ($columnItem->value->items as $configValue) {
                if (! $configValue instanceof ArrayItem) {
                    continue;
                }

                if (! $configValue->key instanceof Expr) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                if (! $this->isRenderTypeInputDateTime($configValue->value)) {
                    continue;
                }

                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (! $configItemValue->key instanceof Expr) {
                        continue;
                    }

                    if ($this->valueResolver->isValue($configItemValue->key, 'max')) {
                        $this->removeNode($configItemValue);
                        $hasAstBeenChanged = true;
                        break;
                    }
                }
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition("Remove TCA config 'max' on inputDateTime fields", [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'date' => [
            'exclude' => false,
            'label' => 'Date',
            'config' => [
                'renderType' => 'inputDateTime',
                'max' => 1,
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
        'date' => [
            'exclude' => false,
            'label' => 'Date',
            'config' => [
                'renderType' => 'inputDateTime',
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    private function isRenderTypeInputDateTime(Array_ $configValueArray): bool
    {
        foreach ($configValueArray->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (! $configItemValue->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($configItemValue->key, 'renderType') && $this->valueResolver->isValue(
                $configItemValue->value,
                'inputDateTime'
            )) {
                return true;
            }
        }

        return false;
    }
}
