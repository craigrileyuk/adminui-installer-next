<?php

namespace AdminUI\AdminUIInstaller\Services;

use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Composer;
use Illuminate\Support\ProcessUtils;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Exception\ProcessSignaledException;

class ComposerService
{
    public $composer;

    public function __construct()
    {
        $this->composer = config('adminui-installer.root') . '/lib/composer.phar';
    }

    public function run(string|array $command)
    {
        $output = collect([]);
        $stack = collect([$this->phpBinary(), $this->composer]);
        $command = Arr::wrap($command);
        $stack->push(...$command);
        $stack = $stack->flatten();

        $process = Process::fromShellCommandline($stack->implode(" "), base_path(), null, null, 300);

        try {
            $process->run(fn ($type, $line) => $output->push($line));
            return $output;
        } catch (ProcessSignaledException $e) {
            $output->push($e->getMessage());
            return $output;
        }
    }

    protected function phpBinary()
    {
        return ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    /**
     * Get a new Symfony process instance.
     *
     * @param  array  $command
     * @param  array  $env
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command, array $env = [])
    {
        return (new Process($command, base_path(), $env))->setTimeout(null);
    }
}
