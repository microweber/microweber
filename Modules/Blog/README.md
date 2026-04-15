# Blog

Blog content display module. Renders blog post listings with filtering, pagination, and configurable layouts as a Livewire component.

## Key Features

- Blog post listing with configurable display
- Frontend filtering support
- Livewire-based rendering for dynamic updates
- Configurable blog settings via admin panel
- Integrates with the Content module for post data

## Key Classes

| Class | Purpose |
|---|---|
| `Livewire\BlogComponent` | Blog display component (tag: `module-blog`) |
| `Microweber\BlogModule` | Microweber module registration |
| `FrontendFilter\*` | Frontend filtering logic |

## Admin Panel (Filament)

- **BlogSettings** -- blog display and behavior configuration

## Usage

```html
<!-- Display blog posts on a page -->
<module type="blog" />
```

The Blog module uses the Content module's data layer. Blog posts are content items with `content_type = 'post'` and `subtype = 'post'`. Configure display options through the BlogSettings admin page.
