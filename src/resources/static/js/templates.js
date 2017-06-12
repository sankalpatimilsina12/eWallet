function getRegisterTemplate() {
  $.ajax({
    url: 'http://localhost/eWallet/src/templates/register.php',
    method: 'GET',
    contentType: 'html',
    success: function(data) {
      var template = Handlebars.compile(data);

      var context = {
        site_name: "eWallet",
        footer: "Footer"
      }

      document.getElementsByClassName("content")[0].innerHTML = template(context);
    }
  });
}

function getLoginTemplate() {
  $.ajax({
    url: 'http://localhost/eWallet/src/templates/login.php',
    method: 'GET',
    contentType: 'html',
    success: function(data) {
      var template = Handlebars.compile(data);
      
      var context = {
        site_name: "eWallet",
        footer: "Footer"
      }

      var html = template(context);
      
      document.getElementsByClassName("content")[0].innerHTML = html;
    }
  });
}

function getLogOutTemplate() {
  document.getElementsByClassName("content")[0].innerHTML = "";
}

function getDashboardTemplate(dashboard_data) {
  $.ajax({
    type: 'GET',
    url: "http://localhost/eWallet/src/templates/dashboard.php",
    success: function(data) {
      var template = Handlebars.compile(data);
      var html = template({data: JSON.parse(dashboard_data)});

      document.getElementsByClassName("content")[0].innerHTML = html;
    },
    error: function(xhr) {
      console.log(xhr.responseText);
    }
  });
}

function getForgotPasswordTemplate() {
  $.ajax({
    type: 'GET',
    url: 'http://localhost/eWallet/src/templates/forgot-password.php',
    success: function(data) {
      document.getElementsByClassName("content")[0].innerHTML = data;
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });  
}

function getTokenTemplate(email) {
  $.ajax({
    type: 'GET',
    url: 'http://localhost/eWallet/src/templates/token_page.php',
    success: function(data) {
      var template = Handlebars.compile(data);
      var context = {
        email: email
      }
      document.getElementsByClassName("content")[0].innerHTML = template(context);
    },
    error: function(xhr) {
      console.error(xhr.responseText);
    }
  });  
}