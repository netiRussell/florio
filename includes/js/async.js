export async function login_func() {
  const login_form = {
    username: document.getElementById("username"),
    password: document.getElementById("password"),
    message: document.getElementById("error_message"),
  };

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

    console.log(response);
  } catch (error) {
    // Error handling
    console.log(error);
  }
}
