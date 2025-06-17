<?php
namespace Framework\Templating;

class TemplateEngine implements TemplateEngineInterface
{
    private string $templatesPath;

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = rtrim($templatesPath, '/\\');
    }

    public function render(string $template, array $data = []): string
    {
        $file = $this->templatesPath
            . DIRECTORY_SEPARATOR
            . $template;

        if (!is_file($file)) {
            throw new \RuntimeException("Template niet gevonden: {$file}");
        }

        $content = file_get_contents($file);

        // Vervang {{ variabele }} door waarden
        $content = preg_replace_callback(
            '/\{\{\s*(\w+)\s*\}\}/',
            function ($m) use ($data) {
                return htmlspecialchars((string)($data[$m[1]] ?? ''), ENT_QUOTES, 'UTF-8');
            },
            $content
        );

        return $content;
    }
}
