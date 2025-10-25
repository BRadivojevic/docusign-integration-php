<?php
namespace App\Queue;

final class SqliteJobStore implements JobStoreInterface {
    private \PDO $pdo;
    public function __construct(string $path) {
        $dsn = 'sqlite:'.$path;
        $this->pdo = new \PDO($dsn);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS jobs (id TEXT PRIMARY KEY, payload TEXT, status TEXT, created_at TEXT, started_at TEXT, finished_at TEXT, last_error TEXT, meta TEXT)');
    }

    public function enqueue(array $job): string {
        $id = bin2hex(random_bytes(8));
        $stmt = $this->pdo->prepare('INSERT INTO jobs (id, payload, status, created_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$id, json_encode($job), 'queued', date('c')]);
        return $id;
    }

    public function nextQueued(): ?array {
        $stmt = $this->pdo->query("SELECT id, payload, status, created_at, started_at, finished_at, last_error FROM jobs WHERE status='queued' ORDER BY created_at ASC LIMIT 1");
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;
        $row['payload'] = json_decode($row['payload'], true);
        return $row;
    }

    public function markRunning(string $id): void {
        $stmt = $this->pdo->prepare('UPDATE jobs SET status="running", started_at=? WHERE id=?');
        $stmt->execute([date('c'), $id]);
    }

    public function markFinished(string $id, array $meta = []): void {
        $stmt = $this->pdo->prepare('UPDATE jobs SET status="finished", finished_at=?, meta=? WHERE id=?');
        $stmt->execute([date('c'), json_encode($meta), $id]);
    }

    public function markFailed(string $id, string $error): void {
        $stmt = $this->pdo->prepare('UPDATE jobs SET status="failed", finished_at=?, last_error=? WHERE id=?');
        $stmt->execute([date('c'), $error, $id]);
    }

    public function get(string $id): ?array {
        $stmt = $this->pdo->prepare('SELECT id, payload, status, created_at, started_at, finished_at, last_error, meta FROM jobs WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return null;
        $row['payload'] = json_decode($row['payload'], true);
        $row['meta'] = json_decode($row['meta'] ?? '{}', true);
        return $row;
    }
}
