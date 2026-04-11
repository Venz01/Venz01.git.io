<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Database Lock Trait
 * 
 * Provides database-agnostic advisory locking that works with both MySQL and PostgreSQL.
 * Uses GET_LOCK/RELEASE_LOCK for MySQL and pg_advisory_lock/pg_advisory_unlock for PostgreSQL.
 */
trait DatabaseLock
{
    /**
     * Acquire an advisory lock
     * 
     * @param string $key The lock key/name
     * @param int $timeout Timeout in seconds (MySQL only, PostgreSQL locks are indefinite)
     * @return bool True if lock acquired successfully
     */
    protected function acquireLock(string $key, int $timeout = 5): bool
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL: GET_LOCK returns 1 if lock acquired, 0 if timeout, NULL if error
            $result = DB::select("SELECT GET_LOCK(?, ?) as locked", [$key, $timeout]);
            return isset($result[0]->locked) && $result[0]->locked == 1;
        } 
        elseif ($driver === 'pgsql') {
            // PostgreSQL: Use pg_advisory_lock with a hash of the key
            // Convert string key to bigint hash for PostgreSQL
            $lockId = $this->stringToLockId($key);
            
            // Try to acquire lock with timeout simulation
            // pg_try_advisory_lock returns true if lock acquired, false otherwise
            $result = DB::select("SELECT pg_try_advisory_lock(?) as locked", [$lockId]);
            return isset($result[0]->locked) && $result[0]->locked === true;
        }

        // Unsupported driver - log warning and return true to not block the process
        \Log::warning("Unsupported database driver for locking: {$driver}");
        return true;
    }

    /**
     * Release an advisory lock
     * 
     * @param string $key The lock key/name
     * @return bool True if lock released successfully
     */
    protected function releaseLock(string $key): bool
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL: RELEASE_LOCK returns 1 if released, 0 if not held, NULL if error
            $result = DB::select("SELECT RELEASE_LOCK(?) as released", [$key]);
            return isset($result[0]->released) && $result[0]->released == 1;
        } 
        elseif ($driver === 'pgsql') {
            // PostgreSQL: pg_advisory_unlock
            $lockId = $this->stringToLockId($key);
            $result = DB::select("SELECT pg_advisory_unlock(?) as released", [$lockId]);
            return isset($result[0]->released) && $result[0]->released === true;
        }

        // Unsupported driver
        \Log::warning("Unsupported database driver for lock release: {$driver}");
        return true;
    }

    /**
     * Convert a string key to a bigint lock ID for PostgreSQL
     * 
     * PostgreSQL advisory locks require a bigint (or two int4s).
     * We use CRC32 to convert the string to an integer.
     * 
     * @param string $key
     * @return int
     */
    private function stringToLockId(string $key): int
    {
        // Use CRC32 to generate a consistent integer from the string
        // CRC32 returns an unsigned 32-bit integer, which fits in PostgreSQL's int4/bigint
        return crc32($key);
    }
}