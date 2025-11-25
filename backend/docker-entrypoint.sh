#!/bin/sh
set -e

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Generate JWT keys if not already present
JWT_PRIVATE_KEY="/app/config/jwt/private.pem"
if [ ! -f "$JWT_PRIVATE_KEY" ]; then
    echo "Generating JWT key pair..."
    php bin/console lexik:jwt:generate-keypair --overwrite
else
    echo "JWT keys already exist, skipping generation"
fi

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