document.addEventListener('DOMContentLoaded', function() {
    const balance = document.getElementById('balance-amount');

    balance.addEventListener('click', function() {
        balance.classList.toggle('visible');
    });
});

