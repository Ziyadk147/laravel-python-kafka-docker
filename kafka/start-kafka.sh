#!/bin/bash

# Start Zookeeper in the background
$KAFKA_HOME/bin/zookeeper-server-start.sh $KAFKA_HOME/config/zookeeper.properties &

# Start Kafka
$KAFKA_HOME/bin/kafka-server-start.sh $KAFKA_HOME/config/server.properties 


topics = ("detectionTopic1" , "detectionTopic2" , "detectionTopic3" , "detectionTopic4")

for topic in "${topics[@]}" ; do
    if $KAFKA_HOME/bin/kafka-topics.sh --bootstrap-server localhost:9092 --list | grep -q "$topic" ; then
         echo "Topic $topic already exists"
    else
        echo "Creating Topic $topic"
        $KAFKA_HOME/bin/kafka-topics.sh --bootstrap-server localhost:9092 --create --topic "$topic" --partitions 4
    fi
done
