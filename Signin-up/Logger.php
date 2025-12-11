<?php
// Logger.php
// A simple static logger class to handle app logging

class Logger {
    private static string $logFile = "app.log"; // Log file name

    // Core function to write logs
    private static function write(string $level, string $message, array $context = []): void {
        $timestamp = date("Y-m-d H:i:s");
        $contextString = $context ? ' ' . json_encode($context) : '';
        $logLine = "[{$timestamp}] [{$level}] {$message}{$contextString}\n";
        @file_put_contents(self::$logFile, $logLine, FILE_APPEND | LOCK_EX);
    }

    // Public logging methods
    public static function debug(string $message, array $context = []): void {
        self::write("DEBUG", $message, $context);
    }

    public static function info(string $message, array $context = []): void {
        self::write("INFO", $message, $context);
    }

    public static function warn(string $message, array $context = []): void {
        self::write("WARN", $message, $context);
    }

    public static function error(string $message, array $context = []): void {
        self::write("ERROR", $message, $context);
    }
}
?>
