<?php

declare(strict_types=1);

/**
 * مثال ۷ — گراف با BFS اشاره‌گری و بررسی چرخه (DAG)
 * اجرا: php examples/07-graph-bfs-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.1
 */
require __DIR__ . '/../scaffolds/graph.php';

$graph = new Graph();
$graph->addEdge('requirements', 'adr');
$graph->addEdge('adr', 'autoloader');
$graph->addEdge('adr', 'router');
$graph->addEdge('autoloader', 'app');
$graph->addEdge('router', 'app');

echo "BFS from requirements: ", implode(' -> ', $graph->bfs('requirements')), "\n";
echo "DAG acyclic: ", $graph->hasCycle() ? 'FAIL (cycle!)' : 'OK', "\n";

$graph->addEdge('app', 'adr'); // یال برگشتی = چرخه
echo "after back-edge hasCycle: ", $graph->hasCycle() ? 'OK (detected)' : 'FAIL', "\n";
