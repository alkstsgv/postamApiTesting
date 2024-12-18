<?php

declare(strict_types=1);

namespace Src;

// require __DIR__ . '../../vendor/autoload.php';

class Writer
{
    public $headers;
    public $query;
    public $response;
    public string $apiKey;
    public string $apiToken;
    public $client;
    public $body;
    public $makeRequest;
    public string $boardId = "UlUMkkwP";
    private string $filename;
    public function __construct(
        $headers = null,
        $query = null,
        $response = null,
        $apiKey = null,
        $apiToken = null,
        $client = null,
        $body = null,
        $boardId = null,
        $filename = null,
        $makeRequest = null
    ) {
    }

    public function writeToFile(string $mode, $inputString = null, string $extension = null)
    {
        $dir = "tests/";
        if ($mode === "create") {
            touch($dir . date("Y-m-d H:i:s") . "{$extension}");
            foreach (scandir($dir) as $key => $value) {
                $this->filename = $dir . $value;
            }
            echo "Новый файл с ответом успешно создан!" . PHP_EOL;
        } elseif ($mode === "reuseLastFile") {
            foreach (scandir($dir) as $key => $value) {
                $this->filename = $dir . $value;
            }
        } elseif ($mode === "deleteAllFiles") {
            foreach (scandir($dir) as $key => $value) {
                if (!is_dir($value)) {
                    unlink($dir . $value);
                }

            }
            exit;
        } else {
            echo "Вы ввели неверное значение, выход";
            exit(0);
        }
        // print_r(scandir( '../tests'));

        // foreach (scandir($dir) as $key => $value) {
        //     $this->filename = $dir . $value;
        // }

        // print_r($this->filename);
        // var_dump(is_writable($this->filename));
        if (is_writable($this->filename)) {
            if (!$fp = fopen($this->filename, "c")) {
                echo "Не могу открыть файл ($this->filename)" . PHP_EOL;
                exit(0);
            }
            if (fwrite($fp, $inputString) === false) {
                echo "Не могу произвести запись в файл ($this->filename)" . PHP_EOL;
                exit(0);
            }
            echo "Ура! Записали ответ в файл $this->filename" . PHP_EOL;

            fclose($fp);
        } else {
            echo "Файл $this->filename недоступен для записи" . PHP_EOL;
            exit(0);
        }
    }

    public function getCollectionScheme() {
        
    }
}



// $write = new Writer();
// // $write->writeToFile("create", "asdfsadfsa");
// $write->writeToFile(mode: "deleteAllFiles");
