#!/bin/sh
set -e

if [ "$1" != "unitd" ] && [ "$1" != "unitd-debug" ]; then
    exec "$@"
fi

"$@" &

UNIT_PID=$!

echo "Waiting for Unit control socket..."
until [ -S /var/run/control.unit.sock ]; do
    sleep 0.1
done

if [ -f /docker-entrypoint.d/config.json ]; then
    echo "Loading Unit configuration..."
    HTTP_CODE=$(curl -s -o /tmp/unit_response.txt -w "%{http_code}" -X PUT --data-binary @/docker-entrypoint.d/config.json \
        --unix-socket /var/run/control.unit.sock \
        http://localhost/config)

    if [ "$HTTP_CODE" = "200" ]; then
        echo "Configuration loaded successfully."
    else
        echo "ERROR: Failed to load configuration (HTTP $HTTP_CODE)"
        echo "Response:"
        cat /tmp/unit_response.txt
        kill $UNIT_PID
        exit 1
    fi
fi

wait $UNIT_PID
