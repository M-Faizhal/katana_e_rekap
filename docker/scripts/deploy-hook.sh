#!/bin/bash

# deploy-hook.sh - Post-deployment hook for Coolify
# This script runs after successful deployment

echo "Post-deployment hook started..."

# Update deployment marker to trigger auto-restart
if [ -f "/var/www/html/.deployment" ]; then
    echo "$(date '+%Y-%m-%d_%H:%M:%S')_$(hostname)_coolify_deploy" > /var/www/html/.deployment
    echo "Deployment marker updated - auto-restart will be triggered"
else
    echo "Warning: Deployment marker not found"
fi

# Optional: Trigger immediate cache clear
docker exec $CONTAINER_NAME php artisan config:clear 2>/dev/null || true
docker exec $CONTAINER_NAME php artisan route:clear 2>/dev/null || true
docker exec $CONTAINER_NAME php artisan view:clear 2>/dev/null || true

echo "Post-deployment hook completed"
