<?php

namespace App\Infrastructure\Services;

class Logger
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }
    }

    public function info(string $message): void
    {
        $this->write('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }

    private function write(string $level, string $message): void
    {
        $line = sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $level, $message);
        file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }
}
