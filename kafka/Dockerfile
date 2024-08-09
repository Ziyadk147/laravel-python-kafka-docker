# Use an official openjdk runtime as a parent image
FROM openjdk:17-slim

# Set environment variables for Kafka
ENV KAFKA_HOME=/app/kafka
ENV PATH=$KAFKA_HOME/bin:$PATH

# Set the working directory to /app/kafka
WORKDIR /app/kafka

# Copy the current directory (kafka) contents into /app/kafka
COPY . /app/kafka

# Expose the necessary ports (default for Kafka and Zookeeper)
EXPOSE 9092 2181

# Create a data directory for Zookeeper and Kafka logs
RUN mkdir -p /app/kafka/data /app/kafka/logs

# Set up the entry point for starting Kafka and Zookeeper
COPY start-kafka.sh /usr/bin/start-kafka.sh
RUN chmod +x /usr/bin/start-kafka.sh

# Start Kafka using the entry point script
ENTRYPOINT ["start-kafka.sh"]
