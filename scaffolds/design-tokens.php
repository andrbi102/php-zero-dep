<?php

declare(strict_types=1);

/**
 * معماری توکن طراحی سه‌لایه + صدور دوگانه — الگوبرداری‌شده از
 * nextlevelbuilder/ui-ux-pro-max-skill (MIT)
 *
 * primitive → semantic → component. صدور در سه فرمت از یک resolver واحد:
 *   'php'  → ثابت‌های PHP (define)
 *   'json' → W3C Design Tokens Community Group ($value/$type)
 *   'css'  → :root custom properties
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level frontend
 * @pattern-source ui-ux-pro-max-skill (MIT) — .claude/skills/design-system/references/token-architecture.md
 */
final class DesignTokens
{
    /** لایهٔ ۱ — primitive (خام، بدون معنی). */
    private const PRIMITIVES = [
        'color' => [
            'blue-500' => '#2563eb', 'blue-600' => '#1d4ed8', 'blue-700' => '#1e40af',
            'gray-50' => '#f9fafb', 'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb',
            'gray-500' => '#6b7280', 'gray-700' => '#374151', 'gray-900' => '#111827',
            'red-500' => '#ef4444', 'red-600' => '#dc2626',
            'green-500' => '#22c55e', 'green-600' => '#16a34a',
            'amber-500' => '#f59e0b',
            'white' => '#ffffff',
        ],
        'spacing' => ['0' => '0', '1' => '0.25rem', '2' => '0.5rem', '3' => '0.75rem', '4' => '1rem', '6' => '1.5rem', '8' => '2rem', '12' => '3rem', '16' => '4rem'],
        'radius' => ['none' => '0', 'sm' => '0.25rem', 'md' => '0.5rem', 'lg' => '1rem', 'full' => '9999px'],
        'font' => ['sans' => 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif', 'mono' => 'ui-monospace, "Cascadia Code", monospace'],
        'weight' => ['normal' => '400', 'medium' => '500', 'semibold' => '600', 'bold' => '700'],
        'shadow' => ['sm' => '0 1px 2px rgba(0,0,0,0.05)', 'md' => '0 4px 6px rgba(0,0,0,0.1)'],
    ];

    /** لایهٔ ۲ — semantic (نقش، مستقل از رنگ خام). */
    private const SEMANTIC = [
        'color' => [
            'primary' => '{color.blue-600}', 'on-primary' => '{color.white}',
            'secondary' => '{color.gray-500}', 'accent' => '{color.blue-500}',
            'background' => '{color.gray-50}', 'foreground' => '{color.gray-900}',
            'card' => '{color.white}', 'muted' => '{color.gray-100}',
            'border' => '{color.gray-200}', 'destructive' => '{color.red-600}',
            'success' => '{color.green-600}', 'warning' => '{color.amber-500}',
            'ring' => '{color.blue-500}',
        ],
        'font' => ['heading' => '{font.sans}', 'body' => '{font.sans}', 'code' => '{font.mono}'],
        'weight' => ['heading' => '{weight.semibold}', 'body' => '{weight.normal}'],
        'radius' => ['control' => '{radius.md}', 'card' => '{radius.lg}', 'pill' => '{radius.full}'],
        'shadow' => ['control' => '{shadow.sm}', 'card' => '{shadow.md}'],
        'spacing' => ['page' => '{spacing.8}', 'section' => '{spacing.12}', 'gap' => '{spacing.4}'],
    ];

    /** لایهٔ ۳ — component (قرارداد مؤلفه). */
    private const COMPONENT = [
        'button' => ['padding' => '{spacing.2} {spacing.4}', 'radius' => '{radius.control}', 'bg' => '{color.primary}', 'fg' => '{color.on-primary}', 'shadow' => '{shadow.control}'],
        'card' => ['padding' => '{spacing.6}', 'radius' => '{radius.card}', 'bg' => '{color.card}', 'border' => '1px solid {color.border}', 'shadow' => '{shadow.card}'],
        'input' => ['padding' => '{spacing.2} {spacing.3}', 'radius' => '{radius.control}', 'border' => '1px solid {color.border}', 'ring' => '{color.ring}'],
    ];

    /** یک توکن semantic/component را تا primitive خام resolve می‌کند. */
    public function resolve(string $ref): string
    {
        $seen = [];
        while (preg_match('/\{([\w.-]+)\}/', $ref, $m)) {
            if (isset($seen[$m[1]])) {
                break; // جلوگیری از حلقه مرجع
            }
            $seen[$m[1]] = true;
            $val = $this->lookup($m[1]);
            if ($val === null) {
                break; // مرجع نامحلول → رها کن
            }
            $ref = str_replace('{' . $m[1] . '}', $val, $ref);
        }
        return $ref;
    }

    private function lookup(string $name): ?string
    {
        // مرجع به‌فرمت {category.key} (مثلاً color.blue-600) یا bare name.
        $parts = explode('.', $name, 2);
        if (count($parts) === 2) {
            [$cat, $key] = $parts;
            if (isset(self::PRIMITIVES[$cat][$key])) {
                return self::PRIMITIVES[$cat][$key];
            }
            if (isset(self::SEMANTIC[$cat][$key])) {
                return $this->resolve(self::SEMANTIC[$cat][$key]);
            }
            // مرجع component: button.bg → COMPONENT['button']['bg']
            if (isset(self::COMPONENT[$cat][$key])) {
                return $this->resolve(self::COMPONENT[$cat][$key]);
            }
            return null;
        }
        // bare name — در همهٔ لایه‌ها بگرد
        foreach (self::PRIMITIVES as $map) {
            if (isset($map[$name])) {
                return $map[$name];
            }
        }
        return null;
    }

    /**
     * صدور در سه فرمت از یک resolver واحد (ui-ux-pro-max design_system.py).
     * @param 'php'|'json'|'css' $format
     */
    public function export(string $format): string
    {
        return match ($format) {
            'php' => $this->exportPhp(),
            'json' => $this->exportJson(),
            'css' => $this->exportCss(),
            default => throw new InvalidArgumentException("format must be php|json|css, got {$format}"),
        };
    }

    private function exportPhp(): string
    {
        $out = "<?php\n\ndeclare(strict_types=1);\n\n// Generated design tokens — php-zero-dep v0.0.2\n";
        foreach (self::SEMANTIC as $cat => $map) {
            foreach ($map as $name => $ref) {
                $const = strtoupper(preg_replace('/[^a-z0-9]+/i', '_', $cat . '_' . $name) ?? '');
                $val = $this->resolve($ref);
                $out .= "define('TOKEN_{$const}', " . var_export($val, true) . ");\n";
            }
        }
        foreach (self::COMPONENT as $comp => $props) {
            foreach ($props as $name => $ref) {
                $const = strtoupper(preg_replace('/[^a-z0-9]+/i', '_', 'component_' . $comp . '_' . $name) ?? '');
                $val = $this->resolve($ref);
                $out .= "define('TOKEN_{$const}', " . var_export($val, true) . ");\n";
            }
        }
        return $out;
    }

    /** W3C Design Tokens Community Group JSON ($value/$type). */
    private function exportJson(): string
    {
        $tokens = [];
        foreach (self::PRIMITIVES as $cat => $map) {
            foreach ($map as $name => $val) {
                $tokens[$cat][$name] = ['$value' => $val, '$type' => $cat === 'color' ? 'color' : 'dimension'];
            }
        }
        foreach (self::SEMANTIC as $cat => $map) {
            foreach ($map as $name => $ref) {
                $tokens['semantic'][$cat . '-' . $name] = ['$value' => $this->resolve($ref), '$type' => $cat === 'color' ? 'color' : 'dimension'];
            }
        }
        foreach (self::COMPONENT as $comp => $props) {
            foreach ($props as $name => $ref) {
                $rv = $this->resolve($ref);
                $type = str_starts_with($rv, '#') ? 'color' : (preg_match('/^[\d.]+(rem|px|em|%)$/', $rv) ? 'dimension' : 'string');
                $tokens['component'][$comp . '-' . $name] = ['$value' => $rv, '$type' => $type];
            }
        }
        return json_encode($tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function exportCss(): string
    {
        $out = ":root {\n";
        foreach (self::SEMANTIC as $cat => $map) {
            foreach ($map as $name => $ref) {
                $var = '--' . str_replace('_', '-', $cat . '-' . $name);
                $out .= "  {$var}: " . $this->resolve($ref) . ";\n";
            }
        }
        foreach (self::COMPONENT as $comp => $props) {
            foreach ($props as $name => $ref) {
                $var = '--component-' . $comp . '-' . $name;
                $out .= "  {$var}: " . $this->resolve($ref) . ";\n";
            }
        }
        $out .= "}\n";
        return $out;
    }
}
