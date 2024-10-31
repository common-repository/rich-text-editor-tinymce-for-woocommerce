import logDebug from "./logDebug";

type Callback = (element: HTMLElement) => void;

function findElementWithRetry(
	selector: string,
	callback: Callback,
	maxRetries: number = 5,
	delay: number = 1000,
): void {
	let attempts = 0;

	function tryFindElement(): void {
		// Increment the attempt counter
		attempts += 1;

		// Attempt to find the element
		const element = document.querySelector(selector) as HTMLElement;

		// Check if the element is found
		if (element) {
			logDebug('Element found: ' + element);
			if (typeof callback === 'function') {
				callback(element); // Call the callback function with the found element
			}
			return; // Exit the function if the element is found
		}

		// Check if maximum retries have been reached
		if (attempts < maxRetries) {
			logDebug(`Attempt ${attempts} failed. Retrying in ${delay / 1000} seconds...`);
			setTimeout(tryFindElement, delay); // Retry after the specified delay
		} else {
			logDebug('Max retries reached. Element not found.');
		}
	}

	tryFindElement();
}

export default findElementWithRetry

/**
 * Usage example: find an element with the class 'my-element' and log it
 * findElementWithRetry('.my-element', (element) => {
 * 	console.log('Callback triggered with element:', element);
 * }, 5, 1000);
 */
