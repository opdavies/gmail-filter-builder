<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Opdavies\GmailFilterBuilder\Enum\FilterAction;
use Opdavies\GmailFilterBuilder\Enum\FilterCondition;

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
        $this->conditions[FilterCondition::HAS_WORD] = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function hasNot(string $value): self
    {
        $this->conditions[FilterCondition::DOES_NOT_HAVE_WORD] = $value;

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
            $this->conditions[FilterCondition::FROM] = collect($values)->map(function ($value) {
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
            $this->conditions[FilterCondition::TO] = collect($values)->map(function ($value) {
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
        $this->conditions[FilterCondition::SUBJECT] = collect($values)->map(function ($value) {
            return json_encode($value);
        })->implode('|');

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAttachment(): self
    {
        $this->conditions[FilterCondition::HAS_ATTACHMENT] = 'true';

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
        $this->has(FilterCondition::FROM_LIST . ":{$value}");

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeChats(): self
    {
        $this->conditions[FilterCondition::EXCLUDE_CHATS] = 'true';

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function label(string $label): self
    {
        $this->actions[FilterAction::ADD_LABEL] = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function archive(): self
    {
        $this->actions[FilterAction::ARCHIVE] = 'true';

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
        $this->actions[FilterAction::MARK_AS_SPAM] = 'true';
        $this->actions[FilterAction::NEVER_MARK_AS_SPAM] = 'false';

        return $this;
    }

    /**
     * @return $this
     */
    public function neverSpam(): self
    {
        $this->actions[FilterAction::MARK_AS_SPAM] = 'false';
        $this->actions[FilterAction::NEVER_MARK_AS_SPAM] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function trash(): self
    {
        $this->actions[FilterAction::TRASH] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function read(): self
    {
        $this->actions[FilterAction::MARK_AS_READ] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function star(): self
    {
        $this->actions[FilterAction::STAR] = 'true';

        return $this;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function forward(string $to): self
    {
        $this->actions[FilterAction::FORWARD_TO] = $to;

        return $this;
    }

    /**
     * @return $this
     */
    public function important(): self
    {
        $this->actions[FilterAction::ALWAYS_MARK_AS_IMPORTANT] = 'true';

        return $this;
    }

    /**
     * @return $this
     */
    public function notImportant(): self
    {
        $this->actions[FilterAction::NEVER_MARK_AS_IMPORTANT] = 'true';

        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function categorise(string $category): self
    {
        $this->actions[FilterAction::ADD_CATEGORY] = $category;

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
