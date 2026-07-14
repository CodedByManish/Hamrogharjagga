# Register User API

- **URL:** `/api/auth/register.php`
- **Method:** `POST`
- **Headers:** `Content-Type: application/json`

### Request Input (JSON)
```json
{
  "name": "Sita Thapa",
  "email": "sita@gmail.com",
  "password": "password123",
  "confirm_password": "password123",
  "registerRole": "buyer"
}