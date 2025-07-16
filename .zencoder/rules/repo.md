---
description: Repository Information Overview
alwaysApply: true
---

# Ruminansia-Care Information

## Summary
Ruminansia-Care is a web application built with Laravel for diagnosing diseases in ruminant animals. The system uses a rule-based approach to match symptoms with potential diseases, providing diagnostic capabilities for veterinary purposes.

## Structure
- **app/**: Contains the application code including controllers, models, and providers
- **resources/**: Frontend assets, views, and JavaScript/CSS files
- **database/**: Database migrations, seeders, and factories
- **routes/**: Application routes definition
- **public/**: Publicly accessible files and assets
- **tests/**: Application test files
- **config/**: Configuration files
- **docker/**: Docker configuration files

## Language & Runtime
**Language**: PHP
**Version**: 8.2
**Framework**: Laravel 12.0
**Build System**: Composer
**Package Manager**: Composer, NPM

## Dependencies
**Main Dependencies**:
- laravel/framework: ^12.0
- laravel/tinker: ^2.10.1

**Development Dependencies**:
- pestphp/pest: ^3.7
- pestphp/pest-plugin-laravel: ^3.1
- laravel/pail: ^1.2.2
- laravel/sail: ^1.41
- tailwindcss: ^4.0.0
- vite: ^6.0.11

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build frontend assets
npm run build

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

## Docker
**Dockerfile**: Dockerfile
**Configuration**: Uses PHP 8.2-fpm with Nginx, configured to expose port 8080
**Build Command**:
```bash
docker build -t ruminansia-care .
docker run -p 8080:8080 ruminansia-care
```

## Testing
**Framework**: Pest PHP (PHPUnit wrapper)
**Test Location**: tests/Feature and tests/Unit
**Configuration**: phpunit.xml
**Run Command**:
```bash
./vendor/bin/pest
```

## Models & Database
**Main Models**:
- Animal: Represents ruminant animal types
- Disease: Represents diseases that can be diagnosed
- Symptom: Represents symptoms that can be observed
- Rule: Represents diagnostic rules connecting symptoms to diseases
- History: Stores diagnostic history