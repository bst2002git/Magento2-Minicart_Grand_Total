<?php
declare(strict_types=1);

namespace Rhs\Minicart\Plugin\Checkout\CustomerData;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Quote\Model\Quote;

class CartTotals
{
    /**
     * @var CheckoutSession $checkoutSession
     */
    protected $checkoutSession;

    /**
     * @var CheckoutHelper $checkoutHelper
     */
    protected $checkoutHelper;

    /**
     * @var null $quote
     */
    protected $quote = null;

    /**
     * CartTotals constructor.
     * @param CheckoutSession $checkoutSession
     * @param CheckoutHelper $checkoutHelper
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CheckoutHelper $checkoutHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
    }

    /**
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param $result
     * @return mixed
     */
    public function afterGetSectionData(
        \Magento\Checkout\CustomerData\Cart $subject,
        $result
    ) {
        $totals = $this->getQuote()->getTotals();
        $result['grand_total'] = isset($totals['grand_total'])
            ? $this->checkoutHelper->formatPrice($totals['grand_total']->getValue())
            : 0;
        return $result;
    }

    /**
     * @return Quote|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }
}
