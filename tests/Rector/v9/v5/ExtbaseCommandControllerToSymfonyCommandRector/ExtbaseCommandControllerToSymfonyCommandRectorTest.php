<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;

final class ExtbaseCommandControllerToSymfonyCommandRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
        $this->assertSame(3, $this->removedAndAddedFilesCollector->getAddedFileCount());

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $commandsFixture = $this->getService(FileInfoFactory::class)->createFileInfoFromPath(
            __DIR__ . '/Fixture/Expected/Configuration/Commands.php.inc'
        );

        // Assert that commands file is added
        $addedCommandsFile = $addedFilesWithContent[2];
        $addedCommandFile = $addedFilesWithContent[0];
        $this->assertStringContainsString('Commands.php', $addedCommandsFile->getFilePath());
        $this->assertStringContainsString('$output->writeln(\'foobar\');', $addedCommandFile->getFileContent());
        $this->assertSame($commandsFixture->getContents(), $addedCommandsFile->getFileContent());
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/Classes/Controller/Command');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
