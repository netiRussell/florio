/** Local needs */
const modal_objs = {
  modal: document.getElementById("modal"),
  overlay: document.getElementById("overlay"),
};

const close_modal = function (action) {
  modal_objs.modal.classList.add("hidden");
  modal_objs.overlay.classList.add("hidden");
  action();
};

const open_modal = function (text, action = function () {}) {
  modal_objs.modal.classList.remove("hidden");
  modal_objs.overlay.classList.remove("hidden");
  modal_objs.modal.querySelector("p").innerText = text;

  modal_objs.modal.querySelector("button").addEventListener("click", function () {
    close_modal(action);
  });

  document.addEventListener("keydown", function (event) {
    if ((event.key.toLowerCase() === "escape" || event.key.toLowerCase() === "esc") && !modal_objs.modal.classList.contains("hidden")) {
      close_modal();
    }
  });
};

const send_request = async function (request_body) {
  return await fetch("includes/php/requests.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: request_body,
  });
};

const provide_func_product = function () {
  const objs = {
    increase: document.getElementById("increase_quantity"),
    decrease: document.getElementById("decrease_quantity"),
    input: document.getElementById("quantity_value"),
    product_price_obj: document.getElementById("product_price"),
    product_price: parseFloat(document.getElementById("product_price").innerText.slice(1)),
    button: document.getElementById("product_order"),
    multiply: function () {
      this.product_price_obj.innerText = "$" + (this.input.value * this.product_price).toFixed(2);
    },
  };

  objs.increase.addEventListener("click", function () {
    if (!isNaN(objs.input.value)) {
      objs.input.value = parseFloat(objs.input.value) + 1;
      if (objs.input.value >= 1) {
        objs.multiply();
      }
    }
  });

  objs.decrease.addEventListener("click", function () {
    if (!isNaN(objs.input.value)) {
      objs.input.value = parseFloat(objs.input.value) - 1;
      if (objs.input.value >= 1) {
        objs.multiply();
      }
    }
  });

  objs.input.addEventListener("input", function () {
    if (!isNaN(objs.input.value) && objs.input.value >= 1) {
      objs.multiply();
    }
  });

  // add to cart
  objs.button.addEventListener("click", async function () {
    try {
      const response = await send_request("request=product_to_cart&product=" + document.querySelector(".w-product_info").id + "&quantity=" + objs.input.value).then((data) => {
        return data.json();
      });

      if (!response.status) {
        throw new Error("Server related problem(php)");
      }

      open_modal("The product has been added to your cart", function () {
        window.location.reload();
      });
    } catch (error) {
      open_modal(error);
    }
  });
};

/** Export */
export function u_provide_functionality() {
  document.getElementById("func_home").addEventListener("click", function () {
    window.location.reload();
  });

  document.getElementById("func_logout").addEventListener("click", function () {
    send_request(`request=delete_session`).then((_) => {
      window.location.reload();
    });
  });
}

export async function show_products() {
  const container = document.getElementsByClassName("content")[0];

  try {
    const response = await send_request("request=show_products").then((data) => {
      if (!data.ok) {
        throw new Error("Server related error");
      }

      return data.json();
    });

    container.insertAdjacentHTML("beforeend", response.html);

    //Open product page - functionality
    container.addEventListener("click", async function (e) {
      if (e.target.classList.contains("thumbnail") || e.target.classList.contains("button")) {
        const id = e.target.parentElement.parentElement.id;
        container.parentElement.classList.add("right_section_nosearch");

        try {
          const response = await send_request("request=get_product&id=" + id).then((data) => {
            if (!data.ok) {
              throw new Error("Server related error");
            }

            return data.json();
          });

          container.parentElement.innerHTML = response.html;
        } catch (error) {
          console.log(error);
        }

        provide_func_product();
      }
    });
  } catch (error) {
    console.log(error);
  }
}

// Log in functionality
export async function login_func() {
  const form = {
    username: document.getElementById("username").value,
    password: document.getElementById("password").value,
    message: document.getElementsByClassName("error_message")[0],
  };

  try {
    // main functionality
    const response = await fetch("includes/php/check_login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `username=${form.username}&password=${form.password}`,
    }).then((data) => {
      if (!data.ok) {
        throw new Error("Server related error.");
      }
      return data.json();
    });

    // Succesful login - transfer to home page
    if (response.ok === true) {
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
    form.message.innerHTML = `<p class="p_error text7">${error}</p>`;
  }
}

const u_transition_home = function () {
  const container = document.getElementsByClassName("main_container")[0];
  container.classList.remove("login_container");
  container.classList.add("actual_container");
  container.innerHTML = "";

  container.insertAdjacentHTML(
    "beforeend",
    "<section class='left_section'> <div class='acc_menu'> <p class='acc_info'><span class='username text4'>Mark Gabson</span><span class='balance text6'>$2000.00</span></p> <nav class='w-menu'> <ul class='menu'><li class='regular_li text5' id='func_home'><span class='icon_menu icon_home'></span>Main page</li> <li class='regular_li text5'><span class='icon_menu icon_cart'></span>Cart</li> <li class='regular_li text5'><span class='icon_menu icon_orders'></span>Orders</li> <li class='regular_li text5' id='func_logout'><span class='icon_menu icon_logout'></span>Log out</li> </ul> </nav> </div> <div class='w-categories y_scroll'> <ul class='categories'> <li class='category regular_li text6'> <input type='checkbox' id='category1' /> <label for='category1'>Roses</label> </li> </ul> </div> </section> <section class='right_section'> <div class='w-search_bar'> <input type='text' class='search_bar text_input text6' id='seacrh_bar' placeholder='Search...' /> <span class='magnify_glass'></span> </div> <div class='content content_userhome y_scroll'></div> </section>"
  );

  show_products();
  u_provide_functionality();
};

export async function sign_up() {
  const form = {
    name: document.getElementById("name").value,
    username: document.getElementById("username").value,
    password: document.getElementById("password").value,
    re_password: document.getElementById("re_password").value,
    message: document.getElementsByClassName("error_message")[0],
  };

  try {
    if (form.re_password === form.password) {
      const response = await send_request(`request=sign_up&name=${form.name}&username=${form.username}&password=${form.password}`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      console.log(response);

      if (response.status) {
        open_modal("Your account has been created", function () {
          window.location.reload();
        });
      } else {
        throw new Error(response.message);
      }
    } else {
      throw new Error("Re-entered password is not the same.");
    }
  } catch (error) {
    form.message.innerHTML = `<p class="p_error text7">${error}</p>`;
  }
}
