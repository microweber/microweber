<?php

declare(strict_types=1);

namespace Modules\Content\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class GetContentTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['view content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_content',
            'Get detailed information about a specific content item by ID.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The ID of the content item to retrieve.',
                required: true,
            ),
            new ToolProperty(
                name: 'include_meta',
                type: PropertyType::STRING,
                description: 'Include metadata and custom fields. Options: "yes" or "no". Default is "yes".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $content_id = $args['content_id'] ?? null;
        $include_meta = $args['include_meta'] ?? 'yes';

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to view content.');
        }

        if (!$content_id) {
            return $this->handleError('Content ID is required.');
        }

        try {
            $content = Content::where('id', $content_id)
                ->where('is_deleted', 0)
                ->first();

            if (!$content) {
                return $this->handleError("Content with ID {$content_id} not found or has been deleted.");
            }

            return $this->formatContentAsHtml($content, $include_meta === 'yes');

        } catch (\Exception $e) {
            return $this->handleError('Error retrieving content: ' . $e->getMessage());
        }
    }

    protected function formatContentAsHtml($content, bool $includeMeta): string
    {
        $typeBadge = $this->getContentTypeBadge($content->content_type ?? 'content');
        $statusBadge = $content->is_active ?
            "<span class='badge bg-success'>Published</span>" :
            "<span class='badge bg-warning'>Unpublished</span>";

        $specialBadges = [];
        if ($content->is_home) {
            $specialBadges[] = "<span class='badge bg-danger'>Homepage</span>";
        }
        if ($content->is_shop) {
            $specialBadges[] = "<span class='badge bg-info'>Shop Page</span>";
        }

        $badges = $typeBadge . ' ' . $statusBadge . ($specialBadges ? ' ' . implode(' ', $specialBadges) : '');

        $header = "
        <div class='content-detail-header mb-4'>
            <h3><i class='fas fa-file-alt text-primary me-2'></i>{$content->title}</h3>
            <p class='mb-2'>{$badges}</p>
        </div>";

        // Basic information
        $basicInfo = [
            ['label' => 'ID', 'value' => $content->id],
            ['label' => 'Title', 'value' => $content->title ?: 'Untitled'],
            ['label' => 'URL', 'value' => $content->url ?: 'No URL set'],
            ['label' => 'Content Type', 'value' => ucfirst($content->content_type ?? 'content')],
            ['label' => 'Subtype', 'value' => ucfirst($content->subtype ?? 'static')],
            ['label' => 'Status', 'value' => $content->is_active ? 'Published' : 'Unpublished'],
        ];

        if ($content->parent) {
            $parentContent = Content::find($content->parent);
            $parentTitle = $parentContent ? $parentContent->title : "ID: {$content->parent}";
            $basicInfo[] = ['label' => 'Parent', 'value' => $parentTitle];
        }

        $basicInfo[] = ['label' => 'Created', 'value' => $content->created_at ? $content->created_at->format('M j, Y H:i') : 'Unknown'];
        $basicInfo[] = ['label' => 'Updated', 'value' => $content->updated_at ? $content->updated_at->format('M j, Y H:i') : 'Unknown'];

        if ($content->created_by) {
            $author = \MicroweberPackages\User\Models\User::find($content->created_by);
            $basicInfo[] = ['label' => 'Author', 'value' => $author ? $author->email : "User ID: {$content->created_by}"];
        }

        $basicTable = $this->formatInfoTable($basicInfo, 'Basic Information');

        // Content body
        $contentSection = '';
        if ($content->description) {
            $contentSection .= "
            <div class='content-section mb-4'>
                <h5>Description</h5>
                <p class='border p-3 bg-light'>{$content->description}</p>
            </div>";
        }

        if ($content->content_body) {
            $bodyPreview = \Str::limit(strip_tags($content->content_body), 500);
            $contentSection .= "
            <div class='content-section mb-4'>
                <h5>Content Body <small class='text-muted'>(Preview)</small></h5>
                <div class='border p-3 bg-light'>
                    <p>{$bodyPreview}</p>
                </div>
            </div>";
        }

        // SEO information
        $seoInfo = [];
        if ($content->content_meta_title) {
            $seoInfo[] = ['label' => 'Meta Title', 'value' => $content->content_meta_title];
        }
        if ($content->content_meta_keywords) {
            $seoInfo[] = ['label' => 'Meta Keywords', 'value' => $content->content_meta_keywords];
        }

        $seoSection = $seoInfo ? $this->formatInfoTable($seoInfo, 'SEO Information') : '';

        // Additional metadata if requested
        $metaSection = '';
        if ($includeMeta) {
            $metaInfo = [];

            if ($content->position) {
                $metaInfo[] = ['label' => 'Position', 'value' => $content->position];
            }

            if ($content->original_link) {
                $metaInfo[] = ['label' => 'Redirect URL', 'value' => $content->original_link];
            }

            if ($content->require_login) {
                $metaInfo[] = ['label' => 'Requires Login', 'value' => 'Yes'];
            }

            // Check for custom fields or content data
            try {
                $customFields = $content->customFieldValues ?? collect();
                if ($customFields->isNotEmpty()) {
                    $metaInfo[] = ['label' => 'Custom Fields', 'value' => $customFields->count() . ' custom field(s)'];
                }

                // Check for categories
                $categories = $content->categories ?? collect();
                if ($categories->isNotEmpty()) {
                    $categoryNames = $categories->pluck('title')->implode(', ');
                    $metaInfo[] = ['label' => 'Categories', 'value' => $categoryNames];
                }

                // Check for tags
                $tags = $content->tags ?? collect();
                if ($tags->isNotEmpty()) {
                    $tagNames = $tags->pluck('name')->implode(', ');
                    $metaInfo[] = ['label' => 'Tags', 'value' => $tagNames];
                }

            } catch (\Exception $e) {
                // Ignore relationship errors
            }

            $metaSection = $metaInfo ? $this->formatInfoTable($metaInfo, 'Additional Information') : '';
        }

        return $header . $basicTable . $contentSection . $seoSection . $metaSection;
    }

    protected function formatInfoTable(array $info, string $title): string
    {
        if (empty($info)) {
            return '';
        }

        $rows = '';
        foreach ($info as $item) {
            $rows .= "
                <tr>
                    <td class='fw-bold' style='width: 30%;'>{$item['label']}</td>
                    <td>{$item['value']}</td>
                </tr>";
        }

        return "
        <div class='content-info-section mb-4'>
            <h5>{$title}</h5>
            <table class='table table-bordered table-sm'>
                <tbody>{$rows}</tbody>
            </table>
        </div>";
    }

    protected function getContentTypeBadge(string $contentType): string
    {
        $badgeClass = match ($contentType) {
            'page' => 'bg-primary',
            'post' => 'bg-info',
            'product' => 'bg-success',
            default => 'bg-secondary'
        };

        return "<span class='badge {$badgeClass}'>" . ucfirst($contentType) . "</span>";
    }
}
