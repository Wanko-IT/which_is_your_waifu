# Which Is Your Waifu

A voting application that allows users to vote for their favorite character from a selection of multiple characters.

## Project Structure

This project consists of two main parts:

### Frontend (Nuxt.js)

Located in the `frontend/nuxt` directory, the frontend is built with Nuxt.js and provides:
- Character listing page
- Voting functionality
- Results page showing vote counts

### Backend (Laravel)

Located in the `backend/laravel` directory, the backend is built with Laravel and provides:
- RESTful API for character data
- Vote recording and retrieval
- Database migrations for MySQL

## Database

The application uses a MySQL database hosted on AWS. The database configuration can be found in the Laravel `.env` file.

## Setup Instructions

### Backend Setup

1. Navigate to the Laravel directory:
   ```
   cd backend/laravel
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Copy the environment file:
   ```
   cp .env.example .env
   ```

4. Configure your AWS database connection in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=your-aws-rds-endpoint.rds.amazonaws.com
   DB_PORT=3306
   DB_DATABASE=which_is_your_waifu
   DB_USERNAME=admin
   DB_PASSWORD=your-password
   ```

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Run migrations:
   ```
   php artisan migrate
   ```

7. Start the server:
   ```
   php artisan serve
   ```

### Frontend Setup

1. Navigate to the Nuxt.js directory:
   ```
   cd frontend/nuxt
   ```

2. Install dependencies:
   ```
   npm install
   ```

3. Configure your API endpoint in the `.env` file:
   ```
   API_URL=http://your-aws-api-endpoint.com/api
   ```

4. Start the development server:
   ```
   npm run dev
   ```

## API Endpoints

### Characters

- `GET /api/characters` - Get all characters
- `GET /api/characters/{id}` - Get a specific character

### Votes

- `POST /api/votes` - Submit a vote for a character
- `GET /api/characters/{id}/votes` - Get votes for a specific character
- `GET /api/votes/results` - Get voting results for all characters
