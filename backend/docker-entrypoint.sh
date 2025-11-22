#!/bin/sh
set -e

# Wait for the database to be ready (optional if healthcheck in compose)
echo "Checking database connection..."
php bin/console dbal:run-sql "SELECT 1" > /dev/null 2>&1 || {
    echo "Database not reachable, waiting..."
    sleep 5
}

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Load initial data if not already loaded
DATA_FLAG="/app/var/init/.data-loaded"
if [ ! -f "$DATA_FLAG" ]; then
    # Create the folder if needed
    mkdir -p /app/var/init

    # Load stations
    if [ -f "/data/stations.sql" ]; then
        php bin/console dbal:run-sql "$(cat /data/stations.sql)"
    else
        echo "Warning: stations.sql file not found"
    fi

    # Load connections
    if [ -f "/data/distances.sql" ]; then
        php bin/console dbal:run-sql "$(cat /data/distances.sql)"
    else
        echo "Warning: distances.sql file not found"
    fi

    # Mark as loaded
    touch "$DATA_FLAG"
else
    echo "Initial data already loaded, skipping"
fi

# Execute the provided command (php-fpm by default)
exec "$@"