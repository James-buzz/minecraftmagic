# Getting Started with MinecraftMagic

This guide will help you set up MinecraftMagic for local development using Laravel and [Laravel Sail](https://laravel.com/docs/11.x/sail).

## Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop)
- [Git](https://git-scm.com/downloads)
- [Composer](https://getcomposer.org/download/) (for initial setup only)

## Setup Steps

1. Clone the repository:
   ```
   git clone https://github.com/james-buzz/minecraftmagic.com.git
   cd minecraftmagic.com
   ```

2. Install Composer dependencies:
   ```
   composer install
   ```

3. Copy the example environment file:
   ```
   cp .env.example .env
   ```

4. Start the Docker environment using Laravel Sail:
   ```
   ./vendor/bin/sail up -d
   ```

5. Generate an application key:
   ```
   ./vendor/bin/sail artisan key:generate
   ```

6. Run database migrations:
   ```
   ./vendor/bin/sail artisan migrate
   ```

7. Install, compile and run (dev) frontend assets:
   ```
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

## Accessing the Application

Once the setup is complete, the following services are accessible in your browser:

* `http://localhost` - **Laravel Application**
* `http://localhost:8025` - Mailpit (Email Testing)
* `http://localhost:9090` - Prometheus (Metrics)
* `http://localhost:3030` - Grafana (Metrics Dashboard)

## Common Commands

- Start the environment: `./vendor/bin/sail up -d`
- Stop the environment: `./vendor/bin/sail down`
- Run Artisan commands: `./vendor/bin/sail artisan [command]`
- Run NPM commands: `./vendor/bin/sail npm [command]`
- Run tests: `./vendor/bin/sail test`

## Troubleshooting

If you encounter any issues during setup, please check the following:

1. Make sure Docker is running on your system.
2. Ensure no other services are using port 80 on your local machine.
3. If you're having permission issues, you may need to run Sail commands with `sudo`.

For more detailed information on using Laravel Sail, refer to the [official Laravel Sail documentation](https://laravel.com/docs/sail).

## Next Steps

Now that you have your local development environment set up.
