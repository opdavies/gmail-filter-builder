<?php

namespace Opdavies\GmailFilterBuilder\Enum;

class FilterAction
{
    const ADD_CATEGORY = 'smartLabelToApply';
    const ADD_LABEL = 'label';
    const ALWAYS_MARK_AS_IMPORTANT = 'shouldAlwaysMarkAsImportant';
    const ARCHIVE = 'shouldArchive';
    const FORWARD_TO = 'forwardTo';
    const MARK_AS_READ = 'markAsRead';
    const MARK_AS_SPAM = 'shouldSpam';
    const NEVER_MARK_AS_IMPORTANT = 'shouldNeverMarkAsImportant';
    const NEVER_MARK_AS_SPAM = 'shouldNeverSpam';
    const STAR = 'shouldStar';
    const TRASH = 'shouldTrash';
}
