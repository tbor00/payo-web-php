<div class="row" style="height:470px;">
  <div class="col-sm-4">
  </div>
  <div class="col-sm-4">
    <div class="well">
      <h2>Acceso exclusivo para clientes</h2>
      <p><strong>Ingrese su usuario y contrase&ntilde;a</strong></p>
      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <INPUT TYPE="HIDDEN" NAME="auth" VALUE="login"> 
        <div class="form-group">
          <label class="control-label" for="input-email">Usuario</label>
          <input type="text" name="user" value="" placeholder="Usuario" id="input-user" class="form-control" autofocus />
        </div>
        <div class="form-group">
          <label class="control-label" for="input-password">Contrase&ntilde;a</label>
          <input type="password" name="passwd" value="" placeholder="Contrase&ntilde;a" id="input-password" class="form-control" />
          <a href="<?php echo $_SERVER['PHP_SELF']."?auth=forgot" ?>">&iquest;Contrase&ntilde;a olvidada?</a></div>
          <input type="submit" value="Entrar" class="btn btn-primary" />
      </form>
    </div>
  </div>
</div>


