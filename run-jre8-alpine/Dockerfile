# ---- Stage 1: Build Environment ----
    FROM openjdk:8-jdk-alpine AS builder

    # Set working directory
    WORKDIR /app
    
    # Copy source files (adjust if using Maven/Gradle)
    COPY src/ .
    
# ---- Stage 2: Minimal Runtime ----
    FROM openjdk:8-jre-alpine

    # Set working directory
    WORKDIR /app

    # Copy application JAR and properties file
    COPY src/soonr.jar soonr.jar
    COPY src/account.properties account.properties
    
    # Expose application port
    EXPOSE 8888
    
    # Copy and set permissions for startup script
    COPY entrypoint.sh /entrypoint.sh
    RUN chmod +x /entrypoint.sh
    
    # Use a startup script for better control
    ENTRYPOINT ["/entrypoint.sh"]
