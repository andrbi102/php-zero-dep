<?php

declare(strict_types=1);

/**
 * tools/hello.php — هر فایل یک Tool مستقل
 *
 * خروجی متنی برای LLM است؛ htmlspecialchars فقط هنگام رندر HTML به کار رود.
 *
 * @package PhpZeroDep\Scaffolds\Tools
 * @version 0.0.2
 * @security-level tool
 */
return [
    'name' => 'hello',
    'description' => 'Say hello',
    'input' => ['name' => 'string'],
    'execute' => static function (array $args): string {
        $name = $args['name'] ?? null;
        if (!is_string($name) || $name === '') {
            throw new InvalidArgumentException("Tool 'hello': 'name' must be a non-empty string");
        }

        return 'Hello, ' . $name;
    },
];
