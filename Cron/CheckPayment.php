<?php
namespace Visma\CustomPayment\Cron;

class CheckPayment
{
    protected $_dataExampleFactory;
    protected $_curl;
    protected $_pageFactory;

    public function __construct(
        \Visma\CustomPayment\Model\DataExampleFactory $DataExampleFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\HTTP\Client\Curl $curl
    ) {
        $this->_dataExampleFactory = $DataExampleFactory;
        $this->_pageFactory = $pageFactory;
        $this->_curl = $curl;
    }

    public function execute()
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info(__METHOD__);

        $this->checkPayment();

        return $this;

    }

    private function checkPayment ()
    {
        $post = $this->_dataExampleFactory->create();
        $collection = $post->getCollection()->addFieldToFilter('status_uri', array('eq' => "Pending"));
        foreach($collection as $item){

            $this->_curl->get($item["postback_uri"]);
            $status = $this->_curl->getStatus();

            var_dump($item["custompayment_id"]);
            var_dump($status);

                if ($status=="200"){
                    $collections = $this->_dataExampleFactory->create()->getCollection()
                            ->addFieldToFilter('custompayment_id', array('eq' => $item["custompayment_id"]));

                    foreach($collections as $oneitem)
                    {
                        $oneitem->setStatusUri('Processing');
                    }
                    $collections->save();
                }
        }
        exit();
        return $this->_pageFactory->create();

    }
}
