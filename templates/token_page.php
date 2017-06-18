<form class="form" onSubmit="return verifyToken(event);">
  <span id="email" style="display: none">{{ email }}</span>
  <div class="row">
    <div class="col-sm-6" id="message" style="color: green;">A mail has been sent to <strong style="color: #000">{{ email }}</strong></div>
    <button class="btn btn-default" type="button" onClick="resendToken();" style="position: absolute; left: 37%">Resend</button>
    <span class="col-sm-2" id="resend-message" style="color: green; position: absolute; right: 1%; font-size: 16px"></span>
  </div>
  <br>
  <div class="form-group">
    <label><strong>Token:</strong></label>
    &nbsp;&nbsp;
    <span id="token-error" style="color: green"></span>
    <input class="form-control" id="token">
  </div>
  <br>
  <div class="form-group">
    <label><strong>New Password:</strong></label>
    <input type="password" class="form-control" id="new-password">
  </div>
  <br>
  <button type="submit" class="btn btn-success">Submit</button>
</form>
