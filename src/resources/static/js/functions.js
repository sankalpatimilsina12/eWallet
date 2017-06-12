function register() {
  Finch.navigate("register");
  return false;
}

function login() {
  Finch.navigate("login");
  return false;
}

function forgotPassword() {
  Finch.navigate("forgot-password");
  return false;
}

function token(email, resendStatus) {
  Finch.navigate("token", {email: email, resend: resendStatus});
  return false;
}

function logOut() {
  // document.cookie = 'access_token' + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  // Finch.call("logout");
  Finch.navigate("logout");
  return false;
}

function registerUser() {
  var username = document.getElementsByName("username")[0].value;
  var email = document.getElementsByName("email")[0].value;
  var password = document.getElementsByName("password")[0].value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/src/controllers/register.php',
    data: {
      username: username,
      email: email,
      password: password
    },
    success: function(data) {
      document.cookie = "access_token=" + data;
      Finch.navigate("dashboard");
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }

  });
  
  return false;
}

function submitLogin() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("pwd").value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/src/controllers/login.php',
    data: {
      email: email,
      password: password
    },
    success: function(data) {
      if(data == 0)
        document.getElementById("login-error").innerHTML = "Incorrect username or password.";
      else {
        document.cookie = "access_token=" + data;
        Finch.navigate("dashboard");
      }
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });

  return false;
}

function submitForgotPassword() {
  var email = document.getElementById("email").value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/src/controllers/forgot-password.php',
    data: {
      email: email
    },
    success: function() {
      token(email);
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });

  return false;
}

function resendToken() {
  var email = document.getElementById("email").innerHTML;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/src/controllers/forgot-password.php',
    data: {
      email: email
    },
    success: function() {
      document.getElementById("resend-message").innerHTML = "Resend success!";
      setTimeout(function(){
        document.getElementById("resend-message").innerHTML = "";
      }, 2000);
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });
}

function verifyToken(e) {
  e.preventDefault();
  var token = document.getElementById("token").value;
  var email = document.getElementById("email").innerHTML;
  var password = document.getElementById("new-password").value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/src/controllers/verify-token.php',
    data: {
      token: token,
      email: email,
      password: password
    },
    success: function(data) {
      if(data == true)
        Finch.call("dashboard");
      else
        console.log("Invalid Token!");
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });

  return false;
}

function getDashboardData() {
  var dashboard_data;
  $.ajax({
    type: 'GET',
    url: 'http://localhost/eWallet/src/controllers/dashboard.php',
    success: function(data) {
      getDashboardTemplate(data);
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });
}