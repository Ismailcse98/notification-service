# Notification Service (Laravel Microservice)

## Overview

This project is a **Laravel 10 based Notification Microservice** designed for a scalable microservice ecosystem.

It supports:

- SMS / Email / WhatsApp notifications (simulated)
- Asynchronous processing using Redis Queue
- AI-ready structured logging
- JWT authentication
- Scalable architecture (50K+ notifications/day ready)

---

### Patterns Used:

- Controller → Service → Repository
- DTO (Data Transfer Object)
- Job (Async Processing)
- Event & Listener (Kafka Mock)
- Circuit Breaker Pattern

---

## Tech Stack

- Laravel 10
- PHP 8.2
- MySQL 8
- Redis (Queue)
- Docker & Docker Compose
- JWT Auth (tymon/jwt-auth)

---

## Environment Setup

### 1. Clone Repository

```bash
git clone https://github.com/Ismailcse98/notification-service.git
cd notification-service
```

### 2. Build & Run Containers

```bash
docker-compose up -d --build
```

### 3. Create `.env` File

```bash
docker exec -it notification_app cp .env.example .env
```

### 4. Install Dependencies

```bash
docker exec -it notification_app composer install
```

### 5. Generate App Key

```bash
docker exec -it notification_app php artisan key:generate
```

### 6. Run Migration

```bash
docker exec -it notification_app php artisan migrate
```

### 7. Generate JWT Secret

```bash
docker exec -it notification_app php artisan jwt:secret
```

### 8. Start Queue Worker

```bash
docker exec -it notification_queue php artisan queue:work
```

## Authentication

### Register
```bash
POST /api/v1/register
```
### Payload
```bash
{
    "name": "ismail",
    "email": "ismailbdcse@mail.com",
    "password": "ismail@123",
    "phone": "01890893098"
}
```
### Response
```bash
{
    "access_token": "JWT_TOKEN",
    "token_type": "Bearer",
    "expires_in": 3600
}
```

### Login
```bash
POST /api/v1/login
```
### Payload
```bash
{
    "email": "ismailbdcse@mail.com",
    "password": "ismail@123"
}
```

### Response
```bash
{ 
    "access_token": "JWT_TOKEN",
    "token_type": "Bearer",
    "expires_in": 3600
}
```

## Notification API

### Send Notification
```bash
POST /api/v1/notifications/send
```

### Headers
```bash
Authorization: Bearer {token}
```

### Payload
```bash
{ 
    "user_id": 101,
    "type": "sms",
    "recipient": "+8801XXXXXXXXX",
    "message": "Your bill is due",
    "metadata": 
    { 
        "campaign": "billing_reminder"
    }
}
```

### Response
```bash
{ 
    "status": "queued"
}
```

### Async Processing

- Uses Redis Queue  
- Laravel Job: SendNotificationJob  
- Status flow:
    - pending → processing → sent / failed

### Retry & Backoff

- Max Retry: 3 times 
- Backoff: 10s → 30s → 60s

### AI / Analytics

## Endpoint
```bash
GET /api/v1/analytics/training-data
```

## Output
```bash
{ 
    "notification_id": 1,
    "type": "sms",
    "status": "sent",
    "retry_count": 0,
    "response_time_ms": 120,
    "sent_at": "2026-01-01",
    "metadata": 
    { 
        "campaign": "billing"
    } 
}
```

## Dashboard API
```bash
GET /api/v1/dashboard
```

## Response
```bash
{
    "total": 0,
    "pending": "0",
    "processing": "0",
    "sent": "0",
    "failed": "0"
}
```

### Performance Optimization

- DB Indexing:
    - status
    - type
    - sent_at
- Eager Loading (avoid N+1)
- Cursor Pagination
- Query Optimization (single aggregate query)
- Redis caching (dashboard)


### Circuit Breaker
Prevents system overload when external services fail.

- After 5 failures → STOP sending
- Auto retry after cooldown (60s)


### Event-Driven (Kafka Mock)

- Event: NotificationSentEvent
- Listener logs simulated Kafka event

### Rate Limiting

- ThrottleRequests: 60 requests/minute

### Testing

## Run Tests
```bash
docker exec -it notification_app php artisan test
```

## Includes:
- Feature Test (API)
- Unit Test (DTO)

## Project Structure:
```bash
app/ 
├── Http/Controllers
├── DTO
├── Events
├── Helpers
├── Jobs
├── Listeners
├── Repositories
├── Services
```

## Summary

- Async Queue (Redis)
- Retry + Backoff
- Circuit Breaker
- JWT Secured APIs
- AI Structured Logs
- Dockerized
- Event-driven (Kafka mock)
- Feature + Unit Tests
- 50K+/day Ready
