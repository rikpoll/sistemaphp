<div class="alterardados">
  <?php
    $user = mysqli_query($connect, "SELECT * FROM utilizadores WHERE id=".$userId);
    if ($user->num_rows > 0) {
      $row = mysqli_fetch_array($user);
      $userId = $row['id'];
      $userLogin = $row['login'];
      $userNome = $row['nome'];
      $userApelido = $row['apelido'];
      $userNC = $row['nome_completo'];
      $userEmail = $row['email'];
      $userAcesso = $row['acesso'];
    }
  ?>	

  <form method="POST">
    <input type="hidden" name="id" value="<?php print($userId); ?>">
    <div class="linha">
      <div class="label">Utilizador:</div>
      <div class="dado"><?php print($userLogin); ?></div>
    </div>
    <div class="linha">
      <div class="label">Nome:</div>
      <div class="dado"><input type="text" name="nome" value="<?php print($userNome); ?>"></div>
    </div>
    <div class="linha">
      <div class="label">Apelido:</div>
      <div class="dado"><input type="text" name="apelido" value="<?php print($userApelido); ?>"></div>
    </div>
    <div class="linha">
      <div class="label">Nome Completo:</div>
      <div class="dado"><input type="text" name="nc" value="<?php print($userNC); ?>"></div>
    </div>
    <div class="linha">
      <div class="label">Email:</div>
      <div class="dado"><input type="text" name="mail" value="<?php print($userEmail); ?>"></div>
    </div>
    <div class="linha">
      <div class="label">Admin?</div>
      <div class="dado">
        <select name="admin">
          <option value="0" <?php if ($userAcesso==0) print("selected"); ?>>Nao</option>
          <option value="1" <?php if ($userAcesso==1) print("selected"); ?>>Sim</option>
        </select>
      </div>
    </div>
    <div class="linha">
      <input type="submit" class="input" name="alterar" value="Alterar">
    </div>
  </form>
</div>

<?php
  if (isset($_POST["alterar"])) {
    $id = $_POST["id"];
    $insNome = $_POST["nome"];
    $insApelido = $_POST["apelido"];
    $insNC = $_POST["nc"];
    $insEmail = $_POST["mail"];
    $insAdmin = $_POST["admin"];
    
    $sql="update utilizadores set nome='{$insNome}', apelido='{$insApelido}',
                                  nome_completo='{$insNC}', email='{$insEmail}', acesso={$insAdmin}
          where id={$id}";
    
    $result=$connect->query($sql);
  } 
?>
