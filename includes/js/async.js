export async function send_request(request_body) {
  await fetch("includes/php/requests.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: request_body,
  });
}

export async function u_transition_home() {
  const container = document.getElementsByClassName("login_container")[0];
  container.classList.remove("login_container");
  container.classList.add("actual_container");
  container.innerHTML = "";

  container.insertAdjacentHTML(
    "beforeend",
    "<section class='left_section'> <div class='acc_menu'> <p class='acc_info'><span class='username text4'>Mark Gabson</span><span class='balance text6'>$2000.00</span></p> <nav class='w-menu'> <ul class='menu'> <li class='regular_li text5'><span class='icon_menu icon_cart'></span>Cart</li> <li class='regular_li text5'><span class='icon_menu icon_orders'></span>Orders</li> </ul> </nav> </div> <div class='w-categories y_scroll'> <ul class='categories'> <li class='category regular_li text6'> <input type='checkbox' id='category1' /> <label for='category1'>Roses</label> </li> </ul> </div> </section> <section class='right_section'> <div class='w-search_bar'> <input type='text' class='search_bar text_input text6' id='seacrh_bar' placeholder='Search...' /> <span class='magnify_glass'></span> </div> <div class='content content_userhome y_scroll'> <div class='product'> <div class='w-thumbnail'> <img class='thumbnail' src='img/markus-clemens-mibjbNoS1XA-unsplash.jpg' alt='product1' /> </div> <p class='product_text text7'>Lorem Ipsum: is not just a simple random text. It has roots from classical Latin literature written way back then.</p> <div class='w-product_order'> <button class='button button_cart text7'>Order</button> <p class='product_price text7'>$39.99</p> </div> </div> </div> </section>"
  );
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

      //init session
      send_request(`request=init_session&id=${response.id}&name=${response.name}`);
      if (response.role === "user") {
        u_transition_home();
      } else if (response.role === "admin") {
      }
    } else {
      throw new Error(response.message);
    }
  } catch (error) {
    // Error handling
    document.getElementsByClassName("error_message")[0].innerHTML = `<p class="p_error text7">${error}</p>`;
  }
}

//For the future: waiting function - until the window has expanded enough to accommodate all the elements of the home page
/**function whichTransitionEvent(){
  var t;
  var el = document.createElement('fakeelement');
  var transitions = {
    'transition':'transitionend',
    'OTransition':'oTransitionEnd',
    'MozTransition':'transitionend',
    'WebkitTransition':'webkitTransitionEnd'
  }

  for(t in transitions){
      if( el.style[t] !== undefined ){
          return transitions[t];
      }
  }
}

var transitionEnd = whichTransitionEvent();
element.addEventListener(transitionEnd, theFunctionToInvoke, false);

function theFunctionToInvoke(){
// set margin of div here
}*/
