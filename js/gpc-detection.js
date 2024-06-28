document.addEventListener("DOMContentLoaded", function () {
	if (navigator.globalPrivacyControl) {
		// Fetch settings from the plugin
		var gpcSettings =
			typeof gpcDetectionSettings !== "undefined"
				? gpcDetectionSettings
				: {};

		console.log(gpcSettings); // Debugging: Check if settings are correctly passed

		// Initialize domainsToBlock array
		var domainsToBlock = [];

		// Disable Google Analytics if enabled
		if (gpcSettings.block_google_analytics == "1") {
			console.log("Blocking Google Analytics");
			if (typeof window["ga-disable-UA-XXXXX-Y"] === "undefined") {
				window["ga-disable-UA-XXXXX-Y"] = true;
			}
			domainsToBlock.push("www.google-analytics.com");
		}

		// Disable Facebook Pixel if enabled
		if (gpcSettings.block_facebook_pixel == "1") {
			console.log("Blocking Facebook Pixel");
			if (typeof fbq === "function") {
				fbq("consent", "revoke");
			}
			domainsToBlock.push("connect.facebook.net");
		}

		// Block known tracking domains conditionally
		if (domainsToBlock.length > 0) {
			console.log("Blocking domains: ", domainsToBlock);
			blockTrackingDomains(domainsToBlock);
		}
	}
});

function blockTrackingDomains(domains) {
	if (!window.XMLHttpRequest) return;
	var originalOpen = XMLHttpRequest.prototype.open;
	XMLHttpRequest.prototype.open = function () {
		var url = arguments[1];
		if (domains.some((domain) => url.includes(domain))) {
			console.log(`Blocked request to: ${url}`);
			return;
		}
		originalOpen.apply(this, arguments);
	};
}
