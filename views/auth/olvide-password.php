<h1 class="nombre-pagina">Olvide mi Password</h1>
<p class="descripcion-pagina">Reestablecer tu password completando el campo a continuacion</p>

<?php include_once __DIR__ ."/../templates/alertas.php"; ?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="Tu E-mail">
    </div>

    <input type="submit" class="boton" value="Reestablecer">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear</a>
</div>