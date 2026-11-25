<?php

declare(strict_types=1);

/**
 * گراف با Adjacency List — صف BFS با اشاره‌گر (بدون array_shift)
 *
 * array_shift روی آرایه بزرگ O(n) است؛ حلقه با اشاره‌گر head/tail
 * هر گام را O(1) می‌کند.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level data
 */
final class Graph
{
    /** @var array<string, array<string, int>> */
    private array $adj = [];

    public function addNode(string $node): void
    {
        $this->adj[$node] ??= [];
    }

    public function addEdge(string $from, string $to, int $weight = 1): void
    {
        $this->addNode($from);
        $this->addNode($to);
        $this->adj[$from][$to] = $weight;
    }

    /** @return array<string, int> */
    public function neighbors(string $node): array
    {
        return $this->adj[$node] ?? [];
    }

    /** @return array<int, string> ترتیب بازدید */
    public function bfs(string $start): array
    {
        $visited = [$start => true];
        $queue = [$start];
        $order = [];
        // صف با اشاره‌گر: O(n) → O(1) در هر گام
        for ($head = 0, $tail = 1; $head < $tail; $head++) {
            $node = $queue[$head];
            $order[] = $node;
            foreach ($this->neighbors($node) as $next => $_) {
                if (!isset($visited[$next])) {
                    $visited[$next] = true;
                    $queue[] = $next;
                    $tail++;
                }
            }
        }

        return $order;
    }

    /** @return array<int, string> */
    public function dfs(string $start): array
    {
        $visited = [];
        $order = [];
        $stack = [$start];
        while ($stack !== []) {
            $node = array_pop($stack);
            if (isset($visited[$node])) {
                continue;
            }
            $visited[$node] = true;
            $order[] = $node;
            foreach (array_keys($this->neighbors($node)) as $next) {
                if (!isset($visited[$next])) {
                    $stack[] = $next;
                }
            }
        }

        return $order;
    }

    /**
     * آیا گراف جهت‌دار چرخه دارد؟ (برای اعتبارسنجی DAG وابستگی‌ها)
     */
    public function hasCycle(): bool
    {
        $state = []; // 1=در بازدید، 2=تمام
        foreach (array_keys($this->adj) as $node) {
            if (($state[$node] ?? 0) === 0 && $this->cycleFrom($node, $state)) {
                return true;
            }
        }

        return false;
    }

    /** @param array<string, int> $state */
    private function cycleFrom(string $node, array &$state): bool
    {
        $state[$node] = 1;
        foreach (array_keys($this->neighbors($node)) as $next) {
            if (($state[$next] ?? 0) === 1) {
                return true; // یال به گره در حال بازدید = چرخه
            }
            if (($state[$next] ?? 0) === 0 && $this->cycleFrom($next, $state)) {
                return true;
            }
        }
        $state[$node] = 2;

        return false;
    }
}
