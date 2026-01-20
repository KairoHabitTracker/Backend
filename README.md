# Kairo Habit Tracker Backend

This repository contains the code for Kairo Habit Tracker app with docs and an admin dashboard

## Run Locally

```bash
    # Clone the project
    git clone https://github.com/KairoHabitTracker/Backend && cd Backend
    
    # Install dependencies
    composer install --no-interaction --optimize-autoloader && pnpm install
    
    # Copy the .env file
    cp .env.example .env # Fill in db credentials
    
    # Generate application key
    php artisan key:generate
    
    # Run database migrations
    php artisan migrate:fresh --seed
    
    # Link storage
    php artisan storage:link
    
    # Start the development server
    composer run dev
    
    # The app will be available at http://localhost:8000
```

## Run with Docker

```bash
    # Clone the project
    git clone https://github.com/KairoHabitTracker/Backend && cd Backend
    
    # Start the containers
    docker-compose up -d --build
    
    # The app will be available at http://localhost:8000
```
