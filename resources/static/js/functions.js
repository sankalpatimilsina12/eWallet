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
  var data = {};
  data['username'] = document.getElementsByName("username")[0].value;
  data['email'] = document.getElementsByName("email")[0].value;
  data['password'] = document.getElementsByName("password")[0].value;


  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/api/register',
    data: JSON.stringify(data),
    success: function(data) {
      if(data == 'Incorrect information.')
        document.getElementById('register-error').innerHTML = "Incorrect information.";
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

function submitLogin() {
  var data = {};
  data['email'] = document.getElementById("email").value;
  data['password'] = document.getElementById("pwd").value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/api/login',
    data: JSON.stringify(data),
    success: function(data) {
      if(data == 'Incorrect username or password.')
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
  alert('here');
  var email = document.getElementById("email").value;
  var data = {};
  data['email'] = document.getElementById("email").value;

  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/api/mailforgotpassword',
    data: JSON.stringify(data),
    success: function(data) {
      if(data == 'No such email found.')
        document.getElementById('email-error').innerHTML = 'Invalid email.';
      else {
        token(email);
      }
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });

  return false;
}

function resendToken() {
  var data = {};
  data['email'] = document.getElementById("email").innerHTML;


  $.ajax({
    type: 'POST',
    url: 'http://localhost/eWallet/api/mailforgotpassword',
    data: JSON.stringify(data),
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
  var data = {};
  data['token'] = document.getElementById("token").value;
  data['email'] = document.getElementById("email").innerHTML;
  data['password'] = document.getElementById("new-password").value;

  $.ajax({
    type: 'PUT',
    url: 'http://localhost/eWallet/api/verifytoken',
    data: JSON.stringify(data),
    success: function(data) {
      if(data == 'Invalid token or password.') {
        document.getElementById('token-error').innerHTML = "Invalid token or password.";

        setTimeout(function() {
          document.getElementById('token-error').innerHTML = "";
        }, 2000);
      }
      else {
        Finch.call("dashboard");
      }
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });

  return false;
}

function getDashboardData() {
  $.ajax({
    type: 'GET',
    url: 'http://localhost/eWallet/api/users',
    success: function(data) {
      if(data != "No user(s) found!")
        getDashboardTemplate(data);
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });
}
