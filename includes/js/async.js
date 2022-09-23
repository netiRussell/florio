export async function send_request(request_body) {
  return await fetch("includes/php/requests.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: request_body,
  });
}

export async function show_products() {
  const container = document.getElementsByClassName("content_userhome")[0];

  try {
    const response = await send_request("request=show_products").then((data) => {
      if (!data) {
        throw new Error("Server related error (Can not retrieve data of products)");
      }

      return data.json();
    });

    container.insertAdjacentHTML("beforeend", response.html);
  } catch (error) {
    console.log(error);
  }
}

/** Log in functionality */
function u_transition_home() {
  const container = document.getElementsByClassName("main_container")[0];
  container.classList.remove("login_container");
  container.classList.add("actual_container");
  container.innerHTML = "";

  show_products();
}

export async function login_func() {
  const login_form = {
    username: document.getElementById("username"),
    password: document.getElementById("password"),
    message: document.getElementById("error_message"),
  };

  let data = null;

  try {
    // main functionality
    const response = await fetch("includes/php/check_login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${login_form.username.value}&password=${login_form.password.value}`,
    }).then((data) => {
      if (!data.ok) {
        throw new Error("Server related error.");
      }
      return data.json();
    });

    // Succesful login - transfer to home page
    if (response.ok === true) {
      console.log(response); // delete !!!

      //Init session
      send_request(`request=init_session&id=${response.id}&name=${response.name}`);

      // Transformation of html
      if (response.role === "user") {
        u_transition_home();
      } else if (response.role === "admin") {
        // open admin home page
      }
    } else {
      throw new Error(response.message);
    }
  } catch (error) {
    // Error handling
    document.getElementsByClassName("error_message")[0].innerHTML = `<p class="p_error text7">${error}</p>`;
  }
}
