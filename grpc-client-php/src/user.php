<?php

use Grpc\UserClient;
use Grpc\ChannelCredentials;
use Grpc\FindByIdRequest;

require dirname(__FILE__) . '/../vendor/autoload.php';

/**
 * parses GRPC errors
 */
function getErrorMessage(\stdClass $status, string $id): string
{
    if ($status->code !== \Grpc\STATUS_NOT_FOUND)
        return sprintf("User %s was not found", $id);

    return "ERROR: " . $status->code . ", " . $status->details;
}

function run(string $hostname, string $id): void
{
    // creates grpc client
    $userClient = new UserClient($hostname, [
        'credentials' => ChannelCredentials::createInsecure(),
    ]);

    // creates grpc request
    $request = new FindByIdRequest();
    $request->setId($id);


    // calls the FindById remote procedure
    list($response, $status) = $userClient->FindById($request)->wait();

    // in case of an error, show it and finish a script
    if ($status->code !== \Grpc\STATUS_OK) {
        echo getErrorMessage($status, $id) . PHP_EOL;
        exit(1);
    }

    // shows received user name
    echo $response->getName() . PHP_EOL;
}

// desire user id
$id = !empty($argv[1]) ? $argv[1] : 'some-unique-id';

// grpc server address
$hostname = !empty($argv[2]) ? $argv[2] : 'grpc-server:9090';


run($hostname, $id);
