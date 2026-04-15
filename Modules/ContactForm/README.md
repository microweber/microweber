# ContactForm

Contact form builder module. Renders customizable contact forms with Alpine.js interactivity, email notifications, and submission storage.

## Key Features

- Drag-and-drop form builder
- Form submission storage in database
- Email notification on submission
- Alpine.js powered frontend interactivity
- Configurable form settings per instance
- Submission management in admin panel

## Key Classes

| Class | Purpose |
|---|---|
| `Models\Form` | Form submission record |
| `Microweber\ContactFormModule` | Microweber module registration |

## Database Tables

- `forms` -- form submission records

## Admin Panel (Filament)

- **ContactFormModuleSettings** -- form configuration and submission viewing

## Routes

Defined in `routes/web.php` for form submission handling.

## Usage

```html
<!-- Place a contact form on any page -->
<module type="contact_form" />
```

The module automatically injects `contact-form-alpine.js` into the page head for frontend form handling. Form submissions are stored in the database and can trigger email notifications.
