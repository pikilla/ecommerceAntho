var checkoutButton = document.getElementById("checkout-button");
var stripe = Stripe(
	"pk_test_51Ided6HGf1iXCRKtDXqEKjdqgunzu2Ya8Kdaugi2oNSOF7QB5vAMUWskP3HlU1rCrkBxF1dkqQTdkIi7E3zgypu600vPPHCBod"
);

checkoutButton.addEventListener("click", function () {
	// Create a new Checkout Session using the server-side endpoint you
	// created in step 3.
	fetch("/create-checkout-session", {
		method: "POST",
	})
	.then(function (response) {
		
		console.log(response);
		return response.json();
	})
	.then(function (session) {
		
			return stripe.redirectToCheckout({ sessionId: session[0].id });
		})
		.then(function (result) {
			// If `redirectToCheckout` fails due to a browser or network
			// error, you should display the localized error message to your
			// customer using `error.message`.
			if (result.error) {
				alert(result.error.message);
			}
		})
		.catch(function (error) {
			console.error("Error:", error);
		});
});