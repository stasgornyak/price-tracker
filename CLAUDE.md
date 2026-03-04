# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Price Tracker is a Laravel 12 application that monitors OLX.ua classifieds ads for price changes and sends email notifications. Users subscribe to ads, and the system periodically checks prices and alerts via email when changes are detected.

## Development Commands

```bash
# Start development environment (server, queue, logs, vite in parallel)
composer dev

# Run tests
./vendor/bin/sail test

# Run a single test file
./vendor/bin/sail test tests/Feature/Path/To/TestFile.php

# Run tests with coverage
./vendor/bin/sail test --coverage

# Static analysis (PHPStan level 7)
./vendor/bin/sail php vendor/bin/phpstan analyse

# Code formatting (Laravel Pint)
./vendor/bin/sail php vendor/bin/pint
```

## Architecture

### Price Checking Flow

1. **Scheduler** triggers `CheckAllPrices` service every 30 minutes
2. `CheckAllPrices` dispatches `PriceCheck` jobs for each subscription (chunked by 100)
3. `PriceCheck` job calls `CheckPriceAction::handle()`
4. `CheckPriceAction` either reuses a recently-checked price (within 15 min for same URL) or fetches from OLX via parser
5. On price change, dispatches `PriceChanged` event
6. `SendPriceChangedNotification` listener sends email via queue

### Parser System

Parsers are configured in `config/subscriptions.php`. The `ParserFactory` selects the appropriate parser based on URL prefix. To add a new site:

1. Create a parser implementing `ParserInterface` in `app/Services/Parsers/`
2. Register it in `config/subscriptions.php` with `base_url` and `class`

### Key Services

- `App\Services\Subscription\CheckPriceAction` - Core price checking logic with deduplication
- `App\Services\Parsers\ParserFactory` - Creates appropriate parser based on URL
- `App\Jobs\PriceCheck` - Queue job with 3 retries and exponential backoff (10s, 30s, 60s)

### Event System

- `PriceChanged` event implements `ShouldDispatchAfterCommit` (dispatches after DB transaction)
- `SendPriceChangedNotification` listener implements `ShouldQueue` (runs asynchronously)

## Tech Stack

- **PHP 8.4** / **Laravel 12** / **SQLite**
- **Testing**: Pest
- **Static Analysis**: Larastan (PHPStan level 7)
- **Containerization**: Laravel Sail
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Mail Testing**: Mailpit (http://localhost:8025)