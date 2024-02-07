<?php

namespace Mvc\Framework\Core\Forge;

abstract class Parser
{
    static public function parse(string $document, array $vars): string
    {
        $document = self::parseAssets($document);
        $document = self::parseViews($document);
        $document = self::parseVars($document, $vars);
        return $document;
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
            return '<script src="./public/' . $matches[1] . '.min.js" defer></script>';
        }, $document);
    }


}