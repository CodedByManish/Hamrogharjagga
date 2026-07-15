# Request Password Reset

Triggers a password reset request and sends a 6-digit OTP code to the provided email.

**Endpoint:** `POST /api/auth/reset_request.php`

## Request

```json
{
  "email": "manish@example.com"
}
```

## Response

```json
{
  "success": true,
  "message": "OTP code sent successfully.",
  "data": {
    "email": "manish@example.com"
  },
  "redirectTo": null
}
```