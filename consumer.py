from confluent_kafka import Consumer , KafkaError , KafkaException;
from os import getenv ;
import sys;
from sqlalchemy import create_engine, Column, String, Float , Integer
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
import json;
import time , sys , pusher;


db_user = 'root'
db_password = "root"
db_host = "db"
db_port = "3306"
db_name = "laravel"

# Create the database engine
engine = create_engine(f'mysql+pymysql://{db_user}:{db_password}@{db_host}:{db_port}/{db_name}')

# engine = create_engine(db_string)
Base = declarative_base()


partitionGroup = sys.argv[2]
conf = {
        'bootstrap.servers': 'localhost:9092',
        'group.id': partitionGroup,
        'enable.auto.commit': 'true',
        'auto.offset.reset': 'latest'
}

pusher_client = pusher.Pusher(
  app_id='1844782',
  key="e69edaf25ba122eccca6",
  secret="a9f175a19a411e90c9e7",
  cluster='ap2'
)

Base.metadata.create_all(engine , checkfirst = False)
Session = sessionmaker(bind=engine)
session = Session()

message = [];


# @app.route("/consume" , methods = ["GET"])

def consume():
    consumer = Consumer(conf);
    topic = sys.argv[1]
    consumer.subscribe([topic])
    lastSaveTime = time.time()
    while True:
            msg = consumer.poll(timeout=0.0001)
            if msg is None:
                if(len(message) > 0):
                    saveDetection(messageDict=message)
                    print("DETECTION SAVED")
                    message.clear()
                continue
            if msg.error():
                if msg.error().code() == KafkaError._PARTITION_EOF:
                    # End of partition event
                    sys.stderr.write('%% %s [%d] reached end at offset %d\n' %
                                     (msg.topic(), msg.partition(), msg.offset()))
                elif msg.error():
                    raise KafkaException(msg.error())
            else:
                data_dict = json.loads(msg.value())
                sendLiveDetection(data_dict , topic)
                message.append(data_dict)
                currentTime = time.time();

                if(len(message) % 1000 == 0 or currentTime - lastSaveTime >= 5 ):

                    saveDetection(messageDict=message)
                    message.clear()
                    print("DETECTION SAVED")
                    lastSaveTime = currentTime;
                # saveDetection(messageDict=message)
                print(data_dict)
                consumer.commit()


def saveDetection(messageDict):
    class Messages(Base):
        __tablename__ = "messages"
        __table_args__ = {'extend_existing': True}  # Add this line
        id = Column(Integer, primary_key=True)
        DeviceID = Column(String(50))
        SignalType = Column(String(50))
        DataType = Column(String(50))
        DataValue = Column(String(50))
        SignalStrength = Column(Float)
        MNC = Column(String(100))
        MCC = Column(String(100))
        Count = Column(Integer)

    session.bulk_insert_mappings(Messages , messageDict)
    session.commit()
    session.close()
    print("\nsaved")
def sendLiveDetection(message , topic):
    channel = ""
    match topic:
        case "detectionTopic1":
            channel = "channelA"
        case "detectionTopic2":
            channel = "channelB"
        case "detectionTopic3":
            channel = "channelC"
        case "detectionTopic4":
            channel = "channelD"
    print("\nsent")

    pusher_client.trigger(channel , 'my-event' , message);


if __name__ == "__main__":
    consume()
