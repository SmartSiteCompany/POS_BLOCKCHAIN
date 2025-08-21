document.addEventListener("DOMContentLoaded", function() {
    const amountInput = document.getElementById("amount");
    const userIdInput = document.getElementById("user_id");
    const paymentMethodSelect = document.getElementById("payment_method");

    const subtotalEl = document.getElementById("subtotal");
    const cashbackLabelEl = document.getElementById("cashback-label");
    const cashbackEl = document.getElementById("cashback");
    const finalBalanceEl = document.getElementById("final-balance");

    function updateResumen() {
        const amount = parseFloat(amountInput.value) || 0;
        const userId = userIdInput.value.trim();
        const paymentMethod = paymentMethodSelect.value;

        let cashback = 0;

        // Aplica cashback solo si hay user_id y pago en efectivo
        if (userId !== "" && paymentMethod === "Efectivo") {
            cashback = amount * 0.10;
            cashbackLabelEl.textContent = "Cashback (10%):";
            cashbackEl.textContent = `$${cashback.toFixed(2)}`;
        } else {
            cashback = 0;
            cashbackLabelEl.textContent = "Cashback:";
            cashbackEl.textContent = `$${cashback.toFixed(2)}`;
        }

        // El saldo final siempre es el mismo que el subtotal
        subtotalEl.textContent = `$${amount.toFixed(2)}`;
        finalBalanceEl.textContent = `$${amount.toFixed(2)}`;
    }

    amountInput.addEventListener("input", updateResumen);
    userIdInput.addEventListener("input", updateResumen);
    paymentMethodSelect.addEventListener("change", updateResumen);

    // Inicializa
    updateResumen();
});

