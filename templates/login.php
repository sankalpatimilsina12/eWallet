<form class="form" onSubmit="return submitLogin();">
  <div id="login-error" style="color: green"></div>
  <br>
  <div class="form-group">
    <label for="email"><strong>Email:</strong></label>
    <input type="email" class="form-control" id="email">
  </div>
  <div class="form-group">
    <label for="pwd"><strong>Password:</strong></label>
    <input type="password" class="form-control" id="pwd">
    <br>
    <a href="#" onclick="return forgotPassword();">Forgot Password?</a>
  </div>
  <br>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
