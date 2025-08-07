
    ['.alert-success', '.alert-danger', '.alert-info', '.alert-warning'].forEach(selector => {
        setTimeout(() => {
            const alert = document.querySelector(selector);
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    });

