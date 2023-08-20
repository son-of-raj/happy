function toastnotification(toasttype,msg,title,positionClass,timeOut) 
{
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "progressBar": true,
      "preventDuplicates": false,
      "positionClass": positionClass || 'toast-top-right',
      "onclick": null,
      "showDuration": "400",
      "hideDuration": "1000",
      "timeOut": timeOut || '7000',
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
    toastr[toasttype](msg, title);
}