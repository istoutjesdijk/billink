<?php

namespace Balticode\Billink\Gateway\Helper;

use Balticode\Billink\Gateway\Validator\OrderDataValidator;
use Balticode\Billink\Observer\DataAssignObserver;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class SubjectReader
 * @package Balticode\Billink\Gateway\Helper
 */
class SubjectReader
{
    const INDEX_PAYMENT = 'payment';
    const INDEX_ADDITIONAL_INFO = 'additional_information';

    /**
     * @param array $subject
     * @return mixed
     */
    public function readPayment(array $subject)
    {
        if (!isset($subject[self::INDEX_PAYMENT])) {
            throw new \InvalidArgumentException('Payment object does not exists');
        }

        return $subject[self::INDEX_PAYMENT]->getPayment() ?: $subject[self::INDEX_PAYMENT];
    }

    /**
     * @param array $subject
     * @return array
     */
    public function readPaymentAdditionalInformation(array $subject)
    {
        $payment = $this->readPayment($subject);

        if (!isset($payment[self::INDEX_ADDITIONAL_INFO])) {
            return [];
        }

        return $payment[self::INDEX_ADDITIONAL_INFO];
    }

    /**
     * @param string $index
     * @param array $subject
     * @return bool|mixed
     */
    public function readPaymentAIField($index, array $subject)
    {
        $paymentAI = $this->readPaymentAdditionalInformation($subject);

        if (!isset($paymentAI[$index])) {
            return false;
        }

        return $paymentAI[$index];
    }

    /**
     * @param array $subject
     * @return mixed
     */
    public function readPaymentWorkflowType(array $subject)
    {
        $paymentAI = $this->readPaymentAdditionalInformation($subject);

        if (!isset($paymentAI[DataAssignObserver::CUSTOMER_TYPE])) {
            return false;
        }

        return $paymentAI[DataAssignObserver::CUSTOMER_TYPE];
    }

    /**
     * @param array $subject
     * @return mixed
     * @throws LocalizedException
     */
    public function readPaymentCheckUUID(array $subject)
    {
        $paymentAI = $this->readPaymentAdditionalInformation($subject);

        if (!isset($paymentAI[Gateway::CHECKUUID])) {
            throw new LocalizedException(__('Missing UUID'));
        }

        return $paymentAI[Gateway::CHECKUUID];
    }

    /**
     * @param array $subject
     * @return mixed
     */
    public function readOrder(array $subject)
    {
        return $this->readPayment($subject)->getOrder();
    }

    /**
     * @param array $subject
     * @return bool|mixed
     */
    public function readValidationFlag(array $subject)
    {
        if (!isset($subject[OrderDataValidator::INDEX_FLAG_VALIDATION])) {
            return false;
        }

        return $subject[OrderDataValidator::INDEX_FLAG_VALIDATION];
    }

    /**
     * @param array $subject
     * @return mixed
     */
    public function readResponse(array $subject)
    {
        if (!isset($subject['response']) && !isset($subject['response']['result'])) {

            if (isset($subject['result'])) {
                return $subject['result'];
            }

            throw new \InvalidArgumentException('Response data does not exists');
        }

        return $subject['response']['result'];
    }
}