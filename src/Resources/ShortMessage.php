<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Resources;

class ShortMessage extends BaseResource
{
    private string $id;
    private string $createdAt;
    private ?string $from = null;
    private string $to;
    private string $message;
    private bool $gsmEncoded;
    private bool $multiPartSms;
    private int $numberOfParts;
    private bool $senderIsPhoneNumber;
    private string $status;
    private int $pricePerPartsInCredits;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isGsmEncoded(): bool
    {
        return $this->gsmEncoded;
    }

    public function setGsmEncoded(bool $gsmEncoded): self
    {
        $this->gsmEncoded = $gsmEncoded;

        return $this;
    }

    public function isMultiPartSms(): bool
    {
        return $this->multiPartSms;
    }

    public function setMultiPartSms(bool $multiPartSms): self
    {
        $this->multiPartSms = $multiPartSms;

        return $this;
    }

    public function getNumberOfParts(): int
    {
        return $this->numberOfParts;
    }

    public function setNumberOfParts(int $numberOfParts): self
    {
        $this->numberOfParts = $numberOfParts;

        return $this;
    }

    public function isSenderIsPhoneNumber(): bool
    {
        return $this->senderIsPhoneNumber;
    }

    public function setSenderIsPhoneNumber(bool $senderIsPhoneNumber): self
    {
        $this->senderIsPhoneNumber = $senderIsPhoneNumber;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPricePerPartsInCredits(): int
    {
        return $this->pricePerPartsInCredits;
    }

    public function setPricePerPartsInCredits(int $pricePerPartsInCredits): self
    {
        $this->pricePerPartsInCredits = $pricePerPartsInCredits;

        return $this;
    }
}
