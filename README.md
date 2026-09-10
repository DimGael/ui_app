# BigBricks.io

A Symfony 8.0 web application that hosts a Tailwind CSS component library with AI-powered editing capabilities.

## Overview

BigBricks.io offers ready-made UI components (HTML/CSS/JS) built with Tailwind CSS, and lets users modify them using Google Gemini AI that edits the underlying HTML code.

## Requirements

- PHP >= 8.4
- Node.js & npm
- Docker & Docker Compose
- Symfony CLI
- Composer

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd ui_app
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy `.env.dist` to `.env` and configure your environment variables:
```bash
cp .env.dist .env
```

5. Start the database:
```bash
make docker
```

6. Run database updates:
```bash
php bin/console doctrine:schema:update --force
```

7. Start the application:
```bash
make start
```

## Usage

Visit `http://127.0.0.1:8000` to access the application.

- Browse components at `/component`
- Use the Playground to view and edit components with live preview
- Ask AI to modify components using natural language

## Features

- **Component Library**: Browse and manage Tailwind CSS components
- **Playground**: Split-pane code editor with live iframe preview
- **Device Preview**: Toggle between mobile, tablet, and desktop views
- **AI Editing**: Modify components using Google Gemini AI prompts
- **User Authentication**: Register and login to save your work
- **Admin Panel**: Manage components via EasyAdmin at `/admin`

## Tech Stack

- **Backend**: Symfony 8.0, Doctrine ORM, MySQL
- **Frontend**: Tailwind CSS, Webpack Encore
- **AI**: Google Gemini API
- **Infrastructure**: Docker Compose

## Available Commands

```bash
make help      # Display all available commands
make start     # Start all services (Encore, Docker, Symfony server)
make stop      # Stop Docker containers
make cache     # Clear the Symfony cache
```

## Security

- Store API keys in `.env`
- Admin access restricted to `ROLE_ADMIN`
