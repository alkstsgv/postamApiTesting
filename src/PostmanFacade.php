<?php

declare(strict_types=1);

namespace Src;

require __DIR__ . '../../vendor/autoload.php';
use Src\PostmanConnector;

class PostmanFacade
{
    public object $postmanConnector;
    public string $body;
    public function __construct(
        $postmanConnector = null,
        $body = null
    ) {
        $this->postmanConnector = new PostmanConnector();
    }
    public function createRequestBody(string $collectionName): array
    {
        if ($collectionName == "sf") {
            $returnedArray = [
                "collection" => [
                    "info" => [
                        "name" => $collectionName,
                        "schema" => $this->postmanConnector->schema,
                    ],
                    "item" => [
                        [
                            "name" => "FunctionalTesting",
                            "item" => [
                                [
                                "name" => "TestNeedKeys",
                                "item" => [
                                    [
                                    "name" => "key:" . $collectionName,
                                    "item" => [
                                            [
                                                "name" => "With_key:" . $collectionName,
                                                "item" => []
                                            ],
                                            [
                                                "name" => "Wout_key:" . $collectionName,
                                                "item" => []
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                            ]
                        ]
                    ]
                ]
            ];
        } elseif ($collectionName == "sfsd.id") {
            $returnedArray = [
                "collection" => [
                    "info" => [
                        "name" => $collectionName,
                        "schema" => $this->postmanConnector->schema,
                    ],
                    "item" => [
                        [
                            "name" => "FunctionalTesting",
                            "item" => [
                                "name" => "key:" . $collectionName,
                                "item" => [
                                    "name" => "With_key:" . $collectionName,
                                    "item" => []
                                ],
                                [
                                    "name" => "Wout_key:" . $collectionName,
                                    "item" => []
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $returnedArray;
    }
    public function makeCollectionToTestApi()
    {
        $this->postmanConnector->createClient();
        $this->postmanConnector->createNewCollection("Copy a Workspace5", "name", "Working", "id", $this->body);
    }
}

$pf = new PostmanFacade();
$pf->createRequestBody("sf");
var_dump($pf->createRequestBody("sf"));
// print_r(json_encode($pf->createRequestBody("sf")));
// $pf->makeCollectionToTestApi();
// $json = '';
// print_r($json);
