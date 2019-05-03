<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Tightenco\Collect\Support\Collection;

class Filter
{
    /** @var string */
    const SEPARATOR = '|';

    /**
     * @var array
     */
    private $properties = [];

    private $conditions;

    public function __construct()
    {
        $this->conditions = collect();
    }

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function has(string $value): self
    {
        $this->conditions->push(new FilterCondition('hasTheWord', $value));

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot(string $value): self
    {
        $this->conditions->push(new FilterCondition('doesNotHaveTheWord', $value));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function from($values): self
    {
        $this->conditions->push(new FilterCondition('from', $values));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function to($values): self
    {
        $this->conditions->push(new FilterCondition('to', $values));

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function subject($values): self
    {
        $this->conditions->push(new FilterCondition(
            'subject',
            collect($values)->map('json_encode')
        ));

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAttachment(): self
    {
        $this->conditions->push(new FilterCondition('hasAttachment', 'true'));

        return $this;
    }

    /**
     * Filter a message if it was sent from a mailing list.
     *
     * @param string|array $value The mailing list address
     *
     * @return $this
     */
    public function fromList($value): self
    {
        $this->has("list:{$value}");

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeChats(): self
    {
        $this->conditions->push(new FilterCondition('excludeChats', 'true'));

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label(string $label): self
    {
        $this->properties['label'] = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function archive(): self
    {
        $this->properties['shouldArchive'] = 'true';

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function labelAndArchive(string $label): self
    {
        $this->label($label)->archive();

        return $this;
    }

    /**
     * @return $this
     */
    public function spam(): self
    {
        $this->properties['shouldSpam'] = 'true';
        $this->properties['shouldNeverSpam'] = 'false';

        return $this;
    }

    /**
     * @return $this
     */
    public function neverSpam(): self
    {
        $this->properties['shouldSpam'] = 'false';
        $this->properties['shouldNeverSpam'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function trash(): self
    {
        $this->properties['shouldTrash'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function read(): self
    {
        $this->properties['markAsRead'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function star(): self
    {
        $this->properties['shouldStar'] = 'true';

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward(string $to): self
    {
        $this->properties['forwardTo'] = $to;

        return $this;
    }

    /**
     * @return $this
     */
    public function important(): self
    {
        $this->properties['shouldAlwaysMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function notImportant(): self
    {
        $this->properties['shouldNeverMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise(string $category): self
    {
        $this->properties['smartLabelToApply'] = $category;

        return $this;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     * @deprecated toArray()
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Return the filter properties as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->properties;
    }

    public function getConditions(): Collection
    {
        return $this->conditions;
    }
}
