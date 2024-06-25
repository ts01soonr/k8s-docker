FROM openjdk:8
WORKDIR /app
ADD src/soonr.jar /app/soonr.jar
ADD src/account.properties /app/account.properties
EXPOSE 8888
CMD sleep 30
CMD ["java","-jar","soonr.jar"]
