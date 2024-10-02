# Form Module

## Overview
The Form module provides functionality for creating and managing forms within the Microweber CMS. It allows users to submit data through customizable forms, which can be configured to send notifications and store entries.

## Features
- Create and manage forms with various field types (text, email, file upload, etc.)
- Automatic email notifications for form submissions
- Customizable autoresponders for users
- Validation for required fields and file uploads
- Integration with the Microweber database for storing form entries

## Models

### FormList
The `FormList` model represents a list of forms. It contains the following properties:
- `id`: The unique identifier for the form list.
- `title`: The title of the form list.
- `description`: A description of the form list.
- `created_at`: The timestamp when the form list was created.
- `formsData()`: A relationship method that retrieves all form data associated with this form list.

### FormData
The `FormData` model represents the data submitted through a form. It contains the following properties:
- `id`: The unique identifier for the form data entry.
- `rel_type`: The type of relationship (e.g., module).
- `rel_id`: The identifier of the related entity.
- `form_values`: The submitted values of the form in JSON format.
- `created_at`: The timestamp when the form data was created.
- `formDataValues()`: A relationship method that retrieves all values associated with this form data entry.

### FormDataValue
The `FormDataValue` model represents individual values submitted in a form. It contains the following properties:
- `id`: The unique identifier for the form data value.
- `form_data_id`: The identifier of the associated form data entry.
- `field_type`: The type of the field (e.g., text, email).
- `field_key`: The key of the field.
- `field_name`: The name of the field.
- `field_value`: The value submitted for the field.
- `field_value_json`: The JSON representation of the field value.

### FormRecipient
The `FormRecipient` model represents recipients of form submissions. It contains the following properties:
- `id`: The unique identifier for the form recipient.
- `name`: The name of the recipient.
- `email`: The email address of the recipient.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter Form
```
