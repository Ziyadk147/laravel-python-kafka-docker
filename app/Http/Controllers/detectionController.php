<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detection;
// use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;
use Junges\Kafka\Message\Message;
use Junges\Kafka\Producers\MessageBatch;
use PDO;

class detectionController extends Controller
{
    public function createDetectionMessage1(Request $request)
    {


        $message = $request->all();

        $brokerAddr = "localhost:9092";

        $noOfConsumers = 4;

        // for($i = 0 ; $i <= 100 ; $i++){
            // $message['Count'] = $i;
            $this->sendMessages("detectionTopic1", $brokerAddr, "partitionGroup1", $message, $noOfConsumers);
        // }

        return response(null, 200);
    }

    public function createDetectionMessage2(Request $request)
    {
        $message = $request->all();
        $brokerAddr = "localhost:9092";
        $noOfConsumers = 4;

        $this->sendMessages("detectionTopic2", $brokerAddr, "partitionGroup2", $message, $noOfConsumers);
        return response(null, 200);

    }

    public function createDetectionMessage3(Request $request)
    {
        $message = $request->all();
        $brokerAddr = "localhost:9092";
        $noOfConsumers = 4;
        $this->sendMessages("detectionTopic3", $brokerAddr, "partitionGroup3", $message, $noOfConsumers);
    }


    public function createDetectionMessage4(Request $request)
    {
        $message = $request->all();
        $brokerAddr = "localhost:9092";
        $noOfConsumers = 4;
        $this->sendMessages("detectionTopic4", $brokerAddr, "partitionGroup4", $message, $noOfConsumers);
    }


    public function sendMessages($sendingTopic, $brokerAddr, $consumerGroupName, $message, $noOfConsumers)
    {

        $sendingMessage = new Message(topicName: $sendingTopic, body: $message);
        $producer = $this->createProducer($brokerAddr, $sendingTopic);
        $producer->withMessage($sendingMessage)->onTopic($sendingTopic)->send();
    }
    public function createProducer($brokerAddr, $topic)
    {
        return Kafka::publish($brokerAddr)->onTopic($topic);
    }}
