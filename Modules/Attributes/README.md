# Attributes Module for Microweber CMS



### Attribute

The `Attribute` model is defined in `Models/Attribute.php`. 

#### Attributes

- `attribute_name`: The name of the attribute.
- `attribute_value`: The value of the attribute.
- `rel_type`: The type of relation.
- `rel_id`: The ID of the related entity.
- `attribute_type`: The type of the attribute.
- `session_id`: The session ID associated with the attribute.
- `created_at`: Timestamp for when the attribute was created.
- `updated_at`: Timestamp for when the attribute was last updated.
- `created_by`: The user who created the attribute.
- `edited_by`: The user who last edited the attribute.

## Repositories

### AttributesManager.php

The `AttributesManager` class is defined in `Repositories/AttributesManager.php`. It provides methods to manage attributes:

- `getValues($params)`: Retrieves attribute values based on the provided parameters.
- `get($data)`: Fetches attributes based on various criteria.
- `save($data)`: Saves or updates an attribute.
- `delete($data)`: Deletes an attribute based on the provided criteria.



## License

This module is licensed under the MIT License.
