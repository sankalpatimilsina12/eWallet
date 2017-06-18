Finch.route("/", getLogOutTemplate);
Finch.route("register", getRegisterTemplate);
Finch.route("login", getLoginTemplate);
Finch.route("dashboard", getDashboardData);
Finch.route("forgot-password", getForgotPasswordTemplate);
Finch.route("token", function(bindings) {
  Finch.observe(["email"], function(email) {
    getTokenTemplate(email);
  });
});
Finch.route("logout", getLogOutTemplate);
Finch.listen();