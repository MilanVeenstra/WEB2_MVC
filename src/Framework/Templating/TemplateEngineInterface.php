<?php
namespace Framework\Templating;

interface TemplateEngineInterface
{
    /**
     * Render een template met de gegeven data.
     *
     * @param string $template Bestandsnaam van de template (bijv. 'index.tpl')
     * @param array  $data     Associatieve array met variabelen voor de view
     * @return string          De gegenereerde HTML
     */
    public function render(string $template, array $data = []): string;
}
