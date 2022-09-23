"use strict";

//Imports
import * as Async from "./async.js";

//Functionality
if (session_name) {
  let current_page = "us_home";
  Async.show_products();

  // Async.send_request(`request=delete_session`);
} else {
  document.getElementById("login_button").addEventListener("click", function () {
    Async.login_func();
  });

  // !!! Still have to add Sign Up function
}
