let responseMessge = (resType, resTitle, resMessage) => {

    return `<div class="alert alert-${resType} mt-3" role="alert">
                ${resTitle}! ${resMessage}
            </div>`;

}

let response_return = (type, title, message, modal_class, table_class, form_class) => {

    // console.log(type, title, message, modal_class, table_class, form_class)

    toastr.options = {
        "closeButton": true,      
        "debug": false,
        "newestOnTop": false,     // Display newest messages on the bottom
        "progressBar": true,      // Show progress bar
        "positionClass": "toast-bottom-left", // Position at the bottom-left
        // "preventDuplicates": true,
        "showDuration": "300",    // Show duration in ms
        "hideDuration": "1000",   // Hide duration in ms
        "timeOut": "3000",        // How long the toast will be visible
        "extendedTimeOut": "1000" // Time out after mouse hover
    };
    

    if(type == "success") {
        toastr.success(message, title);
        $(`.${modal_class}`).modal("hide");
        $(`.${form_class}`).trigger("reset");
        $(`.${table_class}`).DataTable().ajax.reload(null,false);
    
    }else if(type == "error")  {
        toastr.error(message, title);
    }else {
        toastr.warning(message, title);
    }
}

class AutoCapitalize {
    constructor(selector) {
        this.inputs = document.querySelectorAll(selector);
        this.addListeners();
    }

    addListeners() {
        this.inputs.forEach(input => {
            input.addEventListener('input', this.capitalizeText);
        });
    }

    capitalizeText(event) {
        event.target.value = event.target.value.toUpperCase();
    }
}
class AutoLowercase {
    constructor(selector) {
        this.inputs = document.querySelectorAll(selector);
        this.addListeners();
    }

    addListeners() {
        this.inputs.forEach(input => {
            input.addEventListener('input', this.lowercaseText);
        });
    }

    lowercaseText(event) {
        event.target.value = event.target.value.toLowerCase();
    }
}

// Usage
document.addEventListener('DOMContentLoaded', () => {
    new AutoCapitalize('.capitalizeAllLetters');
});



// Usage
document.addEventListener('DOMContentLoaded', () => {
    new AutoLowercase('.lowercaseAllLetters');
});
