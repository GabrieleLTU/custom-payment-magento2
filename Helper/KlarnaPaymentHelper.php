<?php
/**
 * Created by PhpStorm.
 * User: gabriele
 * Date: 8/10/18
 * Time: 1:27 PM
 */

namespace Visma\CustomPayment\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\LayoutFactory;

class KlarnaPaymentHelper extends \Magento\Payment\Helper\Data
{

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        LayoutFactory $layoutFactory,
        \Magento\Payment\Model\Method\Factory $paymentMethodFactory,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Framework\App\Config\Initial $initialConfig
    )
    {
        parent::__construct($context, $layoutFactory, $paymentMethodFactory, $appEmulation, $paymentConfig, $initialConfig);
    }

    public function createNewOrder(array $paymentData)
    {
        $ch = curl_init();
        $merchant_id = $this->getMerchantId();//$this->_scopeConfig->getValue('payment/custompayment/api/merchant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $authorizationData = $this->getAuthorizationData();

        curl_setopt($ch, CURLOPT_URL, "https://buy.playground.klarna.com/v1/{$merchant_id}/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $value = json_encode($paymentData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic $authorizationData"
        ));

        $response = curl_exec($ch);
        //var_dump($value);
        //var_dump($response);
        //$info = curl_getinfo($ch);
        //var_dump($info);
        //die();
        curl_close($ch);
        return ($response);
    }

    public function cancelOrder(string $klarnaorderID)
    {
        $ch = curl_init();
        $merchant_id = $this->getMerchantId();
        $authorizationData = $this->getAuthorizationData();
        curl_setopt($ch, CURLOPT_URL, "https://buy.playground.klarna.com/v1/{$merchant_id}/orders/{$klarnaorderID}/cancel");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{}
");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic $authorizationData"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }

    private function getMerchantId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'payment/custompayment/api/merchant_id',
            $scope
        );
    }

    private function getAuthorizationData($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        $merchant_id = $this->getMerchantId();
        $shared_secret = $this->scopeConfig->getValue(
            'payment/custompayment/api/shared_secret',
            $scope
        );
        return base64_encode ($merchant_id . ':' . $shared_secret);
    }

}
