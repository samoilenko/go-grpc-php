<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Grpc;

/**
 * The user service definition.
 */
class UserClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * Sends a greeting
     * @param \Grpc\FindByIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function FindById(\Grpc\FindByIdRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.User/FindById',
        $argument,
        ['\Grpc\FindByIdReply', 'decode'],
        $metadata, $options);
    }

}
