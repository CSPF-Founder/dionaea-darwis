<?php

/**
 * Copyright (c) 2017 Cyber Security & Privacy Foundation Pte. Ltd.- All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Cyber Security & Privacy Foundation Pte. Ltd.
 */

namespace Core;

/**
 * Setting Page level message like Flash - using Globals instead of session
 * Class Message
 * @package Core
 */

class PageMessage
{
    public static $MESSAGE_LIST = [];

    //Message Types
    public const SUCCESS = 'success';
    public const INFO = 'info';
    public const WARNING = 'warning';
    public const DANGER = 'danger';

    public $message;
    public $type;
    public $closable;

    public function __construct($message, $type, $closable = true)
    {
        $this->message = $message;
        $this->type = $type;
        $this->closable = $closable;
    }

    /**
     * Add a message
     * @param $message
     * @param string $type
     * @param bool $closable
     */
    public static function addMessage($message, $type, $closable = true)
    {
        //Append the message to the array
        static::$MESSAGE_LIST[] = new PageMessage($message, $type, $closable);
    }

    /**
     * Get all the messages
     * @return array
     */
    public static function getMessages()
    {
        //        if(isset($GLOBALS[static::GLOBAL_VARIABLE])){
        //            $messages = $GLOBALS[static::GLOBAL_VARIABLE];
        //            unset($GLOBALS[static::GLOBAL_VARIABLE]);
        //            if($messages){
        //                return $messages;
        //            }
        //        }
        //        return [];
        return static::$MESSAGE_LIST;
    }

    /**
     * Add a message
     * @param $messages
     * @param string $type
     * @param bool $closable
     */
    public static function addMessageList($messages, $type, $closable = true)
    {
        foreach ($messages as $message) {
            static::addMessage($message, $type, $closable);
        }
    }
}
