<form class="form" onSubmit="return registerUser();">
  <div id="register-error" style="color: green"></div>
  <br>
  <div class="form-group">
    <label><strong>Username:</strong></label>
    <input class="form-control" name="username">
  </div>
  <div class="form-group">
    <label for="email"><strong>Email:</strong></label>
    <input type="email" class="form-control" name="email">
  </div>
  <div class="form-group">
    <label for="pwd"><strong>Password:</strong></label>
    <input type="password" class="form-control" name="password">
  </div>
  <br>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
