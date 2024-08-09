FROM python:3.11-slim



WORKDIR /app/python

COPY packages.txt .

RUN pip install --no-cache-dir -r packages.txt

COPY . .

ENV TOPIC = detectionTopic1
ENV PARTITION_GROUP =  partitionGroup1

CMD ["python", "consumer.py", "${TOPIC}}", "${PARTITION_GROUP}}"]
