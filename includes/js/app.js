"use strict";

//Imports
import * as Async from "./async.js";

//Functionality
if (session_name) {
  //User home page
  let current_page = "u_home";
  Async.u_provide_functionality();
  // ! show acc_info
  Async.show_products();
  Async.show_categories();
} else {
  //Login page & Sign up page
  document.getElementById("login_button").addEventListener("click", function () {
    Async.login_func();
  });

  document.getElementById("sign_up").addEventListener("click", function () {
    const container = document.getElementsByClassName("main_container")[0];
    container.classList.remove("login_container");
    container.classList.add("signup_container");

    container.innerHTML =
      "<section class='w-login_form'> <h1 class='branding text1'>Florio<span class='text6'>flowers delivery</span></h1> <div class='login_form'> <input type='text' id='name' class='login_input text_input text5' spellcheck='false' placeholder='Real name' /> <input type='text' id='username' class='login_input text_input text5' spellcheck='false' placeholder='Username' /> <input type='password' id='password' class='login_input text_input text5' placeholder='Password' /> <input type='password' id='re_password' class='login_input text_input text5' placeholder='Re-entered password' /> <div class='error_message'></div> <button type='submit' id='signup_button' class='login_button button text5'>Sign up</button> </div> </section>";

    document.getElementById("signup_button").addEventListener("click", function () {
      Async.sign_up();
    });
  });
}
