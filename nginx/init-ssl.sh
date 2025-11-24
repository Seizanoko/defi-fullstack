#!/bin/sh
set -e

SSL_DIR="/etc/nginx/ssl"
CERT_FILE="$SSL_DIR/nginx-selfsigned.crt"
KEY_FILE="$SSL_DIR/nginx-selfsigned.key"

echo "🔐 Checking SSL certificates..."

# Check if certificates already exist
if ! [ -f "$CERT_FILE" ] && ! [ -f "$KEY_FILE" ]; then
    echo "📦 Generating self-signed SSL certificates..."
    
    # Install openssl if needed
    apk add --no-cache openssl
    
    # Create the directory if needed
    mkdir -p "$SSL_DIR"
    
    # Generate the certificate
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout "$KEY_FILE" \
        -out "$CERT_FILE" \
        -subj "/C=CH/ST=Vaud/L=Montreux/O=DefiFullstack/CN=localhost"
    
    echo "SSL certificates generated successfully"
fi

exec nginx -g 'daemon off;'