function logDebug(message: string, debug: boolean = true): void {
	if (debug) {
		window.console.log(message);
	}
}

export default logDebug
