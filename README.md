# Mini X Backend

Backend-only Laravel API powered by Docker, PostgreSQL, Redis, Nginx, and PHP-FPM.

## Stack

- Laravel
- PHP-FPM
- PostgreSQL
- Redis
- Nginx
- Docker Compose

## Structure

```text
mini-x-backend/
├── app/                 # Laravel application
├── docker/              # Docker images and service configs
├── docker-compose.yaml  # Local runtime stack
├── Makefile             # Short local commands
├── .dockerignore        # Files excluded from Docker build context
├── .gitignore           # Files excluded from Git
└── README.md
```

## Run Locally

```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php artisan migrate
```

API base URL:

```text
http://localhost:8080/api
```

## Make Commands

```bash
make up       # Build and start containers
make install  # Install Composer dependencies
make migrate  # Run database migrations
make routes   # Show Laravel routes
make shell    # Open shell inside PHP container
make down     # Stop containers
```

## Git Setup

```bash
git add .
git commit -m "Initial Mini X backend setup"
git branch -M main
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPOSITORY.git
git push -u origin main
```

