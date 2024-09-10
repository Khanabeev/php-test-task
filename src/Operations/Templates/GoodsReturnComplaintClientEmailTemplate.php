<?php

namespace App\Operations\Templates;

use App\Dto\EmailTemplateDto;
use function App\Operations\__;

class GoodsReturnComplaintClientEmailTemplate extends GoodsReturnComplaintEmailTemplate
{
    public function render(array $data): EmailTemplateDto
    {
        $preparedData = $this->prepare($data);

        $resellerId = $data['resellerId'];

        return new EmailTemplateDto(
            __('complaintClientEmailSubject', $preparedData, $resellerId),
            __('complaintClientEmailBody', $preparedData, $resellerId)
        );
    }
}