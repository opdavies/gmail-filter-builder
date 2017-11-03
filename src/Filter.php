<?php

namespace Opdavies\GmailFilterBuilder;

class Filter
{
    public function has($value)
    {
        return ['hasTheWord' => $value];
    }

    public function from()
    {
        return ['from' =>  func_get_args()];
    }

    public function label($label)
    {
        return ['label' => $label];
    }

    public function archive()
    {
        return ['shouldArchive' => 'true'];
    }

    public function labelAndArchive($label)
    {
        return $this->label($label) + $this->archive();
    }

    public function spam()
    {
        return [
            'shouldSpam' => 'true',
            'shouldNeverSpam' => 'false',
        ];
    }

    public function neverSpam()
    {
        return [
            'shouldSpam' => 'false',
            'shouldNeverSpam' => 'true',
        ];
    }

    public function trash()
    {
        return ['shouldTrash' => 'true'];
    }
}
