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

    data = response;
  } catch (error) {
    // Error handling
    console.log(error);
  }

  // Succesful login - transfer to home page
  if (data.ok === true) {
    console.log(data);
    if (data.role === "user") {
    } else if (data.role === "admin") {
    }
  } else {
    console.log("failed", data);
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
