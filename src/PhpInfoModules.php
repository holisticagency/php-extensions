<?php

declare(strict_types=1);

/*
 * This file is part of holistic-agency/php-extensions.
 *
 * (c) JamesRezo <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HolisticAgency\PhpExtensions;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Process\PhpProcess;

/**
 * @internal
 *
 * @author JamesRezo <james@rezo.net>
 */
final class PhpInfoModules implements PhpInfoModulesInterface
{
    private const KEY = 'php.extensions';

    /** @var \ArrayObject<int,string> */
    private \ArrayObject $infoModules;

    private int $totalLines;

    public function __construct(private CacheInterface $cache)
    {
        /** @var \ArrayObject<int,string> $modules */
        $modules = $this->cache->get(self::KEY, new \ArrayObject([]));
        $this->infoModules = $modules;
        $this->totalLines = \count($this->infoModules);

        if (!$this->cache->has(self::KEY)) {
            $this->refresh();
            $this->cache->set(self::KEY, $this->infoModules);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    private function refresh(): self
    {
        // avg ~453.07ms (fpm)
        // avg ~438.72ms (cli)

        // avg ~0.07ms if file exists
        // if (\file_exists($this->cacheFilename) && !\unlink($this->cacheFilename)) {
        //     throw new \RuntimeException('Erreur d\'ecriture de cache');
        // }

        // avg >1000.00ms (~99.94% refresh (fpm opcache empty)
        // avg ~452.82ms (~99.94% refresh (fpm)
        // avg ~438.47ms (~99.94% refresh cli)
        $modules = '';
        $process = $process = new PhpProcess('<?php phpinfo(INFO_MODULES);');
        $process->run();
        if ($process->isSuccessful()) {
            $modules = $process->getOutput();
        }

        if ($modules) {
            // avg ~0.03ms
            $this->infoModules = new \ArrayObject(\explode("\n", $modules));
            $this->totalLines = \count($this->infoModules);
        }

        return $this;
    }

    public function search(string $extension, int $emptyLines): array
    {
        // avg 0.04ms with cached modules

        $directives = [];
        $found = false;
        $count = 0;
        $moduleLines = $this->infoModules->getIterator();
        $moduleLines->rewind();
        $moduleLines->next();
        while (
            \intval($moduleLines->key())
            && $moduleLines->key() < $this->totalLines
            && $count < $emptyLines
        ) {
            if ($extension == $moduleLines->current()) {
                $found = true;
                $count = 0;
            }
            if ($found) {
                $directives[] = $moduleLines->current();
            }
            if ($found && '' == $moduleLines->current()) {
                ++$count;
            }
            $moduleLines->next();
        }

        return $directives;
    }
}
