"use strict";

const form = {
  username: document.getElementById("username"),
  password: document.getElementById("password"),
  submit: document.getElementById("login_button"),
  message: document.getElementById("error_message"),
};

form.submit.addEventListener("click", async function () {
  try {
    const response = await fetch("includes/php/check_login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${form.username.value}&password=${form.password.value}`,
    }).then((data) => {
      if (!data.ok) {
        throw new Error("Server related error.");
      }
      return data.json();
    });

    console.log(response);
  } catch (error) {
    console.log(error);
  }
});
