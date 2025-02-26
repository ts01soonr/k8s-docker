#!/bin/sh
CMD="${CMD:-jar}"

if [ "$CMD" = "jar" ]; then
    exec java -jar soonr.jar
else
    exec java -cp soonr.jar jtcom.lib.srv.ConnectionHandler "$CMD"
fi
