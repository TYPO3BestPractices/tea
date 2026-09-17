<?php

declare(strict_types=1);

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/../../.Build/vendor/autoload.php';

if (PHP_SAPI !== 'cli') {
    die('Script must be called from command line.' . chr(10));
}

final readonly class XliffNormalizer
{
    private const EXIT_OK = 0;
    private const EXIT_NEEDS_FIX = 1;
    private const EXIT_ERROR = 2;
    private const pathToXliffFiles = 'Resources/Private/Language';

    public function execute(array $argv = []): int
    {
        $script = $argv[0] ?? 'xliffNormalizer.php';
        $mode = 'fix';
        $isDryRun = false;
        $isVerbose = false;
        $defaultRootDir = __DIR__ . '/../../' . self::pathToXliffFiles;
        $rootDir = realpath($defaultRootDir) ?: $defaultRootDir;

        $argc = count($argv);
        for ($i = 1; $i < $argc; $i++) {
            $arg = $argv[$i];

            switch ($arg) {
                case '-n':
                case '--dry-run':
                    $isDryRun = true;
                    break;

                case '--root':
                    $i++;
                    if ($i >= $argc) {
                        $this->printUsage($script, '<error>Error: --root DIR requires a path</error>');
                        return self::EXIT_ERROR;
                    }
                    $rootDir = $argv[$i];
                    break;

                case '-v':
                case '--verbose':
                    $isVerbose = true;
                    break;

                case '-h':
                case '--help':
                    $this->printUsage($script);
                    return self::EXIT_OK;

                default:
                    $this->printUsage($script, sprintf('<error>Unknown option: %s</error>', $arg));
                    return self::EXIT_ERROR;
            }
        }

        if ($isDryRun) {
            $mode = 'check';
        }

        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));

        if (!is_dir($rootDir)) {
            $output->writeln(sprintf('<error>Root directory does not exist: %s</error>', $rootDir));
            return self::EXIT_ERROR;
        }

        $filesToProcess = $this->findXliff($rootDir);
        if (count($filesToProcess) === 0) {
            $output->writeln(sprintf('No XLF files found in %s', $rootDir));
            return self::EXIT_OK;
        }

        $output->writeln(sprintf('Found %d XLF files in %s', count($filesToProcess), $rootDir));
        $output->writeln(sprintf('Mode: %s', $mode));
        $output->writeln('');

        $filesOk = 0;
        $filesNeedFix = 0;
        $filesFixed = 0;
        $filesError = 0;

        foreach ($filesToProcess as $fileInfo) {
            assert($fileInfo instanceof \SplFileInfo);
            $filePath = $fileInfo->getRealPath() ?: $fileInfo->getPathname();
            $result = $this->processFile($filePath, $mode);

            if ($result['status'] === 'ok') {
                $filesOk++;
            } elseif ($result['status'] === 'needs-fix') {
                $filesNeedFix++;
            } elseif ($result['status'] === 'fixed') {
                $filesFixed++;
            } elseif ($result['status'] === 'error') {
                $filesError++;
            }

            if ($isVerbose || $result['status'] === 'error' || $result['status'] === 'fixed') {
                $this->printPerFileLine($output, $result, $mode);
            }
        }

        $output->writeln('');
        $output->writeln('Summary:');
        if ($mode === 'check') {
            $output->writeln(sprintf('  OK: %d', $filesOk));
            $output->writeln(sprintf('  Need fixing: %d', $filesNeedFix));
            $output->writeln(sprintf('  Errors: %d', $filesError));
            if (!$isVerbose && $filesNeedFix > 0) {
                $output->writeln('');
                $output->writeln('Rerun with -v/--verbose to see which files need fixing.');
            }
        } else {
            $output->writeln(sprintf('  Fixed: %d', $filesFixed));
            $output->writeln(sprintf('  Already OK: %d', $filesOk));
            $output->writeln(sprintf('  Errors: %d', $filesError));
        }
        $output->writeln('');

        if ($filesError > 0) {
            $output->writeln('<error>Some files could not be processed (invalid XML or I/O error).</error>');
            return self::EXIT_ERROR;
        }

        if ($mode === 'check') {
            if ($filesNeedFix > 0) {
                $output->writeln('<comment>Run without --dry-run / -n to fix these files.</comment>');
                return self::EXIT_NEEDS_FIX;
            }
            $output->writeln('<info>All files have correct formatting!</info>');
            return self::EXIT_OK;
        }

        $output->writeln('<info>Done!</info>');
        return self::EXIT_OK;
    }

    private function printUsage(string $script, ?string $error = null): void
    {
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));

        if ($error !== null) {
            $output->writeln($error);
            $output->writeln('');
        }

        $output->writeln(sprintf('Usage: %s [--dry-run|-n] [--root DIR] [-v|--verbose]', $script));
        $output->writeln('');
        $output->writeln('Normalize formatting of XLF files using PHP\'s DOMDocument pretty printing.');
        $output->writeln('By default, the script FIXES files in-place. Use --dry-run/-n to only check.');
        $output->writeln('');
        $output->writeln('Options:');
        $output->writeln('    -n, --dry-run     Do not modify files; only report which need changes');
        $output->writeln('    --root DIR        Root directory to search for XLF files');
        $output->writeln('                      (default: ' . self::pathToXliffFiles . ')');
        $output->writeln('    -v, --verbose     Show the status of each processed file');
        $output->writeln('    -h, --help        Show this help message');
        $output->writeln('');
        $output->writeln('Exit codes:');
        $output->writeln('    0 - Success (dry-run: all files OK; normal mode: files fixed successfully)');
        $output->writeln('    1 - Files need fixing (dry-run only)');
        $output->writeln('    2 - Error (invalid arguments or processing error)');
    }

    /**
     * @return Finder|\SplFileInfo[]
     */
    private function findXliff(string $rootDir): Finder
    {
        $finder = new Finder();
        return $finder
            ->files()
            ->in($rootDir)
            ->name('*.xlf');
    }

    /**
     * Processes a single XLF file in check or fix mode.
     *
     * @return array{
     *     file: string,
     *     status: 'ok'|'needs-fix'|'fixed'|'error',
     *     message?: string
     * }
     */
    private function processFile(string $filePath, string $mode): array
    {
        $original = @file_get_contents($filePath);
        if ($original === false) {
            return ['file' => $filePath, 'status' => 'error', 'message' => 'Cannot read file'];
        }

        $formatted = null;
        $errorMessage = null;
        if (!$this->formatWithDomDocument($filePath, $formatted, $errorMessage)) {
            return ['file' => $filePath, 'status' => 'error', 'message' => 'DOMDocument failed: ' . $errorMessage];
        }

        if ($formatted === $original) {
            return ['file' => $filePath, 'status' => 'ok'];
        }

        if ($mode === 'check') {
            return ['file' => $filePath, 'status' => 'needs-fix'];
        }

        if (@file_put_contents($filePath, $formatted) === false) {
            return ['file' => $filePath, 'status' => 'error', 'message' => 'Failed to write file'];
        }

        return ['file' => $filePath, 'status' => 'fixed'];
    }

    /**
     * Formats an XLF file with DOMDocument.
     *
     * @param string|null $formattedXml Output formatted XML if successful.
     * @param string|null $errorMessage Error details (if any).
     * @return bool true on success, false on DOM load/format error.
     */
    private function formatWithDomDocument(string $filePath, ?string &$formattedXml, ?string &$errorMessage): bool
    {
        $formattedXml = null;
        $errorMessage = null;

        if (!is_readable($filePath)) {
            $errorMessage = 'File not readable';
            return false;
        }

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $isLoaded = $dom->load($filePath);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        if (!$isLoaded) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = trim($error->message) . ' (line ' . $error->line . ')';
            }
            $errorMessage = $messages !== [] ? implode('; ', $messages) : 'Unknown XML error';
            return false;
        }

        $xml = $dom->saveXML();
        if ($xml === false) {
            $errorMessage = 'DOMDocument::saveXML() failed';
            return false;
        }

        $formattedXml = $xml;
        return true;
    }

    /**
     * @param array{file: string, status: string, message?: string} $result
     */
    private function printPerFileLine(ConsoleOutput $output, array $result, string $mode): void
    {
        $file = $result['file'];

        switch ($result['status']) {
            case 'ok':
                if ($mode === 'fix') {
                    $output->writeln(sprintf('<info>OK:</info> %s (no changes needed)', $file));
                } else {
                    $output->writeln(sprintf('<info>OK:</info> %s', $file));
                }
                break;
            case 'needs-fix':
                $output->writeln(sprintf('<comment>NEEDS FIX:</comment> %s', $file));
                break;
            case 'fixed':
                $output->writeln(sprintf('<info>FIXED:</info> %s', $file));
                break;
            case 'error':
                $output->writeln(sprintf('<error>ERROR:</error> %s (%s)', $file, $result['message'] ?? 'Unknown error'));
                break;
        }
    }
}

exit((new XliffNormalizer())->execute($argv));
