<?php

namespace App\Dto;

use App\Entity\Chat as EntityChat;

class Message {
    private string $content;
    private EntityChat $Chat;

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getChat(): EntityChat {
        return $this->Chat;
    }

    public function setChat(EntityChat $Chat): self {
        $this->Chat = $Chat;

        return $this;
    }
}