# Laravel Reverb Chat

## Installation

To install Laravel Reverb Chat Test project, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/lucascnunes/laravel-reverb-chat.git
   ```

2. Navigate to the project directory:
   ```bash
   cd laravel-reverb-chat
   ```

3. Install dependencies using Composer:
   ```bash
   composer install
   ```
   
4. Build the project frontend using npm:
   ```bash
   npm install
   npm run build 
   ```

5. Create a copy of the `.env.example` file and rename it to `.env`:
   ```bash
   cp .env.example .env
   ```

6. Generate an application key:
   ```bash
   php artisan key:generate
   ```

7. Run database migrations:
   ```bash
   php artisan migrate
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

9. Start reverb server:
   ```bash
   php artisan reverb:serve
   ```

10. Start queue listener:
    ```bash
    php artisan queue:listen
    ```

11. Open your web browser and visit `http://localhost:8000` to see the application in action.

12. Create your account by registering on the website.

13. If you want to test the application, you can open a new incognito window and visit `http://localhost:8000` , with a different account, to see the application in action.
