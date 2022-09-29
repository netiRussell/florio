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

const u_provide_func_product = function () {
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

const retrieve_html = async function (request, wrapper, change_container = function () {}, provide_func = function () {}) {
  const container = wrapper;
  try {
    const response = await send_request(request).then((data) => {
      if (!data.ok) {
        throw new Error("Server related error");
      }

      return data.json();
    });

    if (!response.status) {
      throw new Error("Server related error(php)");
    }

    change_container(container);
    container.innerHTML = response.html;

    provide_func();
  } catch (error) {
    open_modal(error);
  }
};

const look_up = function (container, request, type = "none") {
  container.innerHTML = "<div class='loader'></div>";
  let home = false;
  if (container.classList.contains("content_userhome")) {
    container.classList.remove("content_userhome");
    home = true;
  }
  container.classList.add("content_loading");

  retrieve_html(request, container, function () {
    container.classList.remove("content_loading");
    if (home) {
      container.classList.add("content_userhome");
    }
  });
};

const search_bar_func = function (type) {
  const objs = {
    search_bar: document.getElementById("seacrh_bar"),
    button: document.getElementById("magnify_glass"),
    container: document.querySelector(".content"),
  };

  const functionality = function (request) {
    look_up(objs.container, "request=" + request + "&value=" + objs.search_bar.value);
  };

  if (type === "home") {
    objs.search_bar.addEventListener("change", function () {
      functionality("get_products_byname");
    });

    objs.button.addEventListener("click", function () {
      functionality("get_products_byname");
    });
  } else if (type === "cart") {
    objs.search_bar.addEventListener("change", function () {
      functionality("get_products_byname_cart");
    });

    objs.button.addEventListener("click", function () {
      functionality("get_products_byname_cart");
    });
  } else if (type === "orders") {
    objs.search_bar.addEventListener("change", function () {
      functionality("get_products_byname_orders");
    });

    objs.button.addEventListener("click", function () {
      functionality("get_products_byname_orders");
    });
  }
};

const search_categories_func = function () {
  const container = document.querySelector(".content");
  const categories_arr = [];

  document.querySelector(".w-categories").addEventListener("click", function (event) {
    const category = event.target;

    if (category.tagName.toLowerCase() === "input") {
      const index = categories_arr.indexOf(category.id);
      if (index >= 0) {
        categories_arr.splice(index, 1);
      } else {
        categories_arr.push(category.id);
      }

      look_up(container, "request=get_products_bycategories&value=" + categories_arr.join(","));
    }
  });
};

const u_provide_func_cart = function () {
  //place order
  document.querySelector(".table").addEventListener("click", async function (e) {
    if (e.target.classList.contains("place_order_func")) {
      var tr_container = e.target.parentElement?.parentElement;
      const order_id = tr_container.id;

      const objs = {
        delivery_address: tr_container.querySelector("input[data-func='delivery_address_func']").value,
        delivery_date: tr_container.querySelector("input[data-func='delivery_date_func']").value,
      };

      try {
        const response = await send_request("request=u_place_order&order_id=" + order_id + "&address=" + objs.delivery_address + "&date=" + objs.delivery_date).then((data) => {
          return data.json();
        });

        if (!response.status) {
          throw new Error(response.message);
        }

        show_acc_info();

        open_modal("We have recived your order.", function () {
          tr_container.remove();
        });
      } catch (error) {
        open_modal(error);
      }
    }
  });

  search_bar_func("cart");
};

/** Export */
export async function send_request(request_body) {
  return await fetch("includes/php/requests.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: request_body,
  });
}

// Sign up functionality
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
      send_request(`request=init_session&id=${response.id}&name=${response.name}&role=${response.role}&balance=${response.account}`).then((_) => {
        window.location.reload();
      });
    } else {
      throw new Error(response.message);
    }
  } catch (error) {
    // Error handling
    form.message.innerHTML = `<p class="p_error text7">${error}</p>`;
  }
}

// Functionality for user
export function u_provide_functionality() {
  const container = document.getElementById("right_section");
  const change_container = function (container) {
    container.classList.add("right_section_nocat");
    container.parentElement?.querySelector(".w-categories")?.remove();
    container.parentElement?.querySelector(".left_section")?.classList.add("left_no_cat");
  };

  // Main page
  document.getElementById("func_home").addEventListener("click", function () {
    window.location.reload();
  });
  //Cart
  document.getElementById("func_cart").addEventListener("click", function () {
    retrieve_html("request=u_show_cart", container, change_container, function () {
      u_provide_func_cart();
    });
  });
  //Orders
  document.getElementById("func_orders").addEventListener("click", function () {
    retrieve_html("request=u_show_orders", container, change_container, function () {
      search_bar_func("orders");
      document.getElementById("seacrh_bar").placeholder = "Seacrh by id(strict)";
    });
  });
  //Log out
  document.getElementById("func_logout").addEventListener("click", function () {
    send_request(`request=delete_session`).then((response) => {
      window.location.reload();
    });
  });

  search_bar_func("home");
  search_categories_func();
}

// Show all products(in case of having more than 500 products - implement products loading as the user scrolls)
export async function show_products() {
  const container = document.getElementsByClassName("content")[0];
  container.classList.add("content_userhome");

  retrieve_html(
    "request=show_products",
    container,
    function () {
      container.classList.remove("content_loading");
    },
    function () {
      container.addEventListener("click", async function (e) {
        if (e.target.classList.contains("thumbnail") || e.target.classList.contains("button")) {
          const id = e.target.parentElement?.parentElement?.id;
          container.parentElement?.classList.add("right_section_nosearch");

          retrieve_html(`request=get_product&id=${id}`, container.parentElement, function () {}, u_provide_func_product);
        }
      });
    }
  );
}

export async function show_categories() {
  retrieve_html("request=show_categories", document?.getElementById("w-categories"));
}

export async function show_acc_info() {
  retrieve_html("request=show_acc_info", document.getElementById("acc_info"));
}
