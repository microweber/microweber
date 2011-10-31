$("body").prepend('<ul id="notifications"></ul>');

/**
 * Global notification system
 *
 * @param  String      Message to be displayed
 * @param  String      Type of notification
 *
 * @author    Bram Jetten
 * @version    28-03-2011
 */
Notification.fn = Notification.prototype;

function Notification(value, type, tag) {
  this.log(value, type);
  this.element = $('<li><span class="image '+ type +'"></span>' + value + '</li>');
  if(typeof tag !== "undefined") {
    $(this.element).append('<span class="tag">' + tag + '</span>');
  }
  $("#notifications").append(this.element);
  this.show();
}

/**
 * Show notification
 */
Notification.fn.show = function() {
  $(this.element).slideDown(200);
  $(this.element).click(this.hide);

  
  $(this.element).fadeOut(10000);
  
  
  
}

/**
 * Hide notification
 */
Notification.fn.hide = function() {  
  $(this).animate({opacity: .01}, 200, function() {
    $(this).slideUp(200, function() {
      $(this).remove();
    });
  });
}

/**
 * Log notification
 * 
 * @param  String      Message to be logged
 * @param  String      Type of notification
 */
Notification.fn.log = function(value, type) {
  switch(type) {
    case "information":
      console.info("*** " + value + " ***");
      break;
    case "success":
      console.log(value);
      break;
    case "warning":
      console.warn(value);
      break;
    case "error":
      console.error(value);
      break;
    case "saved":
      console.log(value);
      break;
    default:
      console.log(value);
      break;
  }
}
