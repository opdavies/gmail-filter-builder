<?php

namespace Opdavies\GmailFilterBuilder\Model;

class Filter
{
    /** @var string */
    const SEPARATOR = '|';

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @var array
     */
    private $actions = [];

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
        $this->conditions['hasTheWord'] = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot(string $value): self
    {
        $this->conditions['doesNotHaveTheWord'] = $value;

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function from($values): self
    {
        if (!empty($values)) {
            $this->conditions['from'] = collect($values)->map(function ($value) {
                return trim($value);
            })->all();
        }

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function to($values): self
    {
        if (!empty($values)) {
            $this->conditions['to'] = collect($values)->map(function ($value) {
                return trim($value);
            })->all();
        }

        return $this;
    }

    /**
     * @param string|array $values
     *
     * @return $this
     */
    public function subject($values): self
    {
        $this->conditions['subject'] = collect($values)->map(function ($value) {
            return json_encode($value);
        })->implode('|');

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAttachment(): self
    {
        $this->conditions['hasAttachment'] = 'true';

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
        $value = collect($value)->implode(self::SEPARATOR);
        $this->has("list:{$value}");

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeChats(): self
    {
        $this->conditions['excludeChats'] = 'true';

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label(string $label): self
    {
        $this->actions['label'] = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function archive(): self
    {
        $this->actions['shouldArchive'] = 'true';

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
        $this->actions['shouldSpam'] = 'true';
        $this->actions['shouldNeverSpam'] = 'false';

        return $this;
    }

    /**
     * @return $this
     */
    public function neverSpam(): self
    {
        $this->actions['shouldSpam'] = 'false';
        $this->actions['shouldNeverSpam'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function trash(): self
    {
        $this->actions['shouldTrash'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function read(): self
    {
        $this->actions['markAsRead'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function star(): self
    {
        $this->actions['shouldStar'] = 'true';

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward(string $to): self
    {
        $this->actions['forwardTo'] = $to;

        return $this;
    }

    /**
     * @return $this
     */
    public function important(): self
    {
        $this->actions['shouldAlwaysMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function notImportant(): self
    {
        $this->actions['shouldNeverMarkAsImportant'] = 'true';

        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise(string $category): self
    {
        $this->actions['smartLabelToApply'] = $category;

        return $this;
    }

    /**
     * @deprecated
     * @see toArray()
     */
    public function getProperties(): array
    {
        return $this->toArray();
    }

    public function getConditions(): array
    {
        $conditions = $this->conditions;
        ksort($conditions);

        return $conditions;
    }

    public function getActions(): array
    {
        $actions = $this->actions;
        ksort($actions);

        return $actions;
    }

    /**
     * @deprecated
     * @see getConditions()
     * @see getActions()
     */
    public function toArray(): array
    {
        return array_merge($this->conditions, $this->actions);
    }
}
