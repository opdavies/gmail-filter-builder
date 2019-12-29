<?php

namespace Opdavies\GmailFilterBuilder\Enum;

class FilterCondition
{
    const DOES_NOT_HAVE_WORD = 'doesNotHaveTheWord';
    const EXCLUDE_CHATS = 'excludeChats';
    const FROM = 'from';
    const FROM_LIST = 'list';
    const HAS_ATTACHMENT = 'hasAttachment';
    const HAS_WORD = 'hasTheWord';
    const SUBJECT = 'subject';
    const TO = 'to';
}
