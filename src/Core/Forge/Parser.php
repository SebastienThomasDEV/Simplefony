<?php

namespace Mvc\Framework\Core\Forge;

abstract class Parser
{
    static public function parse(string $document, array $vars): string
    {
        $document = self::parseAssets($document);
        $document = self::parseViews($document);
        $document = self::parseIfOperation($document, $vars);
        $document = self::parseForOperation($document, $vars);
        return self::parseVars($document, $vars);
    }

    static private function parseForOperation(string $document, array $vars): string
    {
        // need to match this pattern
        //<div @for="array as item">
        //    <div>{{ item }}</div>
        // </div>
        // even if line breaks are present

        return preg_replace_callback('/<([a-zA-Z0-9_]+)\s*@for="([a-zA-Z0-9_]+)\s+as\s+([a-zA-Z0-9_]+)"\s*>(.*?)<\/\1>/', function ($matches) use ($vars) {
            dd($matches);
            $html = '';
            foreach ($vars[$matches[2]] as $item) {
                $html .= str_replace($matches[3], $item, $matches[4]);
            }
            return $html;
        }, $document);
    }
    static private function parseIfOperation(string $document, array $vars): string
    {
        // need to match <div @if="var"></div> and any type html tag
        return preg_replace_callback('/<([a-zA-Z0-9_]+)\s*@if="([a-zA-Z0-9_]+)"\s*>(.*?)<\/\1>/', function ($matches) use ($vars) {
            if ($vars[$matches[2]]) {
                return str_replace('@if="'.$matches[2].'"', '', $matches[0]);
            } else {
                return '';
            }
        }, $document);
    }
    static private function parseVars(string $document, array $vars): string
    {
        return preg_replace_callback('/{{\s*([a-zA-Z0-9_]+)\s*}}/', function ($matches) use ($vars) {
            return $vars[$matches[1]] ?? '';
        }, $document);
    }

    static private function parseViews(string $document): string
    {
        return preg_replace_callback('/<view name="([a-zA-Z0-9_$\/]+)"><\/view>/', function ($matches) {
            return file_get_contents(rtrim(__DIR__ . '/../../Views/', '/') . '/' . $matches[1] . '.forge.html');
        }, $document);
    }

    static private function parseAssets(string $document): string
    {

        // <asset-loader load="./app.js"></asset-loader>
        return preg_replace_callback('/<asset-loader load="([a-zA-Z0-9_$\/\.]+).js"><\/asset-loader>/', function ($matches) {
            $minifiedCode = \JShrink\Minifier::minify(file_get_contents(__DIR__ . '/../../../public/' . $matches[1] . '.js'));
            if (!file_exists(__DIR__ . '/../../../public/' . $matches[1] . '.min.js')) {
                file_put_contents(__DIR__ . '/../../../public/' . $matches[1] . '.min.js', $minifiedCode);
            } else {
                if (file_get_contents(__DIR__ . '/../../../public/' . $matches[1] . '.min.js') !== $minifiedCode) {
                    file_put_contents(__DIR__ . '/../../../public/' . $matches[1] . '.min.js', $minifiedCode);
                }
            }
            return '<script src="./public/' . $matches[1] . '.min.js" type="module"></script>';
        }, $document);
    }


}