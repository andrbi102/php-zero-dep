# ساختار داده گراف

Adjacency List (نه matrix) برای گراف‌های تنک: `array<string, array<string, int>>`.
پیاده‌سازی مرجع: `scaffolds/graph.php` با BFS اشاره‌گری (O(1) در هر گام)، DFS پشته‌ای، و hasCycle برای اعتبارسنجی DAG.
مرجع بیرونی: **mbsoft31/graph-core** (adjacency با O(1)، MIT) و
**abbadon1334/vegoia** (Leiden، centrality، shortest paths).

### ✅ Checklist

- [ ] adjacency list برای گراف تنک
- [ ] بدون array_shift در حلقه صف
- [ ] کلاس Graph از scaffolds برداشته می‌شود

### ❌ Anti-patterns
- matrix مجاورت برای گراف بزرگ تنک
- بازنویسی BFS با shift آرایه (O(n) هر گام)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
