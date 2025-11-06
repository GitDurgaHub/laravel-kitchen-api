🚀 Laravel 12 — Kitchen Throttling API (Dockerized, Tested, and Verified)

This project implements the Tao Digital Backend Challenge using Laravel 12, Docker, and Pest tests — with clean architecture and SOLID design principles.


🧩 Features Implemented

REST API endpoints:

POST /api/orders → create order (VIP bypasses capacity)

GET /api/orders/active → list active orders

POST /api/orders/{id}/complete → mark an order as completed

Kitchen throttling with configurable capacity (KITCHEN_CAPACITY)

Suggests next pickup time when full

Cron-like auto-complete for stale orders

Full test coverage (Pest Feature + Unit tests)

Dockerized: PHP-FPM + Nginx + MySQL + Scheduler + Queue

Compatible with Windows (XAMPP), Linux, and macOS


🛠️ Project Setup
1️⃣ Prerequisites

Make sure you have:

Docker Desktop
 installed and running

Apache stopped (if using XAMPP) so port 80 is free

PHP 8.2+ and Composer (only if you want to run commands outside Docker)

git clone https://github.com/GitDurgaHub/laravel-kitchen-api/laravel-kitchen-api.git
cd laravel-kitchen-api

Run setup
composer install
cp .env.example .env
php artisan key:generate

Then build and start containers:
docker compose up -d --build

Check that all services are running:
docker compose ps

Expected output:
laravel-kitchen-api-app-1         Up
laravel-kitchen-api-db-1          Up (port 3307->3306)
laravel-kitchen-api-web-1         Up (port 80->80)
laravel-kitchen-api-scheduler-1   Up
laravel-kitchen-api-queue-1       Up


4️⃣ Run migrations and seeders
Run these from your host terminal:

docker compose exec app php artisan migrate:fresh --seed


If you get capacity or route errors, make sure:
docker compose exec app php artisan route:list

🔹 Run all tests
docker compose exec app php artisan test

✅ Expected output:
PASS  Tests\Unit\KitchenCapacityServiceTest
PASS  Tests\Feature\OrdersTest


Shows:
POST  api/orders  -> OrderController@store
GET   api/orders/active
POST  api/orders/{order}/complete


🧭 Manual API Testing
🧱 1️⃣ Test Active Orders (GET)
🔸 PowerShell
Invoke-RestMethod -Uri "http://localhost/api/orders/active"
Or in browser
http://localhost/api/orders/active 
🔸 Git Bash / WSL
curl  "http://localhost/api/orders/active"
✅ Expected success:
{"data":[{"id":3,"items":["fries","pizza"],"pickup_time":"2025-11-06T14:49:14+00:00","VIP":false,"status":"active","completed_at":null,"created_at":"2025-11-06T14:42:15+00:00"},{"id":2,"items":["burger","fries","pizza"],"pickup_time":"2025-11-06T15:22:14+00:00","VIP":false,"status":"active","completed_at":null,"created_at":"2025-11-06T14:42:15+00:00"},{"id":1,"items":["pizza"],"pickup_time":"2025-11-06T15:15:14+00:00","VIP":false,"status":"active","completed_at":null,"created_at":"2025-11-06T14:42:15+00:00"}]}



🥡 2️⃣ Create Order (POST)
🔸 Git Bash / WSL
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"items":["burger","fries"],"pickup_time":"2025-11-06T12:30:00Z"}'


🔸 PowerShell
Invoke-RestMethod -Uri "http://localhost/api/orders" `
  -Method POST `
  -Headers @{ "Content-Type" = "application/json"; "Accept" = "application/json" } `
  -Body "{""items"":[""burger"",""fries""],""pickup_time"":""2025-11-06T12:30:00+00:00""}"

✅ Expected success:
{
  "data": {
    "id": 5,
    "items": ["burger", "fries"],
    "pickup_time": "2025-11-06T12:30:00+00:00",
    "status": "active"
  }
}

✅ Expected at capacity:
{
  "message": "Kitchen is at capacity",
  "suggested_next_pickup_time": "2025-11-06T13:51:01+00:00"
}



🧾 3️⃣ Complete Order (POST)
curl -X POST http://localhost/api/orders/5/complete \
  -H "Accept: application/json"

✅ Expected response:
{"data":{"id":5,"status":"completed"}}


## 🧭 Manually Running the Auto-Complete Worker

The application automatically marks aged orders as **completed** every minute using the Laravel scheduler if order created is greater than 5 minutes(configurable).  
You can also manually trigger this background logic using **Artisan Tinker** or directly through a one-line command.

### 🔹 Option 1 — Run via Tinker (interactive mode)
Use this to manually test or inspect the auto-completion logic:

docker compose exec app php artisan tinker
>>> app(App\Services\OrderService::class)->autoCompleteAged();



🧩 Validation Rules
CreateOrderRequest
public function rules(): array
{
    return [
        'items' => ['required','array','min:1'],
        'items.*' => ['string'],
        'pickup_time' => [
            'required',
            'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(Z|[+\-]\d{2}:\d{2})$/'
        ],
    ];
}

public function messages(): array
{
    return [
        'pickup_time.regex' =>
            'The pickup_time must be in ISO8601 format (e.g. 2025-11-06T12:30:00Z or 2025-11-06T12:30:00+05:30).',
    ];
}

🧠 Author Notes

Developed by: Durga Kota
Role: Senior Software Engineer (PHP, MySQL, AWS, Laravel, Angular)
Project Goal: Showcase production-grade backend skills with Laravel SOLID principles, testing, and DevOps setup.