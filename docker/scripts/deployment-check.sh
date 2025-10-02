#!/bin/bash

# deployment-check.sh - Enhanced health check with auto-restart capability

APP_ROOT="/var/www/html"
DEPLOYMENT_MARKER="$APP_ROOT/.deployment"
LAST_DEPLOYMENT_FILE="/tmp/last_deployment"
LOG_FILE="/var/log/supervisor/deployment-check.log"

# Function to log messages
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Function to check if application is healthy
check_app_health() {
    # Check Apache process
    if ! pgrep apache2 > /dev/null; then
        log_message "ERROR: Apache process not running"
        return 1
    fi

    # Check if application responds
    local response=$(curl -s -o /dev/null -w "%{http_code}" --max-time 10 --connect-timeout 5 http://localhost/ 2>/dev/null)
    if [ "$response" != "200" ] && [ "$response" != "302" ]; then
        log_message "ERROR: Application not responding (HTTP: $response)"
        return 1
    fi

    # Check Laravel health endpoint if exists
    local health_response=$(curl -s -o /dev/null -w "%{http_code}" --max-time 5 --connect-timeout 3 http://localhost/health 2>/dev/null)
    if [ "$health_response" = "200" ]; then
        log_message "INFO: Health endpoint OK"
    fi

    return 0
}

# Function to check for new deployment
check_deployment() {
    if [ -f "$DEPLOYMENT_MARKER" ]; then
        local current_deployment=$(cat "$DEPLOYMENT_MARKER" 2>/dev/null || echo "")
        local last_deployment=$(cat "$LAST_DEPLOYMENT_FILE" 2>/dev/null || echo "")
        
        if [ "$current_deployment" != "$last_deployment" ]; then
            log_message "INFO: New deployment detected: $current_deployment"
            echo "$current_deployment" > "$LAST_DEPLOYMENT_FILE"
            return 0
        fi
    fi
    return 1
}

# Function to restart application
restart_application() {
    log_message "INFO: Restarting application..."
    
    # Clear Laravel caches
    cd "$APP_ROOT"
    php artisan config:clear 2>/dev/null || true
    php artisan route:clear 2>/dev/null || true
    php artisan view:clear 2>/dev/null || true
    php artisan cache:clear 2>/dev/null || true
    
    # Recache everything
    php artisan config:cache 2>/dev/null || true
    php artisan route:cache 2>/dev/null || true
    php artisan view:cache 2>/dev/null || true
    
    # Restart Apache gracefully
    if pgrep apache2 > /dev/null; then
        apache2ctl graceful 2>/dev/null || apache2ctl restart 2>/dev/null
        log_message "INFO: Apache restarted gracefully"
    else
        log_message "WARNING: Apache was not running, supervisor will restart it"
    fi
    
    sleep 5
}

# Function to run in watch mode
watch_mode() {
    log_message "INFO: Starting deployment watcher..."
    
    while true; do
        if check_deployment; then
            restart_application
            
            # Wait for restart to complete
            sleep 10
            
            # Verify health after restart
            local retry_count=0
            while [ $retry_count -lt 6 ]; do
                if check_app_health; then
                    log_message "INFO: Application restarted successfully"
                    break
                else
                    log_message "WARNING: Application health check failed, retrying... ($((retry_count + 1))/6)"
                    sleep 10
                fi
                retry_count=$((retry_count + 1))
            done
            
            if [ $retry_count -eq 6 ]; then
                log_message "ERROR: Application failed to restart properly"
            fi
        fi
        
        sleep 30
    done
}

# Main execution
case "${1:-}" in
    --watch)
        watch_mode
        ;;
    *)
        # Default health check mode
        if ! check_app_health; then
            log_message "ERROR: Health check failed"
            exit 1
        fi
        
        # Check for deployment and auto-restart if needed
        if check_deployment; then
            log_message "INFO: Deployment change detected, triggering restart..."
            restart_application
            
            # Re-check health after restart
            sleep 5
            if ! check_app_health; then
                log_message "ERROR: Health check failed after restart"
                exit 1
            fi
        fi
        
        log_message "INFO: Health check passed"
        exit 0
        ;;
esac
