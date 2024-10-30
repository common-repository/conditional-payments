const { PAYMENT_STORE_KEY } = window.wc.wcBlocksData; // "wc/store/payment"
const { CART_STORE_KEY }= window.wc.wcBlocksData;
const { extensionCartUpdate } = window.wc.blocksCheckout;
const { getSetting } = window.wc.wcSettings;
const { subscribe, select } = wp.data;

let previouslyChosenPaymentMethod = getSetting('dscpwChosenPaymentMethod', '');
let previousBillingData = select(CART_STORE_KEY).getCustomerData();

let previousCountry = previousBillingData.billingAddress.country;
let previousShippingCountry = previousBillingData.shippingAddress.country;

subscribe(function () {
	const chosenPaymentMethod = select(PAYMENT_STORE_KEY).getActivePaymentMethod();
	const selectedActiveToken = select(PAYMENT_STORE_KEY).getActiveSavedToken();
	const currentBillingData = select(CART_STORE_KEY).getCustomerData();
    const currentCountry = currentBillingData.billingAddress.country;
    const currentShippingCountry = currentBillingData.shippingAddress.country;

	// Check if payment method changed
	if (selectedActiveToken !== '') {
		previouslyChosenPaymentMethod = '';
		extensionCartUpdate({
			namespace: 'dscpw-chosen-payment-method',
			data: { method: '' },
		});
		return;
	}

	if (chosenPaymentMethod !== previouslyChosenPaymentMethod) {
		previouslyChosenPaymentMethod = chosenPaymentMethod;
		extensionCartUpdate({
			namespace: 'dscpw-chosen-payment-method',
			data: { 
				method: chosenPaymentMethod,
			},
		});
	}

	// Check if billing data changed
	if (previousCountry !== currentCountry || previousShippingCountry !== currentShippingCountry) {
		previousCountry = currentCountry;
        previousShippingCountry = currentShippingCountry;

		extensionCartUpdate({
			namespace: 'dscpw-chosen-payment-method',
			data: { 
				method: chosenPaymentMethod, 
				billingData: currentBillingData 
			},
		});
	}
}, [PAYMENT_STORE_KEY, CART_STORE_KEY]);
