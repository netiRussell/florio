"use strict";

//Imports
import * as Async from "./async.js";

//Functionality
let current_page = "login";

if (session_name) {
  Async.u_transition_home();
  current_page = "u_home";

  // Async.send_request(`request=delete_session`);
} else {
  document.getElementById("login_button").addEventListener("click", function () {
    Async.login_func();
  });
}
