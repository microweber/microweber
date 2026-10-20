<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Elements\Concerns\HasAutofocusAttribute;
use Arcanedev\Html\Elements\Concerns\HasNameAttribute;
use Arcanedev\Html\Elements\Concerns\HasRequiredAttribute;

/**
 * Class     File
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class File extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasAutofocusAttribute;
    use HasNameAttribute;
    use HasRequiredAttribute;

    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    public const ACCEPT_AUDIO = 'audio/*';
    public const ACCEPT_VIDEO = 'video/*';
    public const ACCEPT_IMAGE = 'image/*';

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'input';

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * File constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->getAttributes()->set('type', 'file');
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add the accept attribute.
     *
     * @return $this
     */
    public function accept(string $type): static
    {
        return $this->attribute('accept', $type);
    }

    /**
     * Add the accept attribute (audios).
     *
     * @return $this
     */
    public function acceptAudio(): static
    {
        return $this->accept(self::ACCEPT_AUDIO);
    }

    /**
     * Add the accept attribute (videos).
     *
     * @return $this
     */
    public function acceptVideo(): static
    {
        return $this->accept(self::ACCEPT_VIDEO);
    }

    /**
     * Add the accept attribute (images).
     *
     * @return $this
     */
    public function acceptImage(): static
    {
        return $this->accept(self::ACCEPT_IMAGE);
    }

    /**
     * Add the multiple attribute.
     *
     * @return $this
     */
    public function multiple(): static
    {
        return $this->attribute('multiple');
    }
}
