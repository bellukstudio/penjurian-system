#!/bin/bash

DOMAIN="penjuriandemo.bellukstudio.my.id"
EMAIL="bellukchips@gmail.com"

# Install certbot
sudo apt update
sudo apt install certbot python3-certbot-nginx -y

# Stop nginx temporarily
docker stop nginx_penjuriandemo

# Generate certificate
sudo certbot certonly --standalone \
    --email $EMAIL \
    --agree-tos \
    --no-eff-email \
    -d $DOMAIN \
    -d www.$DOMAIN

# Copy certificates to nginx directory
sudo cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem /home/bellukstudio/projects/containers/nginx/ssl/
sudo cp /etc/letsencrypt/live/$DOMAIN/privkey.pem /home/bellukstudio/projects/containers/nginx/ssl/

# Set proper permissions
sudo chown -R 1000:1000 /home/bellukstudio/projects/containers/nginx/ssl/

# Start nginx again
docker start nginx_penjuriandemo

echo "✅ SSL certificates installed for $DOMAIN"
