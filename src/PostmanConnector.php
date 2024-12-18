<?php

declare(strict_types=1);

namespace Src;

// require __DIR__ . '../../vendor/autoload.php';
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use Src\Writer;
use Src\Parser;

class PostmanConnector
{
    public object $client;
    public object $request;
    public object $response;
    public array $body;
    public array $headers;
    public string $schema = "https://schema.getpostman.com/json/collection/v2.1.0/collection.json";
    private string $postmanApiKey = "PMAK-675d1389730e2800013d74c5-9170e77729ca91969472bb97c480c6f07c";
    public string $base_uri = "https://api.getpostman.com";
    public object $parser;

    public function __construct(
        $client = null,
        $request = null,
        $postmanApiKey = null,
        $response = null,
        $body = null,
        $headers = null,
        $base_uri = null,
        $schema = null,
        $parser = null
    ) {
        $this->parser = new Parser();
    }

    public function createClient()
    {
        $this->client = new Client();
    }
    /**
     * Summary of getWorkspaces
     * @return object function returns object with user's workspaces: id, name, type, visibility, createdBy
     */
    public function getWorkspaces(array $headers = null)
    {
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/workspaces",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getWorkspace
     * getWorkspace("name", "Working", "id")
     * @param string $workspaceKey property of json's workspace
     * @param string $workspaceValue the name of workspace
     * @param string $workspaceKey2 the property by which we will get value
     * @return object function returns object with current workspace's properties: id, name, type, description, visibility, createdBy, updatedBy, createdAt, updatedAt, collections{id, name, uid}
     */
    public function getWorkspace(string $workspaceKey, string $workspaceValue, string $workspaceKey2, array $headers = null)
    {
        $workspaceId = $this->parser->getJsonValueByValue($this->getResponsedBody(
            $this->getWorkspaces()
        ), $workspaceKey, $workspaceValue, $workspaceKey2);
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/workspaces/{$workspaceId}",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getEnvironments
     * @param array $headers headers with request
     * @return object function returns object with envs' of acc and their props: id, name, createdAt, updatedAt, owner, uid, isPublic
     */
    public function getEnvironments(array $headers = null)
    {
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/environments",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getEnvironment
     * getEnvironment("name", "Postman API", "id")
     * @param string $envKey property of json's environment
     * @param string $envValue the name of environment
     * @param string $envKey2 the property by which we will get value
     * @param array $headers headers with request
     * @return object function returns object with selected environment's properties: uid, id, name, owner, createdAt, updatedAt, values, isPublic
     */
    public function getEnvironment(string $envKey, string $envValue, string $envKey2, array $headers = null)
    {
        $envId = $this->parser->getJsonValueByValue($this->getResponsedBody(
            $this->getEnvironments()
        ), $envKey, $envValue, $envKey2);
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/environments/{$envId}",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of addVariableInEnvironment
     * addVariableInEnvironment("test1", "name", "Postman API", "id")
     * @param $varName the new name of variable in selected environment
     * @param string $envKey property of json's environment
     * @param string $envValue the name of environment
     * @param string $envKey2 the property by which we will get value
     * @param array $headers headers with request
     * @return object function returns object of one env with next properties: id, name, uid
     */
    public function addVariableInEnvironment(string $varName, string $envKey, string $envValue, string $envKey2, array $body =  null, array $headers = null)
    {
        $envId = $this->parser->getJsonValueByValue($this->getResponsedBody(
            $this->getEnvironments()
        ), $envKey, $envValue, $envKey2);
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}", "Content-Type" => "application/json"];
        $this->body = ["collection" => ["info" => ["name" => "{$varName}", "schema" => "{$this->schema}"], "item" => []]];
        $this->request = new Request(
            "PUT",
            "{$this->base_uri}/collections/{$envId}",
            $this->headers,
            json_encode($this->body)
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getCollections
     * @return object function returns all collections which belongs to one workspace: id, name, owner, createdAt, updatedAt, uid, isPublic
     */
    public function getCollections(array $headers = null)
    {
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/collections",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getCollection
     * getCollection("name", "Copy a Workspace1","id")
     * @param string $collectionKey the property of collection by which we will search
     * @param string $collectionValue the name of collection by which we will search
     * @param string $collectionKey2 the property of collection by we will get value
     * @param array $headers headers with request
     * @return object function returns object with all info about one collection belongs to one workspace: postman_id, name, schema, updatedAt, createdAt, lastUpdatedBy, uid, item
     */
    public function getCollection(string $collectionKey, string $collectionValue, string $collectionKey2, array $headers = null)
    {
        $collectionId = $this->parser->getJsonValueByValue(
            $this->getResponsedBody(
                $this->getCollections()
            ),
            $collectionKey,
            $collectionValue,
            $collectionKey2
        );
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "GET",
            "{$this->base_uri}/collections/{$collectionId}",
            $this->headers
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of createNewCollection
     * createNewCollection("Copy a Workspace1", "name", "Working", "id");
     * @param string $collectionName Name of a new collection in Postman
     * @param string $workspaceKey property of json's workspace
     * @param string $workspaceValue the name of workspace
     * @param string $workspaceKey2 the property by which we will get value
     * @param array $body body in request
     * @param array $headers headers with request
     * @return object Function returns object with info about new collection: id, name, uid
     */
    public function createNewCollection(string $collectionName, string $workspaceKey, string $workspaceValue, string $workspaceKey2, string $reqBody =  null, array $reqHeaders = null)
    {
        $workspaceId = $this->parser->getJsonValueByValue($this->getResponsedBody(
            $this->getWorkspaces()
        ), $workspaceKey, $workspaceValue, $workspaceKey2);
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}", "Content-Type" => "application/json"];
        // $this->body = ["collection" => ["info" => ["name" => "{$collectionName}", "schema" => "{$this->schema}"], "item" => []]];
        $this->request = new Request(
            "POST",
            "{$this->base_uri}/collections?workspace={$workspaceId}",
            $this->headers,
            $reqBody
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of deleteCollection
     * deleteCollection("name", "Copy a Workspace1","id")
     * @param string $collectionKey the property of collection by which we will search
     * @param string $collectionValue the name of collection by which we will search
     * @param string $collectionKey2 the property of collection by we will get value
     * @param array $headers headers with request
     * @return object the function returns object with deleted collection's properties: id, uid
     */
    public function deleteCollection(string $collectionKey, string $collectionValue, string $collectionKey2, array $headers = null)
    {
        $collectionId = $this->parser->getJsonValueByValue(
            $this->getResponsedBody(
                $this->getCollections()
            ),
            $collectionKey,
            $collectionValue,
            $collectionKey2
        );
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}"];
        $this->request = new Request(
            "DELETE",
            "{$this->base_uri}/collections/{$collectionId}",
             $this->headers
            );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of renameCollection
     * renameCollection("Copy a Workspace1", "name", "Copy a Workspace5", "uid");
     * This method "PUT" alows more, than rename. It allows to create new collections with folders inside, rename them. 
     * I think this method is alternative to only "POST" method, that only creates new folders.
     * @param string $collectionKey the property of collection by which we will search
     * @param string $collectionValue the name of collection by which we will search
     * @param string $collectionKey2 the property of collection by we will get value
     * @param array $body body in request
     * @param array $headers headers with request
     * @return object function returns object with info about renamed collection: id, name, uid
     */
    public function renameCollection(string $newCollectionName, string $collectionKey, string $collectionValue, string $collectionKey2, array $body =  null, array $headers = null)
    {
        $collectionId = $this->parser->getJsonValueByValue(
            $this->getResponsedBody(
                $this->getCollections()
            ),
            $collectionKey,
            $collectionValue,
            $collectionKey2
        );
        $this->headers = ["x-api-key" => "{$this->postmanApiKey}", "Content-Type" => "application/json"];
        $this->body = ["collection" => ["info" => ["name" => "{$newCollectionName}", "schema" => "{$this->schema}"], "item" => []]];
        $this->request = new Request(
            "PUT",
            "{$this->base_uri}/collections/{$collectionId}",
            $this->headers,
            json_encode($this->body)
        );
        $this->response = $this->client->send($this->request);
        return $this->response;
    }
    /**
     * Summary of getResponsedBody
     * @param object $inputObject The function gets input object to transform it to json
     * @return string returns transformed to json's format object
     */
    public function getResponsedBody(object $inputObject)
    {
        $this->response = $inputObject;
        $this->response = $this->response->getBody();
        return (string)$this->response;
    }

}
// $pc = new PostmanConnector();
// $writer = new Writer();
// $parser = new Parser();

#Delete collection
// $pc->createClient();
// $pc->addVariableInEnvironment("test1", "name", "Postman API", "id");
// print_r($pc->getResponsedBody($pc->getEnvironment("name", "Postman API", "id")));
// print_r($pc->getResponsedBody($pc->getEnvironments()));

// $pc->getWorkspaces();
// $pc->createNewCollection("Copy a Workspace4", "name", "Working", "id");
// print_r($pc->getResponsedBody($pc->getCollection( "name", "Copy a Workspace1","id")));
// $pc->renameCollection("Copy a Workspace1", "name", "Copy a Workspace5", "uid");
// print_r($pc->getResponsedBody($pc->getCollections()));
// $pc->getCollection( "name", "Copy a Workspace1","id");
// $pc->getResponsedBody($pc->getCollection( "name", "Copy a Workspace1","id"));
// $pc->deleteCollection("name", "Copy a Workspace2", "uid");






// $pc->createClient();
// $pc->getWorkspaces();
// $pc->createNewCollection("Copy a Workspace1", "name", "Working", "id");
// $pc->test();
// $pc->getResponsedBody($pc->test());
// print_r($pc->getResponsedBody());


// $t = $pc->getResponsedBody($pc->getWorkspace("name", "Working", "id"));
// print_r($t);
// print_r(gettype($pc->getWorkspaces()));
// print_r($pc->getResponseBody());
// $pc->createNewCollection("test12345");

// $workspaceId = $parser->getJsonValueByValue($pc->getResponseBody(), "name", "Working", "id");
// print_r($workspaceId);
// $workspaceId1 = $parser->getEncodedPairValues($pc->getResponseBody(), "name", "Working", "id");
// print_r($workspaceId1);
// $test = $parser->getJsonValue($pc->getResponseBody(), "id");
// print_r($test);
// $pc->createClient();
// $pc->getCollections();
// $pc->getResponseBody();
// $writer->writeToFile("create", $pc->getResponseBody(), ".json");

// $pc->createClient();
// $pc->getWorkspaces();
// $parser->findValueByValue($pc->getResponseBody(), "name", "Working", "id");

// print_r($parser->parseJson($pc->getResponseBody()));
// $writer->writeToFile("create", $pc->getResponseBody(), ".json");
