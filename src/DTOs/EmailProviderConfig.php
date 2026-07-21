<?php

namespace DavidOghi\NotificationsSystem\DTOs;

final class EmailProviderConfig
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $mailer,
        public readonly string $host,
        public readonly int $port,
        public readonly ?string $encryption,
        public readonly ?string $username,
        public readonly ?string $password,
        public readonly string $fromAddress,
        public readonly string $fromName,
        public readonly ?string $replyToAddress = null,
        public readonly ?string $replyToName = null,
    ) {
    }

    public function toMailerConfig(): array
    {
        return [
            'transport' => $this->mailer ?: 'smtp',
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption ?: null,
            'username' => $this->username,
            'password' => $this->password,
            'timeout' => 10,
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mailer' => $this->mailer,
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'username' => $this->username,
            'password' => $this->password,
            'from_address' => $this->fromAddress,
            'from_name' => $this->fromName,
            'reply_to_address' => $this->replyToAddress,
            'reply_to_name' => $this->replyToName,
        ];
    }
}
