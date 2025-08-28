#!/bin/bash

# Deploy script for KATANA Laravel Application
echo "🚀 Starting deployment process..."

# Variables
REPO_URL="https://github.com/your-username/katana.git"  # Change this to your actual repo
DEPLOY_DIR="/var/www/katana"
BRANCH="main"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create deployment directory
print_status "Creating deployment directory..."
sudo mkdir -p $DEPLOY_DIR
cd $DEPLOY_DIR

# Clone or update repository
if [ -d ".git" ]; then
    print_status "Updating existing repository..."
    git pull origin $BRANCH
else
    print_status "Cloning repository..."
    git clone -b $BRANCH $REPO_URL .
fi

# Copy environment file
if [ ! -f ".env" ]; then
    print_status "Creating environment file..."
    cp .env.production .env
    print_warning "Please edit .env file with your actual configuration!"
else
    print_status "Environment file already exists."
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating application key..."
    docker run --rm -v $(pwd):/var/www/html -w /var/www/html php:8.2-cli php artisan key:generate --force
fi

# Set proper permissions
print_status "Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Build and start containers
print_status "Building and starting Docker containers..."
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d

# Wait for containers to be ready
print_status "Waiting for containers to be ready..."
sleep 30

# Run database migrations
print_status "Running database migrations..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Clear and cache application
print_status "Clearing and caching application..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan view:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan view:cache

# Create storage symlink
print_status "Creating storage symlink..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan storage:link

print_status "✅ Deployment completed successfully!"
print_status "Your application should be running at: http://your-server-ip"
print_warning "Don't forget to:"
print_warning "1. Update your domain name in nginx configuration"
print_warning "2. Configure SSL certificate"
print_warning "3. Update .env file with production values"
print_warning "4. Set up proper backup strategy"
