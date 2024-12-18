<?php

declare(strict_types=1);

namespace Src;


class Parser
{
    public function getJsonValueByValue(string $inputJson, string $key1, string $value1, string $key2)
    {
        $decodedJson = json_decode($inputJson, true);
        foreach ($decodedJson as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v["{$key1}"]) {
                    if ($v["{$key1}"] === $value1) {
                        return $v["{$key2}"];
                    }
                }
            }
        }
    }

    public function getEncodedPairValues(string $inputJson, string $key1, string $value1, string $key2)
    {
        $decodedJson = json_decode($inputJson, true);
        foreach ($decodedJson as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v["{$key1}"]) {
                    if ($v["{$key1}"] === $value1) {
                        $encodedString = "\"$key2\"" . ":" . "\"{$v["{$key2}"]}\"";
                        return $encodedString;
                    }
                }
            }
        }
    }

    public function getJsonValue(string $inputJson, string $key1)
    {
        $decodedJson = json_decode($inputJson, true);
        foreach ($decodedJson as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v["{$key1}"]) {
                        return $v["{$key1}"];
                }
            }
        }
    }
}
