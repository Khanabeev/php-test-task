<?php

namespace App\Operations\Templates;

use App\Dto\EmailTemplateDto;
use function App\Operations\__;

class GoodsReturnComplaintEmployeeEmailTemplate extends GoodsReturnComplaintEmailTemplate
{
    public function render(array $data): EmailTemplateDto
    {
        $preparedData = $this->prepare($data);

        $resellerId = $data['resellerId'];

        return new EmailTemplateDto(
            __('complaintEmployeeEmailSubject', $preparedData, $resellerId),
            __('complaintEmployeeEmailBody', $preparedData, $resellerId)
        );
    }
}