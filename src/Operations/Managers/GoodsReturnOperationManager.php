<?php

namespace App\Operations\Managers;

use App\Dto\MessageResultDto;
use App\Operations\Dto\GoodsReturnResultDto;
use SplSubject;
use SplObjectStorage;

final class GoodsReturnOperationManager implements SplSubject
{
    private SplObjectStorage $observers;

    private GoodsReturnResultDto $result;

    public function __construct(
        private readonly array $data
    ) {
        $this->observers = new SplObjectStorage();
        $this->result = new GoodsReturnResultDto();
    }

    public function setEmployeeMailNotification(MessageResultDto $dto): self
    {
        $this->result->employeeMailNotification = $dto;

        return $this;
    }

    public function setClientMailNotification(MessageResultDto $dto): self
    {
        $this->result->clientMailNotification = $dto;

        return $this;
    }

    public function setClientSmsNotification(MessageResultDto $dto): self
    {
        $this->result->clientSmsNotification = $dto;

        return $this;
    }

    public function getResult(): GoodsReturnResultDto
    {
        return $this->result;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}