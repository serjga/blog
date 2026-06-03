<?php
namespace App\Alert;

use App\Session\Session;

trait AlertTrait
{
    private const string ALERT_MESSAGES = 'alert_messages';
    private const string ERROR_TYPE = 'error';
    private const string INFO_TYPE = 'info';

    public function withErrorMessage(string $message): self
    {
        $text = trim($message);
        if (!empty($text)) {
            $messageList = $this->_getMessageList();
            $messageList[] = ['message' => $text, 'type' => self::ERROR_TYPE];
            Session::setTemporary(self::ALERT_MESSAGES, $messageList);
        }
        return $this;
    }

    public function withInfoMessage(string $message): self
    {
        $text = trim($message);
        if (!empty($text)) {
            $messageList = $this->_getMessageList();
            $messageList[] = ['message' => $text, 'type' => self::INFO_TYPE];
            Session::setTemporary(self::ALERT_MESSAGES, $messageList);
        }
        return $this;
    }

    public function getAlertMessages($type = null): array
    {
        $messages = [];
        if (Session::hasTemporary(self::ALERT_MESSAGES)) {
            $messages = Session::getTemporary(self::ALERT_MESSAGES);
            Session::forget(self::ALERT_MESSAGES);

            if (!is_array($messages)) {
                return [];
            }
        }
        if ($type) {
            return array_filter($messages, fn($message) => $message['type'] === $type);
        }

        return $messages;
    }

    public function getAlertMessagesJson($type = null): ?string
    {
        return json_encode($this->getAlertMessages($type));
    }

    private function _getMessageList(): array
    {
        $messageList = [];
        if (Session::hasTemporary(self::ALERT_MESSAGES)) {
            $messages = Session::getTemporary(self::ALERT_MESSAGES);
            if (is_array($messages)) {
                $messageList = $messages;
            }
        }
        return $messageList;
    }
}
