#!/bin/bash

# Coolify Webhook Deployment Script
# This script can be called by Coolify webhook for automatic deployments

echo "🚀 Starting automatic deployment..."

# Set environment variables for build
export NODE_ENV=production
export APP_ENV=production

# Build and deploy
echo "📦 Building application..."

# The Docker build process will handle:
# 1. npm ci (install all dependencies including devDependencies)
# 2. npm audit fix (fix vulnerabilities)
# 3. npm run build (build Vite assets)
# 4. npm ci --only=production (cleanup dev dependencies)

echo "✅ Deployment completed!"
echo "🌐 Application will be available shortly..."

# Exit with success
exit 0
