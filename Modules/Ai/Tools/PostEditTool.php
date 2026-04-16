<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

class PostEditTool extends ContentEditTool
{
    protected string $domain = 'content';
    protected string $contentType = 'post';
    protected array $requiredPermissions = ['edit content'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct($dependencies);

        $this->setName('post_edit');
        $this->setDescription('Edit existing blog posts in Microweber CMS including updating title, content, description, status, and custom fields.');
    }
}
