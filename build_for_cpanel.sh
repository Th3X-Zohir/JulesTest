#!/bin/bash

# Simple script to prepare files for cPanel upload
echo "Preparing for deployment..."

# 1. Install dependencies optimized
composer install --optimize-autoloader --no-dev

# 2. Build assets
npm install
npm run build

# 3. Create ZIP
zip -r ecourt-deploy.zip . -x "*.git*" "node_modules/*" "tests/*" "storage/*.log" ".env"

echo "Done! Upload 'ecourt-deploy.zip' to your cPanel."
