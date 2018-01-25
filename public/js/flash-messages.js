$(document).ready(function () {
    var successContainer = $('.success-container');
    var errorContainer = $('.error-container');
    if(successContainer) {
        setTimeout(function () {
            successContainer.hide();
        }, 3000);
    }
    if(errorContainer) {
        setTimeout(function () {
            errorContainer.hide();
        }, 3000);
    }
});
//# sourceMappingURL=flash-messages.js.map
